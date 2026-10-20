/**
 * Site header behaviour: divider after scrolling and the mobile menu.
 *
 * Progressive enhancement only: the markup works without this script, and the
 * stylesheet only hides the menu when the "js" class is present on <html>.
 */
(function () {
  'use strict';

  var header = document.querySelector('[data-header]');
  if (!header) {
    return;
  }

  /** Show a divider under the sticky header once the page has scrolled. */
  function watchScroll() {
    function update() {
      header.classList.toggle('is-scrolled', window.scrollY > 8);
    }
    update();
    window.addEventListener('scroll', update, { passive: true });
  }

  /** Open and close the mobile menu; Escape closes it and returns focus. */
  function setUpMenu() {
    var toggle = header.querySelector('.nav-toggle');
    if (!toggle) {
      return;
    }

    function setOpen(open) {
      header.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    toggle.addEventListener('click', function () {
      setOpen(!header.classList.contains('is-open'));
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && header.classList.contains('is-open')) {
        setOpen(false);
        toggle.focus();
      }
    });
  }

  watchScroll();
  setUpMenu();
})();
