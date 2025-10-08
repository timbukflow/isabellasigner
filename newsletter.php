<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$redirectRaw = isset($_POST['redirect']) ? (string)$_POST['redirect'] : '/';
$redirectParts = parse_url($redirectRaw);
$path = $redirectParts['path'] ?? '/';
$path = $path !== '' ? $path : '/';

if ($path[0] !== '/') {
    $path = '/' . ltrim($path, '/');
}

$query = [];
if (!empty($redirectParts['query'])) {
    parse_str($redirectParts['query'], $query);
}

$fragment = $redirectParts['fragment'] ?? '';

/**
 * Helper to redirect back to the originating page with a status flag.
 *
 * @param string $status
 * @param string $reason
 * @return never
 */
function newsletter_redirect(string $status, string $reason = ''): void
{
    global $path, $query, $fragment;

    $query['newsletter'] = $status;
    if ($reason !== '') {
        $query['reason'] = $reason;
    } else {
        unset($query['reason']);
    }

    $qs = http_build_query($query);
    $target = $path . ($qs !== '' ? '?' . $qs : '');
    if ($fragment !== '') {
        $target .= '#' . $fragment;
    }

    header('Location: ' . $target);
    exit;
}

if (!empty($_POST['website'])) {
    newsletter_redirect('success');
}

$firstname  = isset($_POST['firstname']) ? trim((string)$_POST['firstname']) : '';
$lastname   = isset($_POST['lastname']) ? trim((string)$_POST['lastname']) : '';
$email      = isset($_POST['email']) ? trim((string)$_POST['email']) : '';

$hasError = false;

if ($firstname === '' || mb_strlen($firstname) > 120) {
    $hasError = true;
}

if ($lastname === '' || mb_strlen($lastname) > 120) {
    $hasError = true;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 160) {
    $hasError = true;
}

if ($hasError) {
    newsletter_redirect('error', 'Bitte prüfe deine Eingaben.');
}

$apiKey = getenv('MAILCHIMP_API_KEY') ?: '';
$listId = getenv('MAILCHIMP_LIST_ID') ?: '';

if ($apiKey === '' || $listId === '') {
    error_log('Mailchimp newsletter: fehlende Konfiguration.');
    newsletter_redirect('error', 'Mailchimp ist noch nicht konfiguriert.');
}

$dashPos = strpos($apiKey, '-');
if ($dashPos === false) {
    error_log('Mailchimp newsletter: ungültiger API-Key.');
    newsletter_redirect('error', 'Mailchimp ist noch nicht korrekt eingerichtet.');
}

$serverPrefix = substr($apiKey, $dashPos + 1);
$memberId = md5(strtolower($email));
$endpoint = sprintf('https://%s.api.mailchimp.com/3.0/lists/%s/members/%s', $serverPrefix, $listId, $memberId);

$payload = [
    'email_address' => $email,
    'status_if_new' => 'subscribed',
    'status' => 'subscribed',
    'merge_fields' => [
        'FNAME' => $firstname,
        'LNAME' => $lastname,
    ],
];

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_USERPWD, 'anystring:' . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$responseBody = curl_exec($ch);
$curlError = curl_error($ch);
$responseCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curlError) {
    error_log('Mailchimp newsletter: cURL-Fehler - ' . $curlError);
    newsletter_redirect('error', 'Die Verbindung zu Mailchimp ist fehlgeschlagen.');
}

if ($responseCode >= 200 && $responseCode < 300) {
    newsletter_redirect('success');
}

$detail = '';
if ($responseBody) {
    $decoded = json_decode($responseBody, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $detail = isset($decoded['title']) ? (string)$decoded['title'] : '';
        $message = isset($decoded['detail']) ? (string)$decoded['detail'] : '';

        if ($detail === 'Member Exists') {
            newsletter_redirect('success');
        }

        if ($detail === 'Forgotten Email Not Subscribed') {
            newsletter_redirect('error', 'Bitte bestätige zuerst deine bestehende Anmeldung.');
        }

        if ($message !== '' && $detail === '') {
            $detail = $message;
        }

        if ($message === '' && $detail !== '') {
            $message = $detail;
        }
    }
}

error_log(sprintf(
    'Mailchimp newsletter: Unerwartete Antwort (Code %d): %s',
    $responseCode,
    $detail !== '' ? $detail : 'Keine Details'
));

newsletter_redirect('error', 'Die Anmeldung war nicht erfolgreich. Bitte versuche es später erneut.');
