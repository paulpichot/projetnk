<?php

/**
 * Adresses IP du serveur : sortantes (celles que voient les services appelés par le site, Brevo par exemple), en IPv6
 * et en IPv4, et adresse sur laquelle le site répond. Outil ponctuel : à supprimer du serveur une fois l'adresse notée
 */

// Adresse vue de l'extérieur, en forçant IPv6 ou IPv4 (service ipify)
function outgoingIp(string $url, int $resolve): string
{
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 8,
        CURLOPT_IPRESOLVE => $resolve,
    ]);
    $ip = trim((string) curl_exec($curl));
    $error = curl_error($curl);
    curl_close($curl);

    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
    }

    return 'indisponible' . ($error ? ' (' . $error . ')' : '');
}

$rows = [
    'IPv6 sortante' => outgoingIp('https://api6.ipify.org', CURL_IPRESOLVE_V6),
    'IPv4 sortante' => outgoingIp('https://api.ipify.org', CURL_IPRESOLVE_V4),
    'Adresse du site' => $_SERVER['SERVER_ADDR'] ?? 'inconnue',
];

header('Content-Type: text/html; charset=utf-8');
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>Adresses IP du serveur</title>
    <style>
        body { margin: 3rem; font: 1rem/1.5 system-ui, sans-serif; color: #282622; background: #FFF7EB; }
        dt { margin-top: 1.25rem; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.04em; }
        dd { margin: 0.25rem 0 0; font: 1.25rem/1.4 ui-monospace, monospace; user-select: all; }
    </style>
</head>
<body>
    <dl>
        <?php foreach ($rows as $label => $value): ?>
            <dt><?= htmlspecialchars($label) ?></dt>
            <dd><?= htmlspecialchars($value) ?></dd>
        <?php endforeach ?>
    </dl>
</body>
</html>
