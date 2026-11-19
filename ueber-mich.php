<!DOCTYPE html>
<html lang="de">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# place: http://ogp.me/ns/place#">
    <meta charset="UTF-8" />
    <title>Über mich – Isabella Signer | Ayurveda &amp; Breathwork Coach</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Ehemalige Führungskraft, Mutter und heute Ayurveda Lifestyle- &amp; Ernährungscoach, Holistic Breathwork Coach und Spiritual Lifecoach in Steinach (SG)." />
    <meta name="author" content="Isabella Signer" />
    <link rel="canonical" href="https://isabella-signer.ch/ueber-mich" />
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
    <meta property="og:title" content="Über mich – Isabella Signer | Ayurveda &amp; Breathwork Coach" />
    <meta property="og:description" content="Ehemalige Führungskraft, Mutter und heute Ayurveda Lifestyle- &amp; Ernährungscoach, Holistic Breathwork Coach und Spiritual Lifecoach in Steinach (SG)." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://isabella-signer.ch/ueber-mich" />
    <meta property="og:image" content="https://isabella-signer.ch/img/og-image.jpg" />
    <meta property="og:locale" content="de_CH" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Über mich – Isabella Signer | Ayurveda &amp; Breathwork Coach" />
    <meta name="twitter:description" content="Ehemalige Führungskraft, Mutter und heute Ayurveda Lifestyle- &amp; Ernährungscoach, Holistic Breathwork Coach und Spiritual Lifecoach in Steinach (SG)." />
    <meta name="twitter:image" content="https://isabella-signer.ch/img/og-image.jpg" />

    <meta name="format-detection" content="telephone=yes" />
    <meta property="business:contact_data:street_address" content="Kornfeldstrasse 17b" />
    <meta property="business:contact_data:region" content="Steinach" />
    <meta property="business:contact_data:postal_code" content="9323" />
    <meta property="business:contact_data:country_name" content="Switzerland" />

    <!-- Styles -->
    <link rel="stylesheet" href="/main.css?v=<?= filemtime(__DIR__ . '/main.css') ?>" />
    <link rel="preload" href="/fonts/rubis-light.woff2" as="font" type="font/woff2" crossorigin>

    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "@id": "https://isabella-signer.ch/ueber-mich#webpage",
  "url": "https://isabella-signer.ch/ueber-mich",
  "name": "Über mich – Isabella Signer | Ayurveda & Breathwork Coach",
  "description": "Ehemalige Führungskraft, Mutter und heute Ayurveda Lifestyle- & Ernährungscoach, Holistic Breathwork Coach und Spiritual Lifecoach in Steinach (SG).",
  "inLanguage": "de-CH",
  "isPartOf": {
    "@id": "https://isabella-signer.ch/#website"
  },
  "about": {
    "@id": "https://isabella-signer.ch/#praxis"
  },
  "mainEntity": {
    "@id": "https://isabella-signer.ch/#isabella"
  }
}
    </script>
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Startseite",
      "item": "https://isabella-signer.ch/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Über mich",
      "item": "https://isabella-signer.ch/ueber-mich"
    }
  ]
}
    </script>
    <?php require_once 'schema.php'; ?>
</head>

<body>
    <?php require_once 'nav.php'; ?>
    <main class="ueber-mich">

    <header>
        <p class="eyebrow">Über mich</p>
        <h1>Ich weiss, wie es sich anfühlt, wenn man alles schafft – und sich selbst dabei immer weniger spürt.</h1>
    </header>

    <?php
    $bloecke = [
        [
            'bild'     => 'img/ueber-mich/isabella-signer-01.webp',
            'bild_alt' => 'Isabella Signer sitzt nachdenklich in ihrem Praxisraum zwischen Keramikgefässen',
            'absaetze' => [
                'Ich kenne die vielen Rollen, die Frauen im Leben einnehmen.',
                'Als ehemalige Führungskraft, als Mutter von drei erwachsenen Kindern, als dreifache stolze Nana weiss ich, wie turbulent dein Alltag aussehen kann.',
                'Als Frau mit einem vollen Leben und einem offenen Herzen, das immer weiter wachsen wollte.',
            ],
        ],
        [
            'bild'        => 'img/ueber-mich/isabella-signer-02.webp',
            'bild_alt'    => 'Isabella Signer in ihrem Praxisraum in Steinach (SG)',
            'bild_rechts' => true,
            'absaetze'    => [
                'Heute begleite ich Menschen mit all meiner Erfahrung und meinem Wissen als Ayurveda Lifestyle- & Ernährungscoach, Expertin für Frauengesundheit, Holistic Breathwork Coach und Spiritual Lifecoach auf ihrem Weg raus aus dem Funktionieren und hin zu einem bewussteren Leben.',
                'Meine Leidenschaften: das Leben! Lesen, Tanzen, Natur, gutes Essen, Kochen, Yoga, Weiterentwicklung und ganz besonders – die magische Zeit mit meinen Enkelkindern und meiner Familie.',
                'Was ich mache? Ich halte Räume, in denen Menschen aufhören, sich zu fragen, ob sie genug sind, und anfangen zu spüren, wie unfassbar grossartig sie sind.',
            ],
        ],
    ];
    require __DIR__ . '/bild-text.php';
    ?>

    <?php
    $gruen = [
        'titel'   => 'Glaubst du immer noch, dass Selbstfürsorge ein Luxus ist, der anderen etwas wegnimmt?',
        'spalten' => [
            'Vielleicht hast du gelernt, dich zurückzunehmen, um geliebt zu werden. Vielleicht denkst du, wenn du dir Zeit schenkst, kommt dadurch deine Familie zu kurz.',
            'In Wahrheit nährst du dein Umfeld am besten, wenn du dich selbst nicht vergisst.',
            'Dein innerer Raum muss nicht verhandelt werden. Du darfst da sein – ganz.',
        ],
    ];
    $gruenButton = 'Lass uns darüber reden';
    require __DIR__ . '/gruen-text.php';
    ?>

    <section class="light-sec">
        <?php require __DIR__ . '/schritte.php'; ?>

        <?php
        $begleitungButton = false;
        require __DIR__ . '/begleitung-teaser.php';
        ?>
    </section>

    <?php
    $termineTitel = 'Meine Angebote';
    $termineText  = 'Du musst dich nicht zwischen Ayurveda, Coaching oder Breathwork entscheiden – ich verbinde diese Qualitäten und gestalte für dich eine Begleitung, die ganz auf deine aktuellen Bedürfnisse abgestimmt ist.';
    require __DIR__ . '/termine.php';
    ?>

    </main>

    <?php require_once 'footer.php'; ?>
    <?php require_once 'script.php'; ?>
    <?php require_once 'googleanalytics.php'; ?>
</body>
</html>
