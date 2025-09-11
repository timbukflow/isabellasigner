<!DOCTYPE html>
<html lang="de">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place#">
    <meta charset="UTF-8" />
    <title>Isabella Signer | Kontakt </title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="..." />
    <meta name="author" content="Isabella Signer" />
    <link rel="canonical" href="https://isabella-signer.ch/kontakt" />
    <meta name="robots" content="index, follow" />
    <meta http-equiv="cache-control" content="public, max-age=3600" />
    <meta http-equiv="pragma" content="cache" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Isabella Signer" />
    <link rel="manifest" href="/site.webmanifest" />

    <!-- Open Graph -->
    <meta property="og:title" content="Isabella Signer | Kontakt" />
    <meta property="og:description" content="..." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://isabella-signer.ch/kontakt" />
    <meta property="og:image" content="https://isabella-signer.ch/img/og-image.jpg" />
    <meta property="og:locale" content="de_CH" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Isabella Signer | Kontakt" />
    <meta name="twitter:description" content="..." />
    <meta name="twitter:image" content="https://isabella-signer.ch/img/og-image.jpg" />

    <meta name="format-detection" content="telephone=yes" />
    <meta property="business:contact_data:street_address" content="Kornfeldstrasse 17b" />
    <meta property="business:contact_data:region" content="Steinach" />
    <meta property="business:contact_data:postal_code" content="9323" />
    <meta property="business:contact_data:country_name" content="Switzerland" />

    <!-- Styles -->
    <link rel="stylesheet" href="main.css" />
    

    <!-- <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Kathrina Looser",
      "url": "https://kathrinalooser.ch",
      "description": "Kathrina Looser verbindet Fotografie und Siebdruck – A thought takes form, a feeling becomes image.",
      "inLanguage": "de",
      "author": {
        "@type": "Person",
        "name": "Kathrina Looser",
        "email": "mailto:hallo@kathrinalooser.ch",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Herrenberg 37",
          "addressLocality": "Rapperswil",
          "postalCode": "8640",
          "addressCountry": "CH"
        }
      },
      "publisher": {
        "@type": "Person",
        "name": "Kathrina Looser"
      }
    }
    </script> -->

</head>

<body>
    <?php require_once 'nav.php'; ?>

    <header>
        <h3>Kontakt</h3>
        <h2 class="sub">Hast du Fragen oder möchtest du kontaktiert werden? Bitte schreibe mir, ich helfe dir gerne.</h2>
    </header>

    <?php
    session_start();
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    $sent = isset($_GET['sent']) && $_GET['sent'] === '1';
    ?>
    <section class="kontakt">

    <?php if ($sent): ?>
        <div class="notice success"><p>E-Mail wurde erfolgreich gesendet. Danke!</p></div>
    <?php endif; ?>

    <form action="/form.php" method="post">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

        <!-- Honeypot (unsichtbar) -->
        <div class="hp" aria-hidden="true">
        <label>Bitte leer lassen</label>
        <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <label> Name* <input type="text" name="name" required maxlength="120"></label>
        <label> Vorname* <input type="text" name="firstname" required maxlength="120"></label>
        <label> Mailadresse* <input type="email" name="email" required maxlength="120" autocomplete="email"></label>
        <label> Telefonnummer* <input type="tel" name="phone" maxlength="40" autocomplete="tel"></label>

        <label> Was möchtest du mir mitteilen?*
        <textarea name="message" rows="6" required maxlength="4000"></textarea>
        </label>

        <label> Wann bist du zeitlich erreichbar?*
        <input type="text" name="reach_time" maxlength="200" placeholder="z. B. Mo–Fr 9–12 Uhr">
        </label>

        <button type="submit" class="btn">Senden</button>
    </form>
    </section>


    <?php require_once 'footer.php'; ?>
    <?php require_once 'script.php'; ?>
    <?php require_once 'googleanalytics.php'; ?>
</body>
</html>
