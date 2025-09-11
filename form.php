<?php
declare(strict_types=1);
session_start();

/* Empfänger & Absender */
$TO_EMAIL   = 'ivoschwizer@gmail.com';            // wohin gesendet wird
$TO_NAME    = 'Ivo Schwizer';
$FROM_EMAIL = 'info@isabella-signer.ch';          // Domain-Absender (SPF/DKIM!)
$SUBJECT    = 'Neue Kontaktanfrage über die Website';

/* Helfer */
function clean_header(string $v): string { return preg_replace("/[\r\n]+/", ' ', $v); } // Header-Injection
function bad_request(string $m){ http_response_code(400); die($m); }
function redirect_ok(){ header('Location: /kontakt.php?sent=1'); exit; }

/* simples Rate-Limit: 3 Submits / 60s pro IP */
$ip=$_SERVER['REMOTE_ADDR']??'0.0.0.0';
$bucket=sys_get_temp_dir().'/contact_rate_'.md5($ip);
$now=time();$win=60;$max=3;$hits=0;
if(is_file($bucket)){ $d=json_decode((string)file_get_contents($bucket),true); if(is_array($d) && $now-($d['ts']??0)<$win){ $hits=(int)($d['hits']??0);} }
$hits++; file_put_contents($bucket,json_encode(['ts'=>$now,'hits'=>$hits]));
if($hits>$max){ bad_request('Zu viele Anfragen. Bitte später erneut versuchen.'); }

/* Nur POST */
if($_SERVER['REQUEST_METHOD']!=='POST'){ http_response_code(405); die('Method Not Allowed'); }

/* CSRF */
if(empty($_POST['csrf'])||empty($_SESSION['csrf'])||!hash_equals($_SESSION['csrf'],(string)$_POST['csrf'])){
  bad_request('Sicherheitsfehler (CSRF).');
}

/* Honeypot */
if(!empty($_POST['website'])){ redirect_ok(); }

/* Felder */
$name      = trim((string)($_POST['name'] ?? ''));
$firstname = trim((string)($_POST['firstname'] ?? ''));
$address   = trim((string)($_POST['address'] ?? ''));
$email     = trim((string)($_POST['email'] ?? ''));
$phone     = trim((string)($_POST['phone'] ?? ''));
$message   = trim((string)($_POST['message'] ?? ''));
$reachTime = trim((string)($_POST['reach_time'] ?? ''));
$reachWay  = in_array($_POST['reach_way'] ?? 'telefon', ['telefon','mail'], true) ? $_POST['reach_way'] : 'telefon';

/* Validierung */
$errors=[];
if($name===''||mb_strlen($name)>120)           $errors[]='Name fehlt/zu lang.';
if($firstname===''||mb_strlen($firstname)>120) $errors[]='Vorname fehlt/zu lang.';
if(!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email)>120) $errors[]='E-Mail ungültig.';
if($message===''||mb_strlen($message)>4000)    $errors[]='Nachricht fehlt/zu lang.';
if(mb_strlen($address)>200||mb_strlen($phone)>40||mb_strlen($reachTime)>200) $errors[]='Ein Feld ist zu lang.';
if($errors){ bad_request(implode(' ',$errors)); }

/* E-Mail */
$body  = "Neue Kontaktanfrage\n\n";
$body .= "Name:        {$name}\n";
$body .= "Vorname:     {$firstname}\n";
$body .= "Adresse:     {$address}\n";
$body .= "E-Mail:      {$email}\n";
$body .= "Telefon:     {$phone}\n";
$body .= "Erreichbar:  {$reachTime}\n";
$body .= "Kontaktweg:  {$reachWay}\n\n";
$body .= "Nachricht:\n{$message}\n";

$from   = clean_header($FROM_EMAIL);
$to     = clean_header($TO_EMAIL);
$reply  = clean_header($email);
$subject= clean_header($SUBJECT);

$headers=[];
$headers[]="From: {$from}";
$headers[]="Reply-To: {$reply}";
$headers[]="MIME-Version: 1.0";
$headers[]="Content-Type: text/plain; charset=UTF-8";
$headers[]="X-Contact-IP: ".($ip);

/* Versand (einfach) */
$sent = @mail($to, $subject, $body, implode("\r\n", $headers));

if($sent){ redirect_ok(); }
http_response_code(500); die('Versand fehlgeschlagen.');