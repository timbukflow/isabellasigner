<?php
declare(strict_types=1);
require_once __DIR__ . '/antworten-lib.php';

/*
 * Methodenseite unter /begleitung/<slug>
 * (Emotional Bodywork & Breathwork, Mindset & Coaching, Ayurveda & Ernährung).
 * Inhalt kommt aus data/methoden/<slug>.json, der Aufbau ist bei allen gleich.
 */
$slug    = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
$datei   = __DIR__ . '/data/methoden/' . $slug . '.json';
$methode = preg_match('/^[a-z0-9-]{1,80}$/', $slug) && is_file($datei)
    ? json_decode((string)file_get_contents($datei), true)
    : null;

if (!is_array($methode)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$base         = 'https://isabella-signer.ch';
$url          = $base . '/begleitung/' . $methode['slug'];
$titel        = $methode['seo_titel'] ?? $methode['titel'];
$beschreibung = $methode['beschreibung'] ?? '';
$ogBild       = $base . '/' . ltrim($methode['hero']['bild'] ?? 'img/og-image.jpg', '/');
$jsonFlags    = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
$calendly     = "Calendly.initPopupWidget({url: 'https://calendly.com/isabella-signer/30min?text_color=27614b&primary_color=a790e5'});return false;";

$seite = [
  '@context' => 'https://schema.org',
  '@type' => 'WebPage',
  '@id' => $url . '#webpage',
  'url' => $url,
  'name' => $titel,
  'description' => $beschreibung,
  'inLanguage' => 'de-CH',
  'isPartOf' => ['@id' => $base . '/#website'],
  'about' => ['@id' => $url . '#service'],
  'keywords' => $methode['keywords'] ?? null,
  'primaryImageOfPage' => $ogBild,
  'breadcrumb' => ['@id' => $url . '#breadcrumb'],
];
$service = [
  '@context' => 'https://schema.org',
  '@type' => 'Service',
  '@id' => $url . '#service',
  'name' => $methode['titel'],
  'serviceType' => $methode['titel'],
  'description' => $beschreibung,
  'url' => $url,
  'image' => $ogBild,
  'provider' => ['@id' => $base . '/#praxis'],
  'areaServed' => ['@type' => 'Place', 'name' => 'Steinach SG, St. Gallen und Ostschweiz'],
  'availableLanguage' => 'de',
];
$breadcrumb = [
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  '@id' => $url . '#breadcrumb',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => $base . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Begleitung', 'item' => $base . '/begleitung'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => $methode['titel'], 'item' => $url],
  ],
];
?>
<!DOCTYPE html>
<html lang="de">
<head prefix="og: http://ogp.me/ns#">
    <meta charset="UTF-8" />
    <title><?= e($titel) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= e($beschreibung) ?>" />
    <meta name="author" content="Isabella Signer" />
    <link rel="canonical" href="<?= e($url) ?>" />
    <meta name="robots" content="index, follow" />

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />

    <meta property="og:title" content="<?= e($methode['titel']) ?> – Isabella Signer" />
    <meta property="og:description" content="<?= e($beschreibung) ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= e($url) ?>" />
    <meta property="og:image" content="<?= e($ogBild) ?>" />
    <meta property="og:locale" content="de_CH" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= e($methode['titel']) ?> – Isabella Signer" />
    <meta name="twitter:description" content="<?= e($beschreibung) ?>" />
    <meta name="twitter:image" content="<?= e($ogBild) ?>" />

    <link rel="stylesheet" href="/main.css?v=<?= filemtime(__DIR__ . '/main.css') ?>" />
    <link rel="preload" href="/fonts/rubis-light.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/<?= e(ltrim($methode['hero']['bild'], '/')) ?>" as="image" fetchpriority="high">

    <script type="application/ld+json">
<?= json_encode(array_filter($seite, fn($v) => $v !== null), $jsonFlags) ?>
    </script>
    <script type="application/ld+json">
<?= json_encode($service, $jsonFlags) ?>
    </script>
    <script type="application/ld+json">
<?= json_encode($breadcrumb, $jsonFlags) ?>
    </script>
    <?php require_once __DIR__ . '/schema.php'; ?>

    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
    <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async></script>
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>
    <main class="methode">
        <header>
            <h1 class="center methode-titel"><?= e($methode['titel']) ?></h1>

            <div class="methode-hero">
                <img src="/<?= e(ltrim($methode['hero']['bild'], '/')) ?>" alt="<?= e($methode['hero']['bild_alt'] ?? '') ?>" width="740" height="740" fetchpriority="high" />
            </div>

            <div class="question-cont">
                <h2><?= e($methode['einstieg']['titel']) ?></h2>
                <div class="question-sec">
                    <?php foreach ($methode['einstieg']['spalten'] as $spalte): ?>
                        <p><?= e($spalte) ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="btn">
                    <a class="btn-gruen" href="/termin" onclick="<?= e($calendly) ?>">Lass uns darüber reden</a>
                </div>
            </div>
        </header>

        <?php $bloecke = $methode['bloecke'] ?? []; require __DIR__ . '/bild-text.php'; ?>

        <?php
        $gruen       = $methode['gruen'];
        $gruenButton = 'Lass uns darüber reden';
        require __DIR__ . '/gruen-text.php';
        ?>

        <section class="light-sec methode-weitere">
            <?php
            $begleitungTitel  = $methode['weitere']['titel'] ?? null;
            $begleitungText   = $methode['weitere']['text'] ?? null;
            $begleitungKarten = $methode['weitere']['karten'] ?? null;
            require __DIR__ . '/begleitung-teaser.php';
            ?>
        </section>

        <?php
        $termineTitel = $methode['angebote']['titel'] ?? 'Was passt gerade zu dir?';
        $termineText  = $methode['angebote']['text'] ?? '';
        require __DIR__ . '/termine.php';
        ?>

        <?php require __DIR__ . '/antworten-teaser.php'; ?>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>
    <?php require_once __DIR__ . '/script.php'; ?>
    <?php require_once __DIR__ . '/googleanalytics.php'; ?>
</body>
</html>
