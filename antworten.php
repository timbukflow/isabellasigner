<?php
declare(strict_types=1);
require_once __DIR__ . '/antworten-lib.php';

$base     = 'https://isabella-signer.ch';
$url      = $base . '/antworten';
$beitraege = antworten_alle();
$neuster  = $beitraege ? reset($beitraege) : null;
$themen   = antworten_tags();
$gewaehlt = isset($_GET['thema']) ? (string)$_GET['thema'] : '';
if ($gewaehlt !== '' && !in_array($gewaehlt, $themen, true)) $gewaehlt = '';

$titel        = 'Antworten zu Stress, Schlaf & Erschöpfung | Isabella Signer';
$beschreibung = 'Warum bist du ständig müde, gereizt oder erschöpft? Antworten von Isabella Signer zu Stress, Schlaf, Überforderung und Essen bei Stress.';

$jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
$seite = [
  '@context' => 'https://schema.org',
  '@type' => 'CollectionPage',
  '@id' => $url . '#webpage',
  'url' => $url,
  'name' => $titel,
  'description' => $beschreibung,
  'inLanguage' => 'de-CH',
  'isPartOf' => ['@id' => $base . '/#website'],
  'about' => ['@id' => $base . '/#praxis'],
  'mainEntity' => [
    '@type' => 'ItemList',
    'numberOfItems' => count($beitraege),
    'itemListElement' => array_values(array_map(fn($i, $p) => [
      '@type' => 'ListItem',
      'position' => $i + 1,
      'url' => $base . antwort_url($p['slug']),
      'name' => $p['titel'],
    ], array_keys(array_values($beitraege)), array_values($beitraege))),
  ],
];
$breadcrumb = [
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => $base . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Antworten', 'item' => $url],
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

    <meta property="og:title" content="Antworten – Isabella Signer" />
    <meta property="og:description" content="<?= e($beschreibung) ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= e($url) ?>" />
    <meta property="og:image" content="<?= e($base . '/' . ltrim($neuster['bild'] ?? 'img/og-image.jpg', '/')) ?>" />
    <meta property="og:locale" content="de_CH" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Antworten – Isabella Signer" />
    <meta name="twitter:description" content="<?= e($beschreibung) ?>" />

    <link rel="stylesheet" href="/main.css?v=<?= filemtime(__DIR__ . '/main.css') ?>" />
    <link rel="preload" href="/fonts/rubis-light.woff2" as="font" type="font/woff2" crossorigin>

    <script type="application/ld+json">
<?= json_encode($seite, $jsonFlags) ?>
    </script>
    <script type="application/ld+json">
<?= json_encode($breadcrumb, $jsonFlags) ?>
    </script>
    <?php require_once __DIR__ . '/schema.php'; ?>
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>
    <main>
        <header class="antworten-kopf">
            <p class="eyebrow center">Antworten</p>
            <h1 class="center">Vielleicht fragst du dich das auch.</h1>
        </header>

        <?php if ($neuster): ?>
            <a class="antworten-hero" href="<?= e(antwort_url($neuster['slug'])) ?>">
                <img src="<?= e(antwort_bild($neuster['bild'])) ?>" alt="<?= e($neuster['bild_alt'] ?? '') ?>" width="1600" height="900" fetchpriority="high">
                <span class="antworten-hero-titel"><?= e($neuster['titel']) ?></span>
            </a>
        <?php endif; ?>

        <section class="container antworten-liste">
            <nav class="themen-filter" aria-label="Themen">
                <?php foreach ($themen as $thema): ?>
                    <a class="thema-pille<?= $gewaehlt === $thema ? ' aktiv' : '' ?>"
                       href="/antworten<?= $gewaehlt === $thema ? '' : '?thema=' . rawurlencode($thema) ?>"
                       data-thema="<?= e($thema) ?>"<?= $gewaehlt === $thema ? ' aria-current="true"' : '' ?>><?= e($thema) ?></a>
                <?php endforeach; ?>
            </nav>

            <div class="antworten-raster">
                <?php foreach ($beitraege as $p):
                    $tags = antwort_tags($p);
                    $sichtbar = $gewaehlt === '' || in_array($gewaehlt, $tags, true); ?>
                    <a class="antwort-karte" href="<?= e(antwort_url($p['slug'])) ?>"
                       data-themen="<?= e(implode('|', $tags)) ?>"<?= $sichtbar ? '' : ' hidden' ?>>
                        <span class="antwort-karte-bild"><img src="<?= e(antwort_bild($p['bild'])) ?>" alt="<?= e($p['bild_alt'] ?? '') ?>" loading="lazy"></span>
                        <span class="antwort-karte-titel"><?= e($p['titel']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <p class="antworten-leer" hidden>Zu diesem Thema gibt es noch keinen Beitrag.</p>
        </section>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>
    <?php require_once __DIR__ . '/script.php'; ?>
    <?php require_once __DIR__ . '/googleanalytics.php'; ?>
</body>
</html>
