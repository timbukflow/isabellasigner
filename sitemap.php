<?php
declare(strict_types=1);
require_once __DIR__ . '/antworten-lib.php';

/*
 * Sitemap, erzeugt aus den festen Seiten und allen Antworten.
 * Aufruf über /sitemap.xml (Weiterleitung in der .htaccess).
 */
header('Content-Type: application/xml; charset=utf-8');

$base = 'https://isabella-signer.ch';

/** Datum der letzten Änderung einer Datei im Projekt */
$stand = function (string $datei): string {
    $pfad = __DIR__ . '/' . ltrim($datei, '/');
    return date('Y-m-d', is_file($pfad) ? (int)filemtime($pfad) : time());
};

$seiten = [
    ['/',             '1.00', 'monthly', $stand('index.php')],
    ['/angebot',      '0.80', 'monthly', $stand('angebot.php')],
    ['/antworten',    '0.80', 'weekly',  $stand('antworten.php')],
    ['/termin',       '0.80', 'monthly', $stand('termin.php')],
    ['/kontakt',      '0.80', 'monthly', $stand('kontakt.php')],
    ['/ueber-mich',   '0.64', 'monthly', $stand('ueber-mich.php')],
    ['/impressum',    '0.30', 'yearly',  $stand('impressum.php')],
    ['/datenschutz',  '0.30', 'yearly',  $stand('datenschutz.php')],
];

foreach (antworten_alle() as $beitrag) {
    $datum = !empty($beitrag['datum']) ? date('Y-m-d', (int)strtotime($beitrag['datum'])) : $stand('data/antworten/' . $beitrag['datei']);
    $seiten[] = [antwort_url($beitrag['slug']), '0.70', 'monthly', $datum];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($seiten as [$pfad, $prio, $frequenz, $datum]): ?>
  <url>
    <loc><?= e($base . $pfad) ?></loc>
    <lastmod><?= e($datum) ?></lastmod>
    <changefreq><?= e($frequenz) ?></changefreq>
    <priority><?= e($prio) ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
