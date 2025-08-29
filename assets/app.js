/* ==================== */
/* IMPORTS CSS (Ordre critique) */
/* ==================== */
// 1. Bibliothèques de base
import 'bootstrap/dist/css/bootstrap.min.css';
import 'startbootstrap-sb-admin-2/css/sb-admin-2.min.css';
import 'animate.css/animate.min.css';

// 2. Thème SB Admin 2 (doit venir après les librairies qu'il surcharge)

import '@fortawesome/fontawesome-free/css/all.min.css';

// 3. Vos styles personnalisés (toujours en dernier)
import './styles/app.css';

/* ==================== */
/* IMPORTS JS (Ordre critique) */
/* ==================== */
// 1. jQuery (base)
import $ from 'jquery';
window.jQuery = $;
window.$ = $;

// 2. Extensions jQuery
import 'jquery.easing';

// 3. Bootstrap (avec Popper)
import 'bootstrap';

// 4. Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// 5. Autres bibliothèques
import { WOW } from 'wow.js';

/* ==================== */
/* INITIALISATION */
/* ==================== */
$(document).ready(function() {
  // Initialisation WOW.js
  new WOW({
    offset: 100,
    mobile: true
  }).init();

  // Initialisation des composants SB Admin 2
  initSBAdminComponents();
});

/* ==================== */
/* FONCTIONS D'INITIALISATION */
/* ==================== */
function initSBAdminComponents() {
  // Sidebar Toggle
  $('#sidebarToggle').off('click').on('click', function(e) {
    e.preventDefault();
    $('body').toggleClass('sidebar-toggled');
    $('.sidebar').toggleClass('toggled');
  });

  // Tooltips (avec vérification Bootstrap 5)
  if (typeof bootstrap !== 'undefined') {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el);
    });
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
      new bootstrap.Popover(el);
    });
  } else {
    // Fallback pour Bootstrap 4
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
  }
}