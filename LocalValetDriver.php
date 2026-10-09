<?php

/*
 * Nur für die lokale Entwicklung mit Herd/Valet.
 * Bildet die Regeln der .htaccess nach, die nginx lokal nicht liest:
 *   /antworten            -> antworten.php
 *   /antworten/<slug>     -> antwort.php?slug=<slug>
 *   /<seite>              -> <seite>.php
 * Auf dem Server (Apache) wird diese Datei nie ausgeführt.
 */
use Valet\Drivers\BasicValetDriver;

class LocalValetDriver extends BasicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return true;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        $pfad = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (preg_match('#^/antworten/([a-z0-9-]+)/?$#', $pfad, $treffer)) {
            $_GET['slug'] = $treffer[1];
            $_SERVER['SCRIPT_NAME'] = '/antwort.php';
            return $sitePath . '/antwort.php';
        }

        if ($pfad === '/sitemap.xml') return $sitePath . '/sitemap.php';
        if ($pfad === '/llms.txt') return $sitePath . '/llms.php';

        if (preg_match('#^/antworten/?$#', $pfad)) {
            return $sitePath . '/antworten.php';
        }

        // Direkter Aufruf einer PHP-Datei, z. B. /antwort.php?slug=…
        if (substr($pfad, -4) === '.php' && is_file($sitePath . $pfad)) {
            $_SERVER['SCRIPT_NAME'] = $pfad;
            return $sitePath . $pfad;
        }

        // Schöne URL -> gleichnamige PHP-Datei
        if (preg_match('#^/([A-Za-z0-9_-]+)/?$#', $pfad, $treffer) && is_file($sitePath . '/' . $treffer[1] . '.php')) {
            $_SERVER['SCRIPT_NAME'] = '/' . $treffer[1] . '.php';
            return $sitePath . '/' . $treffer[1] . '.php';
        }

        if ($pfad === '/' && is_file($sitePath . '/index.php')) {
            return $sitePath . '/index.php';
        }

        // Alles andere: 404-Seite wie auf dem Server
        if (is_file($sitePath . '/404.php')) {
            http_response_code(404);
            return $sitePath . '/404.php';
        }

        return parent::frontControllerPath($sitePath, $siteName, $uri);
    }
}
