<?php
$currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
$uriParts = parse_url($currentUrl);
$basePath = $uriParts['path'] ?? '/';
$queryParams = [];
if (!empty($uriParts['query'])) {
  parse_str($uriParts['query'], $queryParams);
}
unset($queryParams['newsletter'], $queryParams['reason']);
$redirectTarget = $basePath . ($queryParams ? '?' . http_build_query($queryParams) : '');
$redirectTarget = $redirectTarget !== '' ? $redirectTarget : '/';
$newsletterStatus = isset($_GET['newsletter']) ? (string)$_GET['newsletter'] : '';
$newsletterReason = isset($_GET['reason']) ? (string)$_GET['reason'] : '';
?>

<footer class="site-footer" id="newsletter">
  <div class="footer-inner">

    <div class="footer-top">
      <h2 class="footer-title">Weiblich. Sensibel. Stark.</h2>
      <div class="footer-icons">
          <a href="https://www.instagram.com/isabella_signer_be_natural" target="_blank" aria-label="Instagram">
            <img src="img/icon-instagram.svg" alt="Instagram Icon">
          </a>
          <a href="mailto:info@isabella-signer.ch" aria-label="E-Mail schreiben">
            <img src="img/icon-mail.svg" alt="E-Mail Icon">
          </a>
          <a href="tel:+41787581912" aria-label="Anrufen">
            <img src="img/icon-phone.svg" alt="Telefon Icon">
          </a>
      </div>
    </div>

    <div class="footer-newsletter">
      <div class="footer-newsletter-intro">
        <p class="footer-newsletter-slogan">Für dich und dein Wohlbefinden.</p>
        <p class="footer-newsletter-copy">Erhalte Inspirationen rund um Ayurveda, Breathwork und Coaching direkt in dein Postfach.</p>
      </div>
      <form class="footer-newsletter-form" action="/newsletter" method="post" novalidate>
        <div class="newsletter-grid">
          <label class="newsletter-field">
            <span>Vorname*</span>
            <input type="text" name="firstname" required maxlength="120" autocomplete="given-name">
          </label>
          <label class="newsletter-field">
            <span>Nachname*</span>
            <input type="text" name="lastname" required maxlength="120" autocomplete="family-name">
          </label>
          <label class="newsletter-field">
            <span>E-Mail-Adresse*</span>
            <input type="email" name="email" required maxlength="160" autocomplete="email">
          </label>
        </div>
        <div class="newsletter-meta">
          <div class="newsletter-notice" role="status" aria-live="polite">
            <?php if ($newsletterStatus === 'success'): ?>
              <span class="success">Danke für deine Anmeldung! Bitte bestätige die E-Mail, die wir dir gerade gesendet haben.</span>
            <?php elseif ($newsletterStatus === 'error'): ?>
              <span class="error">Es gab ein Problem bei der Anmeldung<?= $newsletterReason ? ': ' . htmlspecialchars($newsletterReason, ENT_QUOTES, 'UTF-8') : '.' ?> Bitte versuche es erneut.</span>
            <?php endif; ?>
          </div>
          <button type="submit" class="newsletter-submit">Anmelden</button>
        </div>

        <div class="newsletter-hidden" aria-hidden="true">
          <label>Bitte nicht ausfüllen<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirectTarget . '#newsletter', ENT_QUOTES, 'UTF-8'); ?>">
      </form>
    </div>

    <div class="footer-meta">
      <p>© <?php echo date('Y'); ?> Isabella Signer</p>
      <a href="datenschutz">Datenschutz</a>
      <a href="impressum">Impressum</a>
    </div>

  </div>
</footer>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Isabella Signer",
  "url": "https://isabella-signer.ch",
  "sameAs": [
    "https://www.instagram.com/isabella_signer_be_natural"
  ]
}
</script>
