<?php
/*
 * «Vielleicht fragst du dich das auch.» – Scroller mit Antworten.
 * Vor dem Einbinden kann $teaserBeitraege gesetzt werden (z. B. ohne den aktuellen Beitrag).
 */
require_once __DIR__ . '/antworten-lib.php';
$teaserBeitraege = $teaserBeitraege ?? antworten_alle();
if ($teaserBeitraege):
?>
            <section class="container weitere-antworten">
                <div class="weitere-kopf">
                    <h2>Vielleicht fragst du dich das auch.</h2>
                    <a class="weitere-link" href="/antworten">Alle Antworten entdecken →</a>
                </div>
                <div class="antworten-scroller">
                    <button type="button" class="scroller-pfeil links" data-scroll="-1" aria-label="Zurück"><img src="/img/pfeil-links-hell.svg" alt="" width="70" height="70"></button>
                    <button type="button" class="scroller-pfeil rechts" data-scroll="1" aria-label="Weiter"><img src="/img/pfeil-rechts-hell.svg" alt="" width="70" height="70"></button>
                    <div class="antworten-karten scroller">
                    <?php foreach ($teaserBeitraege as $w): ?>
                        <a class="antwort-karte" href="<?= e(antwort_url($w['slug'])) ?>">
                            <span class="antwort-karte-bild"><img src="<?= e(antwort_bild($w['bild'])) ?>" alt="<?= e($w['bild_alt'] ?? '') ?>" loading="lazy"></span>
                            <span class="antwort-karte-titel"><?= e($w['titel']) ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
<?php endif; ?>
