<?php
declare(strict_types=1);
session_start();

/* Konfiguration */
$TO_EMAIL   = 'info@isabella-signer.ch';
$TO_NAME    = 'Isabella Signer';
$FROM_EMAIL = 'no-reply@isabella-signer.ch';
$SUBJECT    = 'Neue Kontaktanfrage über die Website';

/* Hilfsfunktionen */
function clean_header(string $v): string { return preg_replace("/[\r\n]+/", ' ', $v); } // Header-Injection-Schutz
function redirect_ok(): void {
  header('Location: /kontakt.php?sent=1'); // Zurück zum Formular, Modal erscheint
  exit;
}

/* Einfache Rate-Limit pro IP (1 Minute / 3 Anfragen) */
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$bucket = sys_get_temp_dir() . '/contact_rate_' . md5($ip);
$now = time(); $win = 60; $max = 3; $hits = 0;
if (is_file($bucket)) {
  $data = json_decode((string)file_get_contents($bucket), true);
  if (is_array($data) && $now - ($data['ts']??0) < $win) $hits = (int)($data['hits']??0);
}
$hits++; file_put_contents($bucket, json_encode(['ts'=>$now,'hits'=>$hits]));
if ($hits > $max) { die('Zu viele Anfragen. Bitte später erneut versuchen.'); }

/* Nur POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); die('Method Not Allowed'); }

/* CSRF prüfen (Synchronizer Token Pattern) */
if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
  http_response_code(400); die('Sicherheitsfehler (CSRF).');
}

/* Honeypot */
if (!empty($_POST['website'])) { redirect_ok(); }

/* Felder */
$name    = trim((string)($_POST['name'] ?? ''));
$address = trim((string)($_POST['address'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$phone   = trim((string)($_POST['phone'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
$reach   = trim((string)($_POST['reach'] ?? ''));

/* Validierung (Längen/Formats) */
$errors = [];
if ($name === '' || mb_strlen($name) > 120)       $errors[] = 'Name fehlt/zu lang.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 120) $errors[] = 'E-Mail ungültig.';
if ($message === '' || mb_strlen($message) > 4000)$errors[] = 'Nachricht fehlt/zu lang.';
if (mb_strlen($address) > 200 || mb_strlen($phone) > 40 || mb_strlen($reach) > 200) $errors[] = 'Ein Feld ist zu lang.';
if ($errors) { http_response_code(400); die(implode(' ', $errors)); }

/* E-Mail aufbauen (Plain-Text, UTF-8) */
$body  = "Neue Kontaktanfrage\n\n";
$body .= "Name:      {$name}\n";
$body .= "Adresse:   {$address}\n";
$body .= "E-Mail:    {$email}\n";
$body .= "Telefon:   {$phone}\n";
$body .= "Erreichbar: {$reach}\n\n";
$body .= "Nachricht:\n{$message}\n";

$from   = clean_header($FROM_EMAIL);
$to     = clean_header($TO_EMAIL);
$reply  = clean_header($email);
$subject= clean_header($SUBJECT);

$headers = [];
$headers[] = "From: {$from}";
$headers[] = "Reply-To: {$reply}";
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-Type: text/plain; charset=UTF-8";
$headers[] = "X-Contact-IP: " . $ip;

$sent = @mail($to, $subject, $body, implode("\r\n", $headers));

if ($sent) { redirect_ok(); }
http_response_code(500); die('Versand fehlgeschlagen.');