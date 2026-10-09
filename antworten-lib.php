<?php
declare(strict_types=1);

/*
 * Antworten (Blog): liest alle Beiträge aus data/antworten/*.json
 * Ein neuer Beitrag = eine JSON-Datei + ein Bild. Übersicht, Detailseite,
 * Startseite, Sitemap, Schema und llms.txt aktualisieren sich daraus selbst.
 */

function antworten_alle(): array
{
    static $cache = null;
    if ($cache !== null) return $cache;

    $posts = [];
    foreach (glob(__DIR__ . '/data/antworten/*.json') ?: [] as $file) {
        $data = json_decode((string)file_get_contents($file), true);
        if (!is_array($data) || empty($data['slug'])) {
            error_log('Antworten: ungültiges JSON in ' . basename($file));
            continue;
        }
        $data['datei'] = basename($file);
        $posts[$data['slug']] = $data;
    }
    // neuste zuerst
    uasort($posts, fn($a, $b) => strcmp($b['datum'] ?? '', $a['datum'] ?? '') ?: strcmp($b['datei'], $a['datei']));
    return $cache = $posts;
}

function antwort_laden(string $slug): ?array
{
    return antworten_alle()[$slug] ?? null;
}

function antwort_url(string $slug): string
{
    return '/antworten/' . $slug;
}

/** Bildadresse mit Versionsnummer, damit ausgetauschte Bilder sofort erscheinen */
function antwort_bild(string $pfad): string
{
    $pfad = '/' . ltrim($pfad, '/');
    $datei = __DIR__ . $pfad;
    return is_file($datei) ? $pfad . '?v=' . filemtime($datei) : $pfad;
}

/** Bildausschnitt für hochformatige Karten: links, rechts, oben, unten oder leer für mittig */
function antwort_bildausschnitt(array $beitrag): string
{
    $karte = ['links' => 'left center', 'rechts' => 'right center', 'oben' => 'center top', 'unten' => 'center bottom'];
    $wahl = $beitrag['bild_ausschnitt'] ?? '';
    return $karte[$wahl] ?? '';
}

/** Themen eines Beitrags, immer als Liste */
function antwort_tags(array $beitrag): array
{
    if (!empty($beitrag['tags']) && is_array($beitrag['tags'])) return $beitrag['tags'];
    return !empty($beitrag['tag']) ? [$beitrag['tag']] : [];
}

/** Alle vorkommenden Themen (Tags), in der Reihenfolge der Startseite */
function antworten_tags(): array
{
    $reihenfolge = ['Stress & innere Unruhe', 'Schlaf & Erschöpfung', 'Überforderung & Reizbarkeit', 'Essen bei Stress & Gefühlen'];
    $vorhanden = [];
    foreach (antworten_alle() as $p) {
        foreach (antwort_tags($p) as $tag) {
            if (!in_array($tag, $vorhanden, true)) $vorhanden[] = $tag;
        }
    }
    usort($vorhanden, function ($a, $b) use ($reihenfolge) {
        $ia = array_search($a, $reihenfolge, true); $ib = array_search($b, $reihenfolge, true);
        return ($ia === false ? 99 : $ia) <=> ($ib === false ? 99 : $ib);
    });
    return $vorhanden;
}

/** Geschätzte Lesezeit in Minuten, 150 Wörter pro Minute */
function antwort_lesezeit(array $beitrag): int
{
    $text = $beitrag['titel'] ?? '';
    foreach ($beitrag['inhalt'] ?? [] as $block) {
        $text .= ' ' . ($block['titel'] ?? '') . ' ' . ($block['text'] ?? '') . ' ' . implode(' ', $block['absaetze'] ?? []);
    }
    $woerter = str_word_count(strip_tags($text), 0, 'äöüÄÖÜßéèàâ0123456789');
    return max(1, (int)round($woerter / 150));
}

/** Weitere Beiträge für «Vielleicht fragst du dich das auch.» */
function antworten_weitere(string $slug, ?int $max = null): array
{
    $alle = antworten_alle();
    $aktuell = $alle[$slug] ?? null;
    unset($alle[$slug]);
    // zuerst gleiches Thema, dann der Rest
    $eigene = $aktuell ? antwort_tags($aktuell) : [];
    uasort($alle, function ($a, $b) use ($eigene) {
        $ga = (int)(bool)array_intersect(antwort_tags($a), $eigene);
        $gb = (int)(bool)array_intersect(antwort_tags($b), $eigene);
        return $gb <=> $ga ?: strcmp($b['datum'] ?? '', $a['datum'] ?? '');
    });
    return $max === null ? $alle : array_slice($alle, 0, $max, true);
}

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
