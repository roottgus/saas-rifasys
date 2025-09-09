import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import { initNumbersSheetUI } from './uiNumbersSheet';
import { initRifaPage } from './rifaPage';
import { initCheckoutPage } from './checkout';
import './modalReservaPago';

// === SWIPER v10+ ===
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

function ensureInitCheckout() {
  let tries = 0;
  function tryInit() {
    const form = document.getElementById('inlinePayForm');
    if (form) {
      // Solo llama 1 vez
      if (!window.__checkoutInitDone) {
        window.__checkoutInitDone = true;
        initCheckoutPage();
      }
      return;
    }
    if (++tries < 10) {
      setTimeout(tryInit, 120); // Espera y reintenta
    }
  }
  tryInit();
}

document.addEventListener('DOMContentLoaded', () => {
  initNumbersSheetUI();
  initRifaPage();

  // Asegura el init del checkout SIEMPRE que exista el form (página de pago/reserva)
  ensureInitCheckout();

  // SWIPER para Rifas (solo móvil)
  if (window.innerWidth < 768) {
    setTimeout(() => {
      const el = document.querySelector('.swiper-rifas');
      if (el) {
        new Swiper(el, {
          modules: [Navigation, Pagination],
          slidesPerView: 1.15,
          spaceBetween: 18,
          centeredSlides: true,
          loop: false,
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        });
      }
    }, 100);
  }
});
