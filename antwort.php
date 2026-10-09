<?php
declare(strict_types=1);
require_once __DIR__ . '/antworten-lib.php';

$slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
$beitrag = preg_match('/^[a-z0-9-]{1,120}$/', $slug) ? antwort_laden($slug) : null;

if (!$beitrag) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$base         = 'https://isabella-signer.ch';
$url          = $base . antwort_url($beitrag['slug']);
$titel        = $beitrag['seo_titel'] ?? $beitrag['titel'];
$beschreibung = $beitrag['beschreibung'] ?? '';
$bildUrl      = $base . '/' . ltrim($beitrag['bild'] ?? 'img/og-image.jpg', '/');
$weiter       = !empty($beitrag['weiterlesen']) ? antwort_laden($beitrag['weiterlesen']) : null;
$weitere      = antworten_weitere($beitrag['slug']);
$lesezeit     = antwort_lesezeit($beitrag);
$datum        = !empty($beitrag['datum']) ? date('d.m.Y', (int)strtotime($beitrag['datum'])) : '';

$artikelSchema = [
  '@context' => 'https://schema.org',
  '@type' => 'Article',
  '@id' => $url . '#artikel',
  'headline' => $beitrag['titel'],
  'description' => $beschreibung,
  'image' => $bildUrl,
  'inLanguage' => 'de-CH',
  'datePublished' => $beitrag['datum'] ?? null,
  'dateModified' => $beitrag['datum'] ?? null,
  'author' => ['@id' => $base . '/#isabella'],
  'publisher' => ['@id' => $base . '/#praxis'],
  'mainEntityOfPage' => $url,
  'articleSection' => antwort_tags($beitrag) ?: null,
  'keywords' => $beitrag['keywords'] ?? null,
  'isPartOf' => ['@id' => $base . '/antworten#webpage'],
];
$breadcrumb = [
  '@context' => 'https://schema.org',
  '@type' => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => $base . '/'],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Antworten', 'item' => $base . '/antworten'],
    ['@type' => 'ListItem', 'position' => 3, 'name' => $beitrag['titel'], 'item' => $url],
  ],
];
$jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;

$teilen = [
  'whatsapp' => ['WhatsApp', 'https://wa.me/?text=' . rawurlencode($beitrag['titel'] . ' ' . $url)],
  'facebook' => ['Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($url)],
  'linkedin' => ['LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode($url)],
  'mail'     => ['E-Mail',   'mailto:?subject=' . rawurlencode($beitrag['titel']) . '&body=' . rawurlencode($url)],
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

    <meta property="og:title" content="<?= e($beitrag['titel']) ?>" />
    <meta property="og:description" content="<?= e($beschreibung) ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?= e($url) ?>" />
    <meta property="og:image" content="<?= e($bildUrl) ?>" />
    <meta property="og:locale" content="de_CH" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= e($beitrag['titel']) ?>" />
    <meta name="twitter:description" content="<?= e($beschreibung) ?>" />
    <meta name="twitter:image" content="<?= e($bildUrl) ?>" />

    <link rel="stylesheet" href="/main.css?v=<?= filemtime(__DIR__ . '/main.css') ?>" />
    <link rel="preload" href="/fonts/rubis-light.woff2" as="font" type="font/woff2" crossorigin>

    <script type="application/ld+json">
<?= json_encode(array_filter($artikelSchema, fn($v) => $v !== null), $jsonFlags) ?>
    </script>
    <script type="application/ld+json">
<?= json_encode($breadcrumb, $jsonFlags) ?>
    </script>
    <?php require_once __DIR__ . '/schema.php'; ?>
</head>

<body>
    <?php require_once __DIR__ . '/nav.php'; ?>
    <main>
        <article class="artikel">

            <div class="artikel-kopf">
                <p class="artikel-meta">
                    Lesezeit: ca. <?= $lesezeit ?> Min.<?php if ($datum): ?> | Publiziert: <?= e($datum) ?><?php endif; ?> | Autorin: <a href="/ueber-mich">Isabella Signer</a>
                </p>

                <h1><?= e($beitrag['titel']) ?></h1>
            </div>

            <?php if (!empty($beitrag['bild'])): ?>
                <img class="artikel-bild" src="<?= e(antwort_bild($beitrag['bild'])) ?>" alt="<?= e($beitrag['bild_alt'] ?? '') ?>" width="1600" height="900" fetchpriority="high">
            <?php endif; ?>

            <div class="artikel-text">
                <?php
                $imEinstieg = true; // alles bis zum ersten Zwischentitel ist Einstiegstext
                foreach ($beitrag['inhalt'] ?? [] as $i => $block):
                    $typ = $block['typ'] ?? 'text';
                    if (in_array($typ, ['h2', 'box', 'box-gruen'], true)) $imEinstieg = false;
                    switch ($typ):
                        case 'h2': ?>
                            <h2><?= e($block['text']) ?></h2>
                        <?php break;
                        case 'lead': ?>
                            <p class="artikel-lead"><?= e($block['text'] ?? '') ?></p>
                        <?php break;
                        case 'box':
                        case 'box-gruen': ?>
                            <aside class="artikel-box">
                                <?php if (!empty($block['titel'])): ?><h2><?= e($block['titel']) ?></h2><?php endif; ?>
                                <?php foreach ($block['absaetze'] ?? [] as $absatz): ?>
                                    <p><?= e($absatz) ?></p>
                                <?php endforeach; ?>
                            </aside>
                        <?php break;
                        case 'bild': ?>
                            <img class="artikel-inlinebild" src="<?= e(antwort_bild($block['bild'])) ?>" alt="<?= e($block['alt'] ?? '') ?>" loading="lazy">
                        <?php break;
                        default: ?>
                            <p<?= $imEinstieg ? ' class="artikel-lead"' : '' ?>><?= e($block['text'] ?? '') ?></p>
                        <?php endswitch;
                endforeach; ?>

                <?php $weiterTitel = $weiter['titel'] ?? ($beitrag['weiterlesen_titel'] ?? ''); ?>
                <?php if ($weiterTitel): ?>
                    <p class="artikel-weiterlesen">
                        Weiterlesen: <?php if ($weiter): ?><a href="<?= e(antwort_url($weiter['slug'])) ?>"><?= e($weiterTitel) ?></a><?php else: ?><?= e($weiterTitel) ?><?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="artikel-teilen">
                <p class="artikel-teilen-titel">Diesen Artikel teilen</p>
                <ul class="artikel-teilen-liste">
                    <?php foreach ($teilen as $key => [$label, $href]): ?>
                        <li>
                            <a href="<?= e($href) ?>"<?= $key === 'mail' ? '' : ' target="_blank" rel="noopener"' ?>>
                                <img src="/img/share-<?= e($key) ?>.svg" alt="" width="26" height="26">
                                <span><?= e($label) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <button type="button" class="artikel-teilen-link" data-link="<?= e($url) ?>">
                            <img src="/img/share-link.svg" alt="" width="26" height="26">
                            <span>Link</span>
                        </button>
                    </li>
                </ul>
            </div>
        </article>

        <?php $teaserBeitraege = $weitere; require __DIR__ . '/antworten-teaser.php'; ?>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>
    <?php require_once __DIR__ . '/script.php'; ?>
    <?php require_once __DIR__ . '/googleanalytics.php'; ?>
</body>
</html>
