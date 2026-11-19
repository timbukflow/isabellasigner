<?php
declare(strict_types=1);
require_once __DIR__ . '/antworten-lib.php';

/*
 * Themenseite (Stress & innere Unruhe, Schlaf & Erschöpfung, …).
 * Inhalt kommt aus data/themen/<slug>.json, der Aufbau ist bei allen gleich.
 */
$slug  = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
$datei = __DIR__ . '/data/themen/' . $slug . '.json';
$thema = preg_match('/^[a-z0-9-]{1,80}$/', $slug) && is_file($datei)
    ? json_decode((string)file_get_contents($datei), true)
    : null;

if (!is_array($thema)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$base         = 'https://isabella-signer.ch';
$url          = $base . '/' . $thema['slug'];
$titel        = $thema['seo_titel'] ?? $thema['titel'];
$beschreibung = $thema['beschreibung'] ?? '';
$ogBild       = $base . '/' . ltrim($thema['bloecke'][0]['bild'] ?? 'img/og-image.jpg', '/');
$jsonFlags    = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;

// Alle Antworten, die zum Thema passenden zuerst
$teaserBeitraege = antworten_alle();
$themaTag = $thema['tag'] ?? '';
uasort($teaserBeitraege, function ($a, $b) use ($themaTag) {
    $pa = (int)in_array($themaTag, antwort_tags($a), true);
    $pb = (int)in_array($themaTag, antwort_tags($b), true);
    return $pb <=> $pa ?: strcmp($b['datum'] ?? '', $a['datum'] ?? '');
});

$seite = [
  '@context' => 'https://schema.org',
  '@type' => 'WebPage',
  '@id' => $url . '#webpage',
  'url' => $url,
  'name' => $titel,
  'description' => $beschreibung,
  'inLanguage' => 'de-CH',
  'isPartOf' => ['@id' => $base . '/#website'],
  'about' => ['@id' => $base . '/#praxis'],
  'keywords' => $thema['keywords'] ?? null,
  'primaryImageOfPage' => $ogBild,
];
$breadcrumb = [
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => $base . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => $thema['titel'], 'item' => $url],
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

    <meta property="og:title" content="<?= e($thema['titel']) ?> – Isabella Signer" />
    <meta property="og:description" content="<?= e($beschreibung) ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= e($url) ?>" />
    <meta property="og:image" content="<?= e($ogBild) ?>" />
    <meta property="og:locale" content="de_CH" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= e($thema['titel']) ?> – Isabella Signer" />
    <meta name="twitter:description" content="<?= e($beschreibung) ?>" />
    <meta name="twitter:image" content="<?= e($ogBild) ?>" />

    <link rel="stylesheet" href="/main.css?v=<?= filemtime(__DIR__ . '/main.css') ?>" />
    <link rel="preload" href="/fonts/rubis-light.woff2" as="font" type="font/woff2" crossorigin>

    <script type="application/ld+json">
<?= json_encode(array_filter($seite, fn($v) => $v !== null), $jsonFlags) ?>
    </script>
    <script type="application/ld+json">
<?= json_encode($breadcrumb, $jsonFlags) ?>
    </script>
    <?php require_once __DIR__ . '/schema.php'; ?>
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>
    <main>
        <header>
            <h1 class="center"><?= e($thema['titel']) ?></h1>

            <div class="kreis-cont">
                <span class="kreis parallax" data-speed="0.1" style="background: <?= e($thema['kreise'][0] ?? '#B9A2E9') ?>"></span>
                <span class="kreis overlap parallax" data-speed="-0.1" style="background: <?= e($thema['kreise'][1] ?? '#FDEB81') ?>"></span>
            </div>

            <div class="question-cont">
                <h2><?= e($thema['einstieg']['titel']) ?></h2>
                <div class="question-sec">
                    <?php foreach ($thema['einstieg']['spalten'] as $spalte): ?>
                        <p><?= e($spalte) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </header>

        <?php $bloecke = $thema['bloecke'] ?? []; require __DIR__ . '/bild-text.php'; ?>

        <?php
        $gruen       = $thema['gruen'];
        $gruenButton = 'Kennenlerngespräch buchen';
        require __DIR__ . '/gruen-text.php';
        ?>

        <section class="light-sec">
            <?php require __DIR__ . '/schritte.php'; ?>

            <?php
            $begleitungKarten = $thema['begleitung'] ?? null;
            require __DIR__ . '/begleitung-teaser.php';
            ?>
        </section>

        <?php
        $termineTitel = $thema['angebote']['titel'] ?? 'Was passt gerade zu dir?';
        $termineText  = $thema['angebote']['text'] ?? '';
        require __DIR__ . '/termine.php';
        ?>

        <?php require __DIR__ . '/antworten-teaser.php'; ?>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>
    <?php require_once __DIR__ . '/script.php'; ?>
    <?php require_once __DIR__ . '/googleanalytics.php'; ?>
</body>
</html>
