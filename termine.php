<?php
/*
 * Petrolfarbener Abschnitt mit den drei Terminkarten.
 * Nutzt denselben Baustein wie /termin und /angebot (termin-angebot.php).
 *   $termineTitel  Überschrift
 *   $termineText   Einleitungstext
 */
$termineTitel = $termineTitel ?? 'Was passt gerade zu dir?';
$termineText  = $termineText  ?? '';
if (!function_exists('e')) { function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); } }
?>
    <section class="ta-cont termine-sec">
        <div class="container">
            <p class="white center termine-label"><?= e($termineTitel) ?></p>
            <?php if ($termineText): ?>
                <h2 class="white center termine-titel"><?= e($termineText) ?></h2>
            <?php endif; ?>
        </div>

        <?php require __DIR__ . '/termin-angebot.php'; ?>
    </section>
