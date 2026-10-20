/**
 * Instant category filter for the projects page.
 *
 * Every filter is a real link to a category page, so the page works without
 * JavaScript. When all projects are on the page this script intercepts the
 * clicks and shows or hides cards in place instead.
 */
(function () {
  'use strict';

  var grid = document.querySelector('[data-grid][data-filterable]');
  var filters = document.querySelector('[data-filters]');
  if (!grid || !filters) {
    return;
  }

  var cards = Array.prototype.slice.call(grid.querySelectorAll('.project-card'));

  /** True when a card belongs to the chosen category ("*" means all). */
  function matches(card, key) {
    var categories = (card.getAttribute('data-cats') || '').split(' ');
    return key === '*' || categories.indexOf(key) !== -1;
  }

  /** Mark one filter as current and clear the others. */
  function markCurrent(link) {
    filters.querySelectorAll('[data-filter]').forEach(function (item) {
      item.removeAttribute('aria-current');
    });
    link.setAttribute('aria-current', 'true');
  }

  /** The wide "feature" card only applies while nothing is filtered. */
  function updateFeature(key) {
    cards.forEach(function (card, index) {
      card.classList.toggle('project-card--feature', key === '*' && index === 0);
    });
  }

  filters.addEventListener('click', function (event) {
    var link = event.target.closest('[data-filter]');
    if (!link) {
      return;
    }

    event.preventDefault();
    var key = link.getAttribute('data-filter');

    markCurrent(link);
    cards.forEach(function (card) {
      card.hidden = !matches(card, key);
    });
    updateFeature(key);
  });
})();
