// resources/js/store/modalReservaPago.js
// API: window.showReservaPagoModal({ nums:[1,2], total: 100, onReservar(datos?), onPagar(), flow })

(function () {
  const ID = 'reservaPagoModal';
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const money = v => `$${(Number(v) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

  function getModal() {
    const m = document.getElementById(ID);
    if (!m) { console.error('[modalReservaPago] Falta #reservaPagoModal'); }
    return m;
  }

  function formatNumLabel(n) {
    const grid = document.getElementById('numbersGrid');
    if (grid) {
      const btn = grid.querySelector(`.num[data-num="${n}"]`);
      if (btn && btn.dataset.label) return btn.dataset.label;
      const sample = grid.querySelector('.num[data-label]');
      const padLen = sample ? (sample.dataset.label || '').length : 0;
      if (padLen) return String(n).padStart(padLen, '0');
    }
    return String(n);
  }

  function open(state) {
    const m = getModal();
    if (!m) return;
    m._state = state || {};

    // Detecta flujo "pay" (pagar ahora)
    const flow = state?.flow || (typeof state?.onPagar === 'function' && !state?.onReservar ? 'pay' : 'reserve');
    const isPayNow = flow === 'pay';

    // Estado visual inicial
    $('#modalBtnsBox', m)?.classList.remove('hidden');
    $('#reservaFormBox', m)?.classList.add('hidden');

    // Si es "pagar ahora", oculta completamente el botón de reservar y el form
    if (isPayNow) {
      $('#btnModalReservar', m)?.classList.add('hidden');
      $('#reservaFormBox', m)?.classList.add('hidden');
    } else {
      $('#btnModalReservar', m)?.classList.remove('hidden');
    }

    // Chips y total
    const chips = $('#modalNums', m);
    if (chips) {
      const nums = Array.isArray(state.nums) ? state.nums : [];
      chips.innerHTML = nums
        .map(n => {
          const label = formatNumLabel(n);
          return `<span class="chip bg-yellow-100 text-yellow-800 border-yellow-300">#${label}</span>`;
        })
        .join('');
    }
    const sumEl = $('#modalSum', m);
    if (sumEl) sumEl.textContent = money(state.total);

    // Mensaje informativo
    const msg = $('#modalMsg', m);
    if (msg) {
      msg.className = 'text-xs text-yellow-700 text-center mt-1 mb-2';
      msg.textContent = isPayNow
        ? 'Vas a proceder al pago inmediato de tus números seleccionados.'
        : 'Si reservas, tus números se apartarán por 4 horas. Pasado ese tiempo, se liberarán automáticamente si no pagas.';
    }

    // Abrir modal
    m.style.pointerEvents = 'auto';
    m.classList.remove('hidden', 'opacity-0');
    m.classList.add('opacity-100');
    document.documentElement.classList.add('overflow-y-hidden');
    document.body.classList.add('overflow-y-hidden');
  }

  function close() {
    const m = getModal();
    if (!m) return;
    m.classList.remove('opacity-100');
    m.classList.add('opacity-0');
    setTimeout(() => m.classList.add('hidden'), 160);
    document.documentElement.classList.remove('overflow-y-hidden');
    document.body.classList.remove('overflow-y-hidden');
  }

  // === Delegación de eventos (se ata UNA SOLA VEZ) ===
  document.addEventListener('click', (e) => {
    const m = getModal();
    if (!m || m.classList.contains('hidden')) return;

    // Cerrar por fondo o por X
    if (e.target === m || e.target.closest('.modal-close')) { close(); return; }

    /// Este callback se usa cuando el usuario da "Pagar Ahora"
if (e.target.closest('#btnModalPagar')) {
  e.preventDefault();
  close();

  // Aquí llama a la función que hace la reserva directa con pay_now=1
  if (typeof m._state?.onPagar === 'function') {
    m._state.onPagar({
      // Puedes pasar datos aquí si los necesitas
    });
  }
  return;
}


    // Reservar (solo si no es "pagar ahora")
    if (e.target.closest('#btnModalReservar')) {
      e.preventDefault();
      // Si está oculto el botón, no hace nada (protección)
      if (e.target.closest('.hidden')) return;
      const btns = $('#modalBtnsBox', m);
      const formBox = $('#reservaFormBox', m);
      if (formBox) {
        btns && btns.classList.add('hidden');
        formBox.classList.remove('hidden');
        const firstInput = formBox.querySelector('input,select,textarea');
        firstInput && firstInput.focus({ preventScroll: true });
      } else {
        close();
        m._state?.onReservar && m._state.onReservar();
      }
      return;
    }
  }, true);

  // Enviar formulario de reserva (si está embebido)
  document.addEventListener('submit', async (e) => {
    const m = getModal();
    if (!m || m.classList.contains('hidden')) return;
    if (!e.target.matches('#formReserva')) return;

    e.preventDefault();
    const form = e.target;
    const msg = $('#msgReserva', form.closest('#reservaFormBox') || form);

    const fd = new FormData(form);
    const nombre = (fd.get('nombre') || '').toString().trim();
    const whatsapp = (fd.get('whatsapp') || '').toString().trim();
    const email = (fd.get('email') || '').toString().trim();
    const preferencia = (fd.get('preferencia') || '').toString().trim();

    if (!nombre || !whatsapp || !email || !preferencia) {
      if (msg) { msg.className = 'min-h-[32px] text-base text-center text-red-600'; msg.textContent = 'Todos los campos son obligatorios.'; }
      return;
    }

    try {
      await m._state?.onReservar?.({ nombre, whatsapp, email, preferencia });
      // close(); // descomenta si deseas cerrar automáticamente después de enviar
    } catch (err) {
      if (msg) { msg.className = 'min-h-[32px] text-base text-center text-red-600'; msg.textContent = err?.message || 'No fue posible registrar la reserva.'; }
    }
  });

  // ESC para cerrar
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const m = getModal();
      if (m && !m.classList.contains('hidden')) close();
    }
  });

  // API pública
  window.showReservaPagoModal = (opts) => open(opts || {});
  window.closeReservaPagoModal = close;
})();
