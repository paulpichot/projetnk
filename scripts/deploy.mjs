/**
 * Déploiement du site en FTP (basic-ftp), sans jamais toucher au contenu modifié en production.
 *
 *   npm run deploy                         compile puis envoie le code
 *   npm run deploy -- --test               essai à blanc : liste ce qui serait envoyé ou supprimé, sans rien changer
 *   npm run deploy -- --contenu <dossier>  envoie aussi les fichiers d'un dossier de content/ absents du serveur
 *                                          (ex. --contenu inscription-confirmee) ; un fichier déjà en ligne n'est
 *                                          jamais remplacé
 *
 * Envoyé (dossiers remis à l'identique, fichiers en trop supprimés) : dist, kirby, vendor, assets et le code de site/
 * (blueprints, snippets, templates, plugins, models, api, languages), plus index.php, site/config/config.php et
 * site/config/vite.config.php. Un fichier n'est renvoyé que s'il a changé depuis le dernier envoi (empreintes des
 * fichiers envoyés gardées dans .deploy-state.json, non versionné) ou s'il manque ou diffère sur le serveur.
 * Jamais envoyé ni supprimé : content/ (hors --contenu), media/, site/accounts, site/sessions, site/cache, les autres
 * fichiers de site/config (newsletter.php, licence…), .htaccess, .htpasswd.
 * Bibliothèques (kirby/, vendor/ et dossiers vendor des plugins) : tant qu'aucune empreinte n'est enregistrée, un
 * fichier déjà en ligne avec la même taille est tenu pour identique (évite de renvoyer des milliers de fichiers
 * inchangés au premier envoi).
 * Après l'envoi, le cache des pages (site/cache) est vidé sur le serveur.
 *
 * Réglages dans .deploy.env (non versionné, voir deploy.env.example).
 */
import { Client } from 'basic-ftp';
import { execSync } from 'node:child_process';
import { createHash } from 'node:crypto';
import { existsSync, readFileSync, readdirSync, statSync, writeFileSync } from 'node:fs';
import { dirname, join, posix, relative, resolve } from 'node:path';
import { createInterface } from 'node:readline';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
process.chdir(root);

const FOLDERS = ['dist', 'kirby', 'vendor', 'assets', 'site/blueprints', 'site/snippets', 'site/templates', 'site/plugins', 'site/models', 'site/api', 'site/languages'];
const FILES = ['index.php', 'site/config/config.php', 'site/config/vite.config.php'];
const IGNORED = new Set(['.DS_Store']);

const fail = (message) => {
    console.error(message);
    process.exit(1);
};

// Options
const args = process.argv.slice(2);
const dry = args.includes('--test');
const contents = [];
for (let i = 0; i < args.length; i++) {
    if (args[i] === '--test') continue;
    if (args[i] === '--contenu') {
        const folder = (args[++i] ?? '').replace(/^content\//, '').replace(/\/$/, '');
        if (!folder || folder.includes('..') || !existsSync(join('content', folder)) || !statSync(join('content', folder)).isDirectory()) {
            fail(`Dossier de contenu introuvable : content/${folder}`);
        }
        contents.push(`content/${folder}`);
        continue;
    }
    fail(`Option inconnue : ${args[i]}`);
}

// Réglages
if (!existsSync('.deploy.env')) fail('Réglages manquants : copier deploy.env.example en .deploy.env et le remplir');
const env = Object.fromEntries(
    readFileSync('.deploy.env', 'utf8')
        .split('\n')
        .map((line) => line.trim())
        .filter((line) => line && !line.startsWith('#') && line.includes('='))
        .map((line) => {
            const index = line.indexOf('=');
            return [line.slice(0, index).trim(), line.slice(index + 1).trim().replace(/^(['"])(.*)\1$/, '$2')];
        }),
);
for (const key of ['FTP_HOST', 'FTP_USER', 'REMOTE_DIR']) {
    if (!env[key]) fail(`${key} manquant dans .deploy.env`);
}

// Mot de passe saisi sans affichage s'il n'est pas dans .deploy.env
const askPassword = (question) => new Promise((done) => {
    const input = createInterface({ input: process.stdin, output: process.stdout, terminal: true });
    input._writeToOutput = (text) => {
        if (text.startsWith(question)) process.stdout.write(question);
    };
    input.question(question, (answer) => {
        input.close();
        process.stdout.write('\n');
        done(answer);
    });
});
const password = env.FTP_PASSWORD || process.env.FTP_PASSWORD || await askPassword(`Mot de passe FTP de ${env.FTP_USER} : `);

// Compilation (vite build supprime .dev : remis ensuite pour le développement local)
const dev = existsSync('.dev') ? readFileSync('.dev', 'utf8') : null;
execSync('npm run build', { stdio: 'inherit' });
if (dev !== null) writeFileSync('.dev', dev);

// Empreintes des fichiers déjà envoyés, par serveur et dossier
const stateFile = '.deploy-state.json';
const stateKey = `${env.FTP_USER}@${env.FTP_HOST}/${env.REMOTE_DIR}`;
const allStates = existsSync(stateFile) ? JSON.parse(readFileSync(stateFile, 'utf8')) : {};
const state = allStates[stateKey] ?? {};
const hash = (path) => createHash('sha1').update(readFileSync(path)).digest('hex');

// Fichiers locaux d'un dossier (chemins relatifs, avec date et taille)
const localTree = (folder) => {
    const files = new Map();
    const walk = (dir) => {
        for (const entry of readdirSync(dir, { withFileTypes: true })) {
            if (IGNORED.has(entry.name)) continue;
            const path = join(dir, entry.name);
            if (entry.isDirectory()) walk(path);
            else if (entry.isFile()) {
                const { size, mtime } = statSync(path);
                files.set(relative(folder, path).split('\\').join('/'), { size, mtime });
            }
        }
    };
    walk(folder);
    return files;
};

const client = new Client(30000);
let base = '/';
// Dossiers distants dont l'existence est connue (créés une seule fois)
const knownDirs = new Set(['.']);
// Bibliothèques : fichier en ligne de même taille tenu pour identique tant qu'aucune empreinte n'est enregistrée
const isLibrary = (path) => /^(kirby|vendor)\//.test(path) || path.includes('/vendor/');
const stats = { sent: 0, removed: 0, kept: 0 };
const say = (action, path) => console.log(`${dry ? '[essai] ' : ''}${action} ${path}`);

// Contenu distant d'un dossier : fichiers (taille, date) et sous-dossiers, chemins relatifs ; vide s'il n'existe pas
const remoteTree = async (folder) => {
    const files = new Map();
    const dirs = [];
    const walk = async (dir) => {
        let list;
        try {
            list = await client.list(dir);
        } catch {
            return;
        }
        for (const item of list) {
            if (item.name === '.' || item.name === '..') continue;
            const path = posix.join(dir, item.name);
            const rel = posix.relative(folder, path);
            if (item.isDirectory) {
                dirs.push(rel);
                await walk(path);
            } else {
                files.set(rel, { size: item.size, mtime: item.modifiedAt });
            }
        }
    };
    await walk(folder);
    return { files, dirs };
};

const upload = async (local, remote) => {
    say('envoi  ', remote);
    stats.sent++;
    if (dry) return;
    const dir = posix.dirname(remote);
    if (!knownDirs.has(dir)) {
        await client.ensureDir(posix.join(base, dir));
        await client.cd(base);
        dir.split('/').forEach((_, i, parts) => knownDirs.add(parts.slice(0, i + 1).join('/')));
    }
    await client.uploadFrom(local, remote);
    state[remote] = hash(local);
};

// Fichier à renvoyer : absent ou de taille différente sur le serveur, ou modifié en local depuis son dernier envoi
const changed = (localPath, remotePath, local, remote) => {
    if (!remote || remote.size !== local.size) return true;
    if (!state[remotePath] && isLibrary(remotePath)) {
        if (!dry) state[remotePath] = hash(localPath);
        return false;
    }
    return state[remotePath] !== hash(localPath);
};

// Dossier remis à l'identique (missing : seulement les fichiers absents, sans rien supprimer)
const mirror = async (folder, { missingOnly = false } = {}) => {
    const local = localTree(folder);
    const remote = await remoteTree(folder);
    if (remote.files.size || remote.dirs.length) knownDirs.add(folder);
    remote.dirs.forEach((dir) => knownDirs.add(posix.join(folder, dir)));
    for (const [path, info] of local) {
        const target = remote.files.get(path);
        if (missingOnly ? !target : changed(join(folder, path), posix.join(folder, path), info, target)) await upload(join(folder, path), posix.join(folder, path));
        else stats.kept++;
    }
    if (missingOnly) return;
    for (const path of remote.files.keys()) {
        if (local.has(path) || IGNORED.has(posix.basename(path))) continue;
        say('suppr. ', posix.join(folder, path));
        stats.removed++;
        if (!dry) await client.remove(posix.join(folder, path));
        delete state[posix.join(folder, path)];
    }
    // Sous-dossiers vides ou absents en local, des plus profonds aux plus hauts
    const localDirs = new Set([...local.keys()].flatMap((path) => path.split('/').slice(0, -1).map((_, i, parts) => parts.slice(0, i + 1).join('/'))));
    for (const dir of remote.dirs.sort((a, b) => b.length - a.length)) {
        if (localDirs.has(dir)) continue;
        say('suppr. ', `${posix.join(folder, dir)}/`);
        if (!dry) {
            await client.removeDir(posix.join(base, folder, dir)).catch(() => {});
            await client.cd(base);
        }
    }
};

try {
    await client.access({
        host: env.FTP_HOST,
        port: Number(env.FTP_PORT || 21),
        user: env.FTP_USER,
        password,
        secure: env.FTP_TLS !== 'false',
    });
    await client.cd(env.REMOTE_DIR);
    base = await client.pwd();
    console.log(`\n${dry ? 'Essai à blanc : rien ne sera envoyé ni supprimé' : `Envoi vers ${env.FTP_HOST}/${env.REMOTE_DIR}`}\n`);

    for (const folder of FOLDERS) {
        if (existsSync(folder)) await mirror(folder);
    }
    for (const file of FILES) {
        if (!existsSync(file)) continue;
        const { size, mtime } = statSync(file);
        const remote = (await remoteTree(posix.dirname(file))).files.get(posix.basename(file));
        if (changed(file, file, { size, mtime }, remote)) await upload(file, file);
        else stats.kept++;
    }
    for (const folder of contents) await mirror(folder, { missingOnly: true });

    // Cache des pages vidé
    if (!dry) {
        const cache = await client.list('site/cache').catch(() => []);
        for (const item of cache) {
            if (item.name === '.' || item.name === '..') continue;
            const path = posix.join('site/cache', item.name);
            await (item.isDirectory ? client.removeDir(posix.join(base, path)) : client.remove(path)).catch(() => {});
            await client.cd(base);
        }
    }

    console.log(`\n${stats.sent} fichier(s) ${dry ? 'à envoyer' : 'envoyé(s)'}, ${stats.removed} ${dry ? 'à supprimer' : 'supprimé(s)'}, ${stats.kept} inchangé(s).`);
    console.log(dry ? 'Essai terminé.' : 'Déploiement terminé, cache vidé.');
} catch (error) {
    fail(`\nÉchec du déploiement : ${error.message}`);
} finally {
    client.close();
    // Empreintes enregistrées même après un échec : les fichiers déjà envoyés ne repartiront pas
    if (!dry) writeFileSync(stateFile, `${JSON.stringify({ ...allStates, [stateKey]: state }, null, 1)}\n`);
}
