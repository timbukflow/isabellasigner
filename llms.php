<?php
declare(strict_types=1);
require_once __DIR__ . '/antworten-lib.php';

/*
 * llms.txt für KI-Suche, erzeugt aus den festen Angaben und allen Antworten.
 * Aufruf über /llms.txt (Weiterleitung in der .htaccess).
 */
header('Content-Type: text/plain; charset=utf-8');

$base = 'https://isabella-signer.ch';
$beitraege = antworten_alle();
?>
# Isabella Signer

> Isabella Signer begleitet Menschen, die müde sind und trotzdem nicht abschalten können – bei Stress, Erschöpfung, innerer Unruhe, Schlafproblemen, Reizbarkeit und emotionalem Essen. Sie verbindet Emotional Bodywork, Breathwork, Ayurveda & Ernährung sowie Mindset-Coaching. Praxis in Steinach (SG) am Bodensee, nahe St. Gallen, Rorschach und Arbon; Coaching, Breathwork und Ayurveda-Begleitung auch online.

Die Begleitung beginnt nicht mit einer Methode, sondern bei der Person und ihrer aktuellen Lebenssituation. Sie folgt vier Schritten:

- **Wahrnehmen:** erkennen, was in Körper und Gedanken abläuft und was innerlich in Alarmbereitschaft hält.
- **Regulieren:** den Körper gezielt unterstützen, um Druck abzubauen und innere Stabilität zu gewinnen.
- **Verstehen:** Zusammenhänge, Erfahrungen und Muster aufdecken, die Denken, Fühlen und Handeln prägen.
- **Verankern:** neue Entscheidungen und Verhaltensweisen Schritt für Schritt in den Alltag integrieren.

Isabella Signer ist Ayurveda Lifestyle- & Ernährungscoach, Expertin für Frauengesundheit, Holistic Breathwork Coach und Spiritual Lifecoach, ehemalige Führungskraft und Mutter von drei erwachsenen Kindern. Ihre Arbeit ersetzt keine medizinische oder psychotherapeutische Behandlung.

## Angebot und Preise

- [Kennenlerngespräch](<?= $base ?>/termin): 20 Minuten, kostenlos und unverbindlich.
- [Einzel-Session](<?= $base ?>/termin): CHF 140.– pro Stunde; Breathwork, Emotional Bodywork oder Coaching je nach Thema.
- [Monatsbegleitung](<?= $base ?>/termin): CHF 880.– pro Monat; 2-stündiges Anamnesegespräch, vier Beratungsstunden pro Monat, WhatsApp-Begleitung, persönliches Workbook, beliebig verlängerbar.
- Absagen bis 24 Stunden vorher sind kostenlos.

## Antworten auf häufige Fragen

Beiträge von Isabella Signer, jeweils mit persönlicher Erfahrung und Einordnung. Übersicht: <?= $base ?>/antworten
<?php foreach ($beitraege as $b): ?>

### <?= $b['titel'] ?>

<?= $b['beschreibung'] ?>

- URL: <?= $base . antwort_url($b['slug']) ?>

- Themen: <?= implode(', ', antwort_tags($b)) ?>

- Publiziert: <?= !empty($b['datum']) ? date('d.m.Y', (int)strtotime($b['datum'])) : '-' ?>

<?php endforeach; ?>

## Seiten

- [Startseite](<?= $base ?>/): Überblick, Themen und Arbeitsweise
- [Angebot](<?= $base ?>/angebot): Emotional Bodywork, Breathwork, Ayurveda und Coaching im Detail
- [Antworten](<?= $base ?>/antworten): Beiträge zu Stress, Schlaf, Überforderung und Essen bei Stress
- [Über mich](<?= $base ?>/ueber-mich): Werdegang und Ausbildungen von Isabella Signer
- [Termin buchen](<?= $base ?>/termin): Formate, Preise und häufige Fragen
- [Kontakt](<?= $base ?>/kontakt): Kontaktformular

## Kontakt

- Adresse: Isabella Signer, Kornfeldstrasse 17b, 9323 Steinach, Schweiz
- E-Mail: info@isabella-signer.ch
- Telefon: +41 78 758 19 12
- Instagram: https://www.instagram.com/isabella.signer/
