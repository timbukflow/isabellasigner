<?php
/*
 * Grüner Bereich mit Titel, drei Spalten und gelbem Button – gleich auf Themen- und Methodenseiten.
 *   $gruen        ['titel' => …, 'spalten' => […]]
 *   $gruenButton  Beschriftung des Buttons (öffnet das Kennenlerngespräch in Calendly)
 */
$gruen       = $gruen ?? ['titel' => '', 'spalten' => []];
$gruenButton = $gruenButton ?? 'Kennenlerngespräch buchen';
if (!function_exists('e')) { function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); } }
?>
        <section class="ta-cont thema-gruen">
            <div class="container">
                <h2 class="white"><?= e($gruen['titel']) ?></h2>
                <div class="question-sec">
                    <?php foreach ($gruen['spalten'] as $spalte): ?>
                        <p class="white"><?= e($spalte) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="btn">
                <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
                <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async></script>
                <a class="btn-gelb" href="/termin" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/isabella-signer/30min?text_color=27614b&primary_color=a790e5'});return false;"><?= e($gruenButton) ?></a>
            </div>
        </section>
