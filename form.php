<?php
declare(strict_types=1);
session_start();

/* Empfänger & Absender */
$TO_EMAIL   = 'ivoschwizer@gmail.com';
$FROM_EMAIL = 'info@isabella-signer.ch'; 
$SUBJECT    = 'Neue Kontaktanfrage über die Website';

/* kleine Helfer */
function clean_header(string $v): string { return preg_replace("/[\r\n]+/", ' ', $v); } // CRLF/Header-Injection
function fail(string $msg){ http_response_code(400); die($msg); }
function ok(){ header('Location: /kontakt.php?sent=1'); exit; }

/* Nur POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); die('Method Not Allowed'); }

/* CSRF */
if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
  fail('Sicherheitsfehler (CSRF).');
}

/* Honeypot */
if (!empty($_POST['website'])) { ok(); }

/* Felder holen */
$name      = trim((string)($_POST['name'] ?? ''));
$firstname = trim((string)($_POST['firstname'] ?? ''));
$email     = trim((string)($_POST['email'] ?? ''));
$phone     = trim((string)($_POST['phone'] ?? ''));
$message   = trim((string)($_POST['message'] ?? ''));
$reachTime = trim((string)($_POST['reach_time'] ?? ''));
$contact   = strtolower(trim((string)($_POST['contact_method'] ?? '')));

/* kurze Validierung */
$errors = [];
if ($name==='' || mb_strlen($name)>120)           $errors[]='Name fehlt/zu lang.';
if ($firstname==='' || mb_strlen($firstname)>120)  $errors[]='Vorname fehlt/zu lang.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email)>120) $errors[]='E-Mail ungültig.';
if ($message==='' || mb_strlen($message)>4000)     $errors[]='Nachricht fehlt/zu lang.';
if (!in_array($contact, ['email','telefonisch'], true)) $errors[]='Kontaktweg fehlt/ungültig.';
if ($contact === 'telefonisch' && $reachTime==='') $errors[]='Bitte Verfügbarkeit angeben.';
if (mb_strlen($phone)>40 || mb_strlen($reachTime)>200) $errors[]='Ein Feld ist zu lang.';
if ($errors) { fail(implode(' ', $errors)); }

/* Mailinhalt */
$body  = "Neue Kontaktanfrage\n\n";
$body .= "Name:        {$name}\n";
$body .= "Vorname:     {$firstname}\n";
$body .= "E-Mail:      {$email}\n";
$body .= "Telefon:     {$phone}\n";
$body .= "Kontaktweg:  ".($contact==='telefonisch' ? 'Telefonisch' : 'E-Mail')."\n";
$body .= ($contact==='telefonisch' && $reachTime !== '' ? "Erreichbar:  {$reachTime}\n" : '');
$body .= "Nachricht:\n{$message}\n";

/* Header sicher setzen */
$from    = clean_header($FROM_EMAIL);
$replyTo = clean_header($email);
$headers = [];
$headers[] = "From: {$from}";
$headers[] = "Reply-To: {$replyTo}";
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-Type: text/plain; charset=UTF-8";

/* Versand */
$sent = @mail($TO_EMAIL, clean_header($SUBJECT), $body, implode("\r\n", $headers));

if ($sent) { ok(); }
http_response_code(500); die('Versand fehlgeschlagen.');
