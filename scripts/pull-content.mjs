/**
 * Récupération du contenu en ligne (content/ du serveur) dans le content/ local, pour travailler sur les textes et
 * fichiers modifiés dans le panel en production.
 *
 *   npm run recuperer            remet content/ à l'identique du serveur (fichiers absents en ligne supprimés)
 *   npm run recuperer -- --test  essai à blanc : liste ce qui serait récupéré ou supprimé, sans rien changer
 *
 * Avant toute modification, le content/ local est sauvegardé dans .content-backups/<date>/ (non versionné).
 * Textes (.txt) toujours récupérés ; autres fichiers (images…) seulement s'ils manquent ou changent de taille.
 * Rien n'est jamais envoyé sur le serveur. Réglages dans .deploy.env, comme le déploiement (deploy.mjs).
 */
import { Client } from 'basic-ftp';
import { cpSync, existsSync, mkdirSync, readFileSync, readdirSync, rmSync, statSync } from 'node:fs';
import { dirname, join, posix, relative, resolve } from 'node:path';
import { createInterface } from 'node:readline';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
process.chdir(root);

const FOLDER = 'content';
const IGNORED = new Set(['.DS_Store']);
const dry = process.argv.includes('--test');

const fail = (message) => {
    console.error(message);
    process.exit(1);
};

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

// Fichiers locaux (chemins relatifs, taille)
const local = new Map();
const walkLocal = (dir) => {
    for (const entry of readdirSync(dir, { withFileTypes: true })) {
        if (IGNORED.has(entry.name)) continue;
        const path = join(dir, entry.name);
        if (entry.isDirectory()) walkLocal(path);
        else if (entry.isFile()) local.set(relative(FOLDER, path).split('\\').join('/'), statSync(path).size);
    }
};
if (existsSync(FOLDER)) walkLocal(FOLDER);

const client = new Client(30000);
const say = (action, path) => console.log(`${dry ? '[essai] ' : ''}${action} ${path}`);

try {
    await client.access({
        host: env.FTP_HOST,
        port: Number(env.FTP_PORT || 21),
        user: env.FTP_USER,
        password,
        secure: env.FTP_TLS !== 'false',
    });
    await client.cd(env.REMOTE_DIR);

    // Fichiers en ligne
    const remote = new Map();
    const walkRemote = async (dir) => {
        for (const item of await client.list(dir)) {
            if (item.name === '.' || item.name === '..' || IGNORED.has(item.name)) continue;
            const path = posix.join(dir, item.name);
            if (item.isDirectory) await walkRemote(path);
            else remote.set(posix.relative(FOLDER, path), item.size);
        }
    };
    await walkRemote(FOLDER);
    if (!remote.size) fail('Aucun contenu trouvé sur le serveur : rien n’est modifié');

    const fetches = [...remote].filter(([path, size]) => path.endsWith('.txt') || local.get(path) !== size).map(([path]) => path);
    const removals = [...local.keys()].filter((path) => !remote.has(path));

    // Sauvegarde du contenu local avant toute modification
    if (!dry && local.size) {
        const backup = join('.content-backups', new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19));
        mkdirSync(backup, { recursive: true });
        cpSync(FOLDER, join(backup, FOLDER), { recursive: true });
        console.log(`Contenu local sauvegardé dans ${backup}/\n`);
    }

    for (const path of fetches) {
        say(local.has(path) ? 'récup. ' : 'nouveau', `${FOLDER}/${path}`);
        if (dry) continue;
        mkdirSync(dirname(join(FOLDER, path)), { recursive: true });
        await client.downloadTo(join(FOLDER, path), posix.join(FOLDER, path));
    }
    for (const path of removals) {
        say('suppr. ', `${FOLDER}/${path}`);
        if (!dry) rmSync(join(FOLDER, path));
    }

    // Dossiers locaux devenus vides
    if (!dry) {
        const prune = (dir) => {
            for (const entry of readdirSync(dir, { withFileTypes: true })) {
                if (entry.isDirectory()) prune(join(dir, entry.name));
            }
            const left = readdirSync(dir).filter((name) => !IGNORED.has(name));
            if (dir !== FOLDER && !left.length) rmSync(dir, { recursive: true });
        };
        prune(FOLDER);
    }

    console.log(`\n${fetches.length} fichier(s) ${dry ? 'à récupérer' : 'récupéré(s)'}, ${removals.length} ${dry ? 'à supprimer' : 'supprimé(s)'} en local, ${remote.size - fetches.length} inchangé(s).`);
} catch (error) {
    fail(`\nÉchec de la récupération : ${error.message}`);
} finally {
    client.close();
}
