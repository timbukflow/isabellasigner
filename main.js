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
