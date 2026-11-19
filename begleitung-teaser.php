<?php
/*
 * «Deine Begleitung richtet sich nach dir.» – drei Angebote.
 * $begleitungKarten  (optional) eigene Texte je Karte, sonst die Standardtexte unten
 * $begleitungButton  (optional) [Beschriftung, Ziel] für den Button am Ende
 * $begleitungTitel / $begleitungText  (optional) eigene Überschrift und Einleitung
 */
$begleitungKarten = $begleitungKarten ?? [
  ['bild' => 'img/isabella-signer-begleitung-bodywork.webp', 'bild_alt' => 'Frau tanzt mit erhobenem Arm im sonnigen Wald', 'titel' => 'Emotional Bodywork & Breathwork', 'text' => 'Dein Körper spricht längst mit dir. Anspannung lösen, körperliche Reaktionen besser einordnen und neue Wege im Umgang mit Stress entwickeln.', 'link' => '/begleitung/emotional-bodywork-breathwork'],
  ['bild' => 'img/isabella-signer-begleitung-coaching.webp', 'bild_alt' => 'Isabella Signer im Gespräch mit einer Klientin am Tisch', 'titel' => 'Mindset & Coaching', 'text' => 'Gedanken und Muster hinterfragen, die dich immer wieder an denselben Punkt bringen und neue Handlungsmöglichkeiten entwickeln.', 'link' => '/begleitung/mindset-coaching'],
  ['bild' => 'img/isabella-signer-begleitung-ayurveda.webp', 'bild_alt' => 'Isabella Signer riecht an frisch gemörserten Kräutern', 'titel' => 'Ayurveda & Ernährung', 'text' => 'Was du isst und wie du isst, beeinflusst, wie du dich fühlst. Die richtigen Lebensmittel und Gewohnheiten können deinen Körper gezielt im Alltag unterstützen.', 'link' => '/begleitung/ayurveda-ernaehrung'],
];

/** Solange eine Methodenseite noch nicht existiert, führt der Button auf /begleitung */
if (!function_exists('begleitung_ziel')) { function begleitung_ziel(string $ziel): string
{
    $slug = trim($ziel, '/');
    if (preg_match('#^begleitung/([a-z0-9-]+)$#', $slug, $m) && is_file(__DIR__ . '/data/methoden/' . $m[1] . '.json')) return $ziel;
    if (is_file(__DIR__ . '/' . $slug . '.php') || is_file(__DIR__ . '/data/themen/' . $slug . '.json')) return $ziel;
    return '/begleitung';
} }
$begleitungButton = $begleitungButton ?? ['Begleitung entdecken', '/begleitung'];
$begleitungTitel  = $begleitungTitel ?? 'Deine Begleitung richtet sich nach dir.';
$begleitungText   = $begleitungText ?? 'Je nachdem, was du brauchst, verbinde ich unterschiedliche Ansätze miteinander.';
if (!function_exists('e')) { function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); } }
?>
        <div class="container gap-middle begleitung-kopf">
            <h2><?= nl2br(e($begleitungTitel), false) ?></h2>
            <p class="lead"><?= e($begleitungText) ?></p>
        </div>

        <div class="teaser-angebot light<?= count($begleitungKarten) === 2 ? ' zwei' : '' ?>">
            <?php foreach ($begleitungKarten as $karte): ?>
                <?php
                $karteDatei = __DIR__ . '/' . ltrim($karte['bild'], '/');
                $karteMasse = @getimagesize($karteDatei) ?: [1000, 1000];
                ?>
                <div class="angebot">
                    <img src="/<?= e(ltrim($karte['bild'], '/')) ?>" alt="<?= e($karte['bild_alt'] ?? '') ?>" width="<?= (int)$karteMasse[0] ?>" height="<?= (int)$karteMasse[1] ?>" loading="lazy" />
                    <h3 class="center"><?= e($karte['titel']) ?></h3>
                    <p class="center"><?= e($karte['text']) ?></p>
                    <?php if (!empty($karte['link'])): ?>
                        <p class="center angebot-link"><a class="btn-linie" href="<?= e(begleitung_ziel($karte['link'])) ?>">Mehr dazu erfahren</a></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($begleitungButton): ?>
            <div class="btn">
                <a href="<?= e($begleitungButton[1]) ?>"><?= e($begleitungButton[0]) ?></a>
            </div>
        <?php endif; ?>
