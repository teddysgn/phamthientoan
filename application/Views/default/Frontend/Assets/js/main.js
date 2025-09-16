/**
* Template Name: iPortfolio
* Updated: Mar 10 2023 with Bootstrap v5.2.3
* Template URL: https://bootstrapmade.com/iportfolio-bootstrap-portfolio-websites-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Scrolls to an element with header offset
   */
  const scrollto = (el) => {
    let elementPos = select(el).offsetTop
    window.scrollTo({
      top: elementPos,
      behavior: 'smooth'
    })
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Mobile nav toggle
   */
  on('click', '.mobile-nav-toggle', function(e) {
    select('body').classList.toggle('mobile-nav-active')
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  /**
   * Scrool with ofset on links with a class name .scrollto
   */
  on('click', '.scrollto', function(e) {
    if (select(this.hash)) {
      e.preventDefault()

      let body = select('body')
      if (body.classList.contains('mobile-nav-active')) {
        body.classList.remove('mobile-nav-active')
        let navbarToggle = select('.mobile-nav-toggle')
        navbarToggle.classList.toggle('bi-list')
        navbarToggle.classList.toggle('bi-x')
      }
      scrollto(this.hash)
    }
  }, true)

  /**
   * Scroll with ofset on page load with hash links in the url
   */
  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (select(window.location.hash)) {
        scrollto(window.location.hash)
      }
    }
  });

  /**
   * Hero type effect
   */
  const typed = select('.typed')
  if (typed) {
    let typed_strings = typed.getAttribute('data-typed-items')
    typed_strings = typed_strings.split(',')
    new Typed('.typed', {
      strings: typed_strings,
      loop: true,
      typeSpeed: 100,
      backSpeed: 50,
      backDelay: 2000
    });
  }

  /**
   * Skills animation
   */
  let skilsContent = select('.skills-content');
  if (skilsContent) {
    new Waypoint({
      element: skilsContent,
      offset: '80%',
      handler: function(direction) {
        let progress = select('.progress .progress-bar', true);
        progress.forEach((el) => {
          el.style.width = el.getAttribute('aria-valuenow') + '%'
        });
      }
    })
  }

  /**
   * Porfolio isotope and filter
   */
  window.addEventListener('load', () => {
    let portfolioContainer = select('.portfolio-container');
    if (portfolioContainer) {
      let portfolioIsotope = new Isotope(portfolioContainer, {
        itemSelector: '.portfolio-item'
      });

      let portfolioFilters = select('#portfolio-flters li', true);

      on('click', '#portfolio-flters li', function(e) {
        e.preventDefault();
        portfolioFilters.forEach(function(el) {
          el.classList.remove('filter-active');
        });
        this.classList.add('filter-active');

        portfolioIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        portfolioIsotope.on('arrangeComplete', function() {
          AOS.refresh()
        });
      }, true);
    }

  });

  /**
   * Initiate portfolio lightbox 
   */
  const portfolioLightbox = GLightbox({
    selector: '.portfolio-lightbox'
  });

  /**
   * Portfolio details slider
   */
  new Swiper('.portfolio-details-slider', {
    speed: 400,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /**
   * Testimonials slider
   */
  new Swiper('.testimonials-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 20
      },

      1200: {
        slidesPerView: 3,
        spaceBetween: 20
      }
    }
  });

  /**
   * Animation on scroll
   */
  window.addEventListener('load', () => {
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    })
  });

  /**
   * Initiate Pure Counter 
   */
  new PureCounter();
  

})()

(function () {

  var slidersContainer = document.querySelector('.sliders-container');

  // Initializing the numbers slider
  var msNumbers = new MomentumSlider({
      el: slidersContainer,
      cssClass: 'ms--numbers',
      range: [1, 6],
      rangeContent: function (i) {
          return '0' + i;
      },
      style: {
          transform: [{
              scale: [0.4, 1]
          }],
          opacity: [0, 1]
      },

      interactive: false
  });


  // Initializing the titles slider
  var titles = [
      'Advertising ',
      'Art & Illustration',
      'Packaging',
      'Publication',
      'Social Post',
      'Visual Identity'
  ];

  var msTitles = new MomentumSlider({
      el: slidersContainer,
      cssClass: 'ms--titles',
      range: [0, 5],
      rangeContent: function (i) {
          return '<h3>' + titles[i] + '</h3>';
      },
      vertical: true,
      reverse: true,
      style: {
          opacity: [0, 1]
      },

      interactive: false
  });


  // Initializing the links slider
  var msLinks = new MomentumSlider({
      el: slidersContainer,
      cssClass: 'ms--links',
      range: [0, 5],
      rangeContent: function () {
          return '<a href="../resume.html" class="ms-slide__link btn">View More</a>';
      },
      vertical: true,
      interactive: false
  });


  // Get pagination items
  var pagination = document.querySelector('.pagination');
  var paginationItems = [].slice.call(pagination.children);

  // Initializing the images slider
  var msImages = new MomentumSlider({
      // Element to append the slider
      el: slidersContainer,
      // CSS class to reference the slider
      cssClass: 'ms--images',
      // Generate the 4 slides required
      range: [0, 5],
      rangeContent: function () {
          return '<div class="ms-slide__image-container"><div class="ms-slide__image"></div></div>';
      },
      // Syncronize the other sliders
      sync: [msNumbers, msTitles, msLinks],
      // Styles to interpolate as we move the slider
      style: {
          '.ms-slide__image': {
              transform: [{
                  scale: [1.5, 1]
              }]
          }
      },


      // Update pagination if slider change
      change: function (newIndex, oldIndex) {
          if (typeof oldIndex !== 'undefined') {
              paginationItems[oldIndex].classList.remove('pagination__item--active');
          }
          paginationItems[newIndex].classList.add('pagination__item--active');
      }
  });


  // Select corresponding slider item when a pagination button is clicked
  pagination.addEventListener('click', function (e) {
      if (e.target.matches('.pagination__button')) {
          var index = paginationItems.indexOf(e.target.parentNode);
          msImages.select(index);
      }
  });

})();

$(function () {
  $('.navbar-toggler').click(function () {
      $('body').toggleClass('noscroll');
  })
});
(function () {
  if (typeof _bsa !== 'undefined' && _bsa) {
      // format, zoneKey, segment:value, options
      _bsa.init('flexbar', 'CKYI627U', 'placement:w3layoutscom');
  }
})();
(function () {
  if (typeof _bsa !== 'undefined' && _bsa) {
      // format, zoneKey, segment:value, options
      _bsa.init('fancybar', 'CKYDL2JN', 'placement:demo');
  }
})();
(function () {
  if (typeof _bsa !== 'undefined' && _bsa) {
      // format, zoneKey, segment:value, options
      _bsa.init('stickybox', 'CKYI653J', 'placement:w3layoutscom');
  }
})();
window.dataLayer = window.dataLayer || [];
function gtag() { dataLayer.push(arguments); }
gtag('js', new Date());

gtag('config', 'G-98H8KRKT85');

