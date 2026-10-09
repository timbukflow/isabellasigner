$(document).ready(function() {

  // Nav
    $('#burger').click(function() {
    $('#sideMenu').toggleClass('open');
    $('body').toggleClass('noscroll');

  });

  // Parallax
  const elements = document.querySelectorAll(".parallax");

  const parallaxData = Array.from(elements).map(el => ({
    el,
    factor: parseFloat(el.dataset.speed) || 0.2, 
    current: 0,
    targetY: 0
  }));

  function smoothParallax() {
    const scrolled = window.scrollY;

    parallaxData.forEach(obj => {
      obj.targetY = scrolled * obj.factor;
      obj.current += (obj.targetY - obj.current) * 0.05; 
      obj.el.style.transform = `translateY(${obj.current}px)`;
    });

    requestAnimationFrame(smoothParallax);
  }

  smoothParallax();

  // FAQ-Accordion (ein Element offen; erneuter Klick schliesst)
    $(function () {
    $('.faq-q').on('click', function () {
      const $btn = $(this);
      const $answer = $btn.next('.faq-a'); // immer das nächste Element öffnen

      const isOpen = $btn.hasClass('open');

      // alles schliessen
      $('.faq-q').removeClass('open').attr('aria-expanded', 'false');
      $('.faq-a').slideUp(220);

      // aktuelles toggeln
      if (!isOpen) {
        $btn.addClass('open').attr('aria-expanded', 'true');
        $answer.slideDown(220);
      }
    });
  });

});

// Scroller (Antworten-Karten) und Link kopieren – ohne jQuery
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.antworten-scroller').forEach(function (bereich) {
    var scroller = bereich.querySelector('.scroller');
    var zurueck = bereich.querySelector('.scroller-pfeil.links');
    var weiter = bereich.querySelector('.scroller-pfeil.rechts');
    if (!scroller) return;

    function schrittweite() {
      var karte = scroller.querySelector('.antwort-karte');
      var abstand = parseFloat(getComputedStyle(scroller).columnGap || getComputedStyle(scroller).gap) || 40;
      return karte ? karte.getBoundingClientRect().width + abstand : 350;
    }

    // Rest am Ende ausgleichen, damit jede Scrollposition links bündig ist
    function restAusgleichen() {
      scroller.style.paddingRight = '';
      var schritt = schrittweite();
      var ueberstand = (scroller.scrollWidth - scroller.clientWidth) % schritt;
      if (ueberstand > 1) scroller.style.paddingRight = (parseFloat(getComputedStyle(scroller).paddingRight) + (schritt - ueberstand)) + 'px';
    }

    // Pfeile auf die Mitte der Bilder setzen (nicht auf die Mitte der ganzen Karte)
    function pfeileEinmitten() {
      var bild = scroller.querySelector('.antwort-karte-bild');
      if (!bild) return;
      var mitte = bild.getBoundingClientRect().height / 2;
      bereich.querySelectorAll('.scroller-pfeil').forEach(function (p) { p.style.top = mitte + 'px'; });
    }

    function pfeileAktualisieren() {
      var maxLinks = scroller.scrollWidth - scroller.clientWidth;
      if (zurueck) zurueck.hidden = scroller.scrollLeft <= 2;
      if (weiter) weiter.hidden = scroller.scrollLeft >= maxLinks - 2;
    }

    bereich.querySelectorAll('.scroller-pfeil').forEach(function (btn) {
      btn.addEventListener('click', function () {
        scroller.scrollBy({ left: schrittweite() * parseInt(btn.dataset.scroll, 10), behavior: 'smooth' });
      });
    });

    // nach freiem Scrollen auf die nächste Karte einrasten, damit links nichts angeschnitten bleibt
    var warten;
    function einrasten() {
      var schritt = schrittweite();
      var ziel = Math.round(scroller.scrollLeft / schritt) * schritt;
      var maxLinks = scroller.scrollWidth - scroller.clientWidth;
      ziel = Math.max(0, Math.min(ziel, maxLinks));
      if (Math.abs(ziel - scroller.scrollLeft) > 1) {
        scroller.scrollTo({ left: ziel, behavior: 'smooth' });
      }
    }

    restAusgleichen();
    pfeileEinmitten();
    window.addEventListener('resize', function () { restAusgleichen(); pfeileEinmitten(); });
    window.addEventListener('load', pfeileEinmitten);

    scroller.addEventListener('scroll', function () {
      pfeileAktualisieren();
      clearTimeout(warten);
      warten = setTimeout(einrasten, 140);
    }, { passive: true });
    window.addEventListener('resize', pfeileAktualisieren);
    pfeileAktualisieren();
  });

  document.querySelectorAll('.artikel-teilen-link').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var text = btn.querySelector('span');
      navigator.clipboard.writeText(btn.dataset.link).then(function () {
        var alt = text.textContent;
        text.textContent = 'Kopiert';
        setTimeout(function () { text.textContent = alt; }, 2000);
      });
    });
  });
});

// Themenfilter auf /antworten
document.addEventListener('DOMContentLoaded', function () {
  var filter = document.querySelector('.themen-filter');
  if (!filter) return;
  var karten = document.querySelectorAll('.antworten-raster .antwort-karte');
  var leer = document.querySelector('.antworten-leer');

  filter.addEventListener('click', function (e) {
    var pille = e.target.closest('.thema-pille');
    if (!pille) return;
    e.preventDefault();

    var abwaehlen = pille.classList.contains('aktiv');
    var thema = abwaehlen ? '' : pille.dataset.thema;

    filter.querySelectorAll('.thema-pille').forEach(function (p) {
      var aktiv = !abwaehlen && p === pille;
      p.classList.toggle('aktiv', aktiv);
      if (aktiv) { p.setAttribute('aria-current', 'true'); } else { p.removeAttribute('aria-current'); }
      p.setAttribute('href', aktiv ? '/antworten' : '/antworten?thema=' + encodeURIComponent(p.dataset.thema));
    });

    var sichtbar = 0;
    karten.forEach(function (k) {
      var passt = !thema || (k.dataset.themen || '').split('|').indexOf(thema) > -1;
      k.hidden = !passt;
      if (passt) sichtbar++;
    });
    if (leer) leer.hidden = sichtbar > 0;

    history.replaceState(null, '', thema ? '/antworten?thema=' + encodeURIComponent(thema) : '/antworten');
  });
});
