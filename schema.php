<?php
/*
 * Zentrale strukturierte Daten (Person, Praxis, Website).
 * Seiten verweisen per @id darauf, statt die Angaben zu wiederholen:
 *   https://isabella-signer.ch/#isabella  – Person
 *   https://isabella-signer.ch/#praxis    – Praxis / lokales Unternehmen
 *   https://isabella-signer.ch/#website   – Website
 */
$schemaBase = 'https://isabella-signer.ch';
$schemaGraph = [
  '@context' => 'https://schema.org',
  '@graph' => [
    [
      '@type' => 'Person',
      '@id' => $schemaBase . '/#isabella',
      'name' => 'Isabella Signer',
      'url' => $schemaBase . '/ueber-mich',
      'image' => $schemaBase . '/img/isabella-signer-teaser-ueber-mich.webp',
      'jobTitle' => 'Ayurveda Lifestyle- & Ernährungscoach, Holistic Breathwork Coach, Spiritual Lifecoach',
      'description' => 'Isabella Signer begleitet in Steinach (SG) bei Stress, Erschöpfung und innerer Unruhe – mit Emotional Bodywork, Breathwork, Ayurveda & Ernährung und Coaching.',
      'knowsAbout' => [
        'Stressbewältigung', 'Erschöpfung', 'Innere Unruhe', 'Schlafprobleme', 'Emotionales Essen',
        'Emotional Bodywork', 'Breathwork', 'Ayurveda', 'Ernährung', 'Coaching', 'Frauengesundheit',
      ],
      'worksFor' => ['@id' => $schemaBase . '/#praxis'],
      'sameAs' => ['https://www.instagram.com/isabella.signer/'],
    ],
    [
      '@type' => 'HealthAndBeautyBusiness',
      '@id' => $schemaBase . '/#praxis',
      'name' => 'Isabella Signer',
      'description' => 'Ganzheitliche Begleitung bei Stress, Erschöpfung und innerer Unruhe: Emotional Bodywork, Breathwork, Ayurveda & Ernährung und Mindset-Coaching in Steinach (SG) und online.',
      'url' => $schemaBase . '/',
      'logo' => $schemaBase . '/img/isabella-signer-logo.svg',
      'image' => $schemaBase . '/img/og-image.jpg',
      'email' => 'info@isabella-signer.ch',
      'telephone' => '+41 78 758 19 12',
      'priceRange' => 'CHF 0–880',
      'currenciesAccepted' => 'CHF',
      'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Kornfeldstrasse 17b',
        'postalCode' => '9323',
        'addressLocality' => 'Steinach',
        'addressRegion' => 'SG',
        'addressCountry' => 'CH',
      ],
      'geo' => ['@type' => 'GeoCoordinates', 'latitude' => 47.4995, 'longitude' => 9.4368],
      'areaServed' => [
        ['@type' => 'City', 'name' => 'Steinach'],
        ['@type' => 'City', 'name' => 'St. Gallen'],
        ['@type' => 'City', 'name' => 'Rorschach'],
        ['@type' => 'City', 'name' => 'Arbon'],
        ['@type' => 'AdministrativeArea', 'name' => 'Kanton St. Gallen'],
        ['@type' => 'AdministrativeArea', 'name' => 'Kanton Thurgau'],
      ],
      'founder' => ['@id' => $schemaBase . '/#isabella'],
      'sameAs' => ['https://www.instagram.com/isabella.signer/'],
    ],
    [
      '@type' => 'WebSite',
      '@id' => $schemaBase . '/#website',
      'url' => $schemaBase . '/',
      'name' => 'Isabella Signer',
      'inLanguage' => 'de-CH',
      'publisher' => ['@id' => $schemaBase . '/#praxis'],
    ],
  ],
];
?>
<script type="application/ld+json">
<?= json_encode($schemaGraph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>

</script>
