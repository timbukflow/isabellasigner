<?php
session_start();
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
$sent = isset($_GET['sent']) && $_GET['sent'] === '1';
?>
<!DOCTYPE html>
<html lang="de">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place#">
    <meta charset="UTF-8" />
    <title>Kontakt – Isabella Signer | Ayurveda, Breathwork &amp; Coaching in Steinach (SG)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Kontaktiere Isabella Signer in Steinach (SG) – Ayurveda, Breathwork &amp; Coaching. Schreibe mir für Fragen oder eine Anfrage, ich melde mich zeitnah." />
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
    <meta property="og:description" content="Kontakt &amp; Anfrage: Isabella Signer – Ayurveda, Breathwork &amp; Coaching in Steinach (SG). Ich freue mich auf deine Nachricht." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://isabella-signer.ch/kontakt" />
    <meta property="og:image" content="https://isabella-signer.ch/img/og-image.jpg" />
    <meta property="og:locale" content="de_CH" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Isabella Signer | Kontakt" />
    <meta name="twitter:description" content="Schreibe Isabella Signer – Ayurveda, Breathwork &amp; Coaching in Steinach (SG). Persönlich, einfühlsam, zuverlässig." />
    <meta name="twitter:image" content="https://isabella-signer.ch/img/og-image.jpg" />

    <meta name="format-detection" content="telephone=yes" />
    <meta property="business:contact_data:street_address" content="Kornfeldstrasse 17b" />
    <meta property="business:contact_data:region" content="Steinach" />
    <meta property="business:contact_data:postal_code" content="9323" />
    <meta property="business:contact_data:country_name" content="Switzerland" />

    <!-- Styles -->
    <link rel="stylesheet" href="main.css" />
    <link rel="preload" href="/fonts/rubis-light.woff2" as="font" type="font/woff2" crossorigin>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ContactPage",
      "name": "Kontakt – Isabella Signer",
      "url": "https://isabella-signer.ch/kontakt",
      "inLanguage": "de-CH",
      "description": "Kontakt für Ayurveda, Breathwork und Coaching in Steinach (SG).",
      "mainEntity": {
        "@type": "Person",
        "name": "Isabella Signer",
        "url": "https://isabella-signer.ch",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Kornfeldstrasse 17b",
          "addressLocality": "Steinach",
          "postalCode": "9323",
          "addressCountry": "CH"
        },
        "contactPoint": [{
          "@type": "ContactPoint",
          "contactType": "customer service",
          "email": "info@isabella-signer.ch",
          "telephone": "+41 71 446 44 36",
          "areaServed": "CH",
          "availableLanguage": ["de-CH", "de"]
        }]
      }
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Startseite", "item": "https://isabella-signer.ch/"},
        {"@type": "ListItem", "position": 2, "name": "Kontakt", "item": "https://isabella-signer.ch/kontakt"}
      ]
    }
    </script>

</head>

<body>
    <?php require_once 'nav.php'; ?>

    <header>
        <h3>Kontakt</h3>
        <h2 class="sub">Ich freue mich auf deine Nachricht – ganz egal, ob du schon konkrete Fragen hast oder einfach herausfinden möchtest, ob meine Begleitung zu dir passt.</h2>
    </header>

    <?php /* Session & CSRF bereits oben initialisiert */ ?>
    <section class="kontakt">

    <?php if ($sent): ?>
        <div class="notice success" id="successNotice">
            <p>Ich habe deine Anfrage erhalten. Herzlichen Dank. Gerne werde ich mich schnellstmöglich bei dir melden.
              <br><br>Alles Liebe, Isabella</p>
            <button type="button" class="btn-send" id="closeSuccessBtn">Zurück</button>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('closeSuccessBtn');
            var notice = document.getElementById('successNotice');
            if (!btn || !notice) return;

            btn.addEventListener('click', function (e) {
              e.preventDefault();

              notice.style.transition = 'opacity .4s ease';
              notice.style.opacity = '0';

              setTimeout(function () {
                notice.style.display = 'none';

                var url = new URL(window.location.href);
                url.searchParams.delete('sent');
                var qs = url.searchParams.toString();
                history.replaceState({}, '', url.pathname + (qs ? '?' + qs : '') + url.hash);
              }, 400);
            });
          });
        </script>
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

        <div class="group">
          <div class="label">Wie möchtest du kontaktiert werden?*</div>
          <div class="options">
            <label class="inline"><input type="radio" name="contact_method" value="email" required> E-Mail</label>
            <label class="inline"><input type="radio" name="contact_method" value="telefonisch" required> Telefonisch</label>
          </div>
        </div>

        <label id="reach-time-wrap" style="display:none;"> Wann bist du zeitlich erreichbar?
        <input type="text" name="reach_time" id="reach_time" maxlength="200" placeholder="z. B. Mo–Fr 9–12 Uhr">
        </label>

        <button type="submit" class="btn">Senden</button>
    </form>
    </section>


    <?php require_once 'footer.php'; ?>
    <?php require_once 'script.php'; ?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var wrap = document.getElementById('reach-time-wrap');
        var input = document.getElementById('reach_time');
        var radios = document.querySelectorAll('input[name="contact_method"]');

        function updateReachTimeVisibility() {
          var checked = document.querySelector('input[name="contact_method"]:checked');
          var isPhone = checked && checked.value === 'telefonisch';
          if (wrap) wrap.style.display = isPhone ? '' : 'none';
          if (input) {
            input.required = !!isPhone;
            if (!isPhone) input.value = '';
          }
        }

        radios.forEach(function(r){ r.addEventListener('change', updateReachTimeVisibility); });
        updateReachTimeVisibility();
      });
    </script>
    <?php require_once 'googleanalytics.php'; ?>
</body>
</html>
