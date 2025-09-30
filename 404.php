<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <title>Seite nicht gefunden (404) – Isabella Signer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, follow" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Isabella Signer" />
    <link rel="manifest" href="/site.webmanifest" />

    <link rel="stylesheet" href="/main.css" />
</head>
<body>
    <?php require_once 'nav.php'; ?>

    <header>
        <h3>404</h3>
        <h2 class="sub">Die gewünschte Seite wurde nicht gefunden.</h2>
    </header>

    <section class="container">
        <p class="small">Mögliche Gründe: Der Link ist veraltet, die Seite wurde umbenannt oder entfernt.</p>
        <p class="small" style="margin-top: 16px;">Das kannst du als Nächstes tun:</p>
        <ul style="margin-top: 10px; list-style: disc; padding-left: 20px;">
            <li><a href="/">Zur Startseite</a></li>
            <li><a href="/angebot">Mein Angebot</a></li>
            <li><a href="/termin">Termin buchen</a></li>
            <li><a href="/kontakt">Kontakt</a></li>
        </ul>
    </section>

    <?php require_once 'footer.php'; ?>
    <?php require_once 'script.php'; ?>
    <?php require_once 'googleanalytics.php'; ?>
</body>
<html>

