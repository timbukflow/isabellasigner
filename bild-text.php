<?php
/*
 * Bild/Text-Blöcke (50/50) – gleich auf Themen- und Methodenseiten.
 *   $bloecke  Liste mit bild, bild_alt, titel, absaetze[], bild_rechts (optional),
 *             link + link_text (optional) für den Button mit Kontur
 */
$bloecke = $bloecke ?? [];
if (!function_exists('e')) { function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); } }
?>
        <?php $letzterBlock = array_key_last($bloecke); ?>
        <?php foreach ($bloecke as $i => $block): ?>
            <?php
            $bildPfad  = ltrim($block['bild'], '/');
            $bildDatei = __DIR__ . '/' . $bildPfad;
            $bildMasse = @getimagesize($bildDatei) ?: [1320, 1980];
            $bildStand = @filemtime($bildDatei) ?: 0;
            ?>
            <section class="cont-50-50 bild-text<?= !empty($block['bild_rechts']) ? ' revers' : '' ?><?= $i === $letzterBlock ? ' letzter' : '' ?>">
                <div class="photo parallax fix" data-speed="0.04">
                    <img src="/<?= e($bildPfad) ?><?= $bildStand ? '?v=' . $bildStand : '' ?>" alt="<?= e($block['bild_alt'] ?? '') ?>" width="<?= (int)$bildMasse[0] ?>" height="<?= (int)$bildMasse[1] ?>" loading="lazy" />
                </div>
                <div class="copy">
                    <?php if (!empty($block['titel'])): ?>
                        <h2 class="sub"><?= e($block['titel']) ?></h2>
                    <?php endif; ?>
                    <?php foreach ($block['absaetze'] as $absatz): ?>
                        <p><?= e($absatz) ?></p>
                    <?php endforeach; ?>
                    <?php if (!empty($block['link'])): ?>
                        <p class="bild-text-link"><a class="btn-linie" href="<?= e($block['link']) ?>"><?= e($block['link_text'] ?? 'Mehr erfahren') ?></a></p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endforeach; ?>
