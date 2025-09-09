import { debounce } from './helpers';

export function initNumbersSheetUI() {
  const sheet    = document.getElementById('numbersSheet');
  if (!sheet) return;

  const panel    = document.getElementById('numbersPanel');
  const backdrop = document.getElementById('numbersBackdrop');
  const closeBtn = document.getElementById('closeNumbers');

  let lastOpener = null;

  const html   = document.documentElement;
  const header = document.querySelector('header');
  const parent   = sheet.parentElement;
  const siblings = parent ? Array.from(parent.children).filter(el => el !== sheet) : [];
  const inertEls = [header, ...siblings].filter(Boolean);

  const pre = document.getElementById('preAnim');
  let preTimer = null;

  const isInSheet = (el) => !!(el && sheet.contains(el));

  const openPre = (minMs = 2000) => {
    if (!pre) return () => {};
    pre.classList.remove('hidden'); pre.classList.add('is-open');
    sheet.setAttribute('aria-busy', 'true');
    const started = Date.now();
    return () => {
      const rest = Math.max(0, minMs - (Date.now() - started));
      clearTimeout(preTimer);
      preTimer = setTimeout(() => {
        pre.classList.add('hidden'); pre.classList.remove('is-open');
        sheet.removeAttribute('aria-busy');
      }, rest);
    };
  };
  const hidePreNow = () => {
    if (!pre) return;
    clearTimeout(preTimer);
    pre.classList.add('hidden'); pre.classList.remove('is-open');
    sheet.removeAttribute('aria-busy');
  };

  const measureStickyHeights = () => {
    if (!panel) return;
    const hdr = panel.querySelector('.sheet-pro__header');
    const tlb = panel.querySelector('.sheet-pro__toolbar');
    const hdrH = (hdr?.offsetHeight || 72);
    const tlbH = (tlb?.offsetHeight || 44);
    panel.style.setProperty('--hdr-h', `${hdrH}px`);
    panel.style.setProperty('--tlb-h', `${tlbH}px`);
  };
  const onResize = debounce(measureStickyHeights, 120);

  const setOpen = (open, openerEl = null) => {
    if (open) {
      if (openerEl instanceof Element) lastOpener = openerEl;
      else if (!lastOpener && document.activeElement instanceof Element) lastOpener = document.activeElement;

      sheet.classList.remove('hidden');
      sheet.removeAttribute('aria-hidden');
      backdrop?.classList.remove('opacity-0');
      backdrop?.classList.add('opacity-100');

      inertEls.forEach(el => el.setAttribute('inert', ''));
      html.classList.add('overflow-y-hidden', 'numbers-open');

      requestAnimationFrame(() => {
        measureStickyHeights();
        window.addEventListener('resize', onResize, { passive: true });
      });

      const stopPre = openPre(2000);
      setTimeout(() => {
        const focusTarget = document.getElementById('searchNumber') || closeBtn || panel;
        try { focusTarget?.focus({ preventScroll: true }); } catch {}
        stopPre();
      }, 50);
      return;
    }

    // Cierre seguro A11y
    const wasInside = isInSheet(document.activeElement);
    inertEls.forEach(el => el.removeAttribute('inert'));

    if (wasInside) {
      const target = lastOpener || document.querySelector('#openNumbers, .open-numbers') || document.body;
      if (target === document.body) document.body.setAttribute('tabindex', '-1');
      try { target.focus({ preventScroll: true }); } catch {}
      if (target === document.body) setTimeout(() => document.body.removeAttribute('tabindex'), 10);
      try { document.activeElement?.blur?.(); } catch {}
    }

    requestAnimationFrame(() => {
      sheet.setAttribute('aria-hidden', 'true');
      sheet.classList.add('hidden');
      backdrop?.classList.add('opacity-0');
      backdrop?.classList.remove('opacity-100');

      html.classList.remove('overflow-y-hidden', 'numbers-open');

      // oculta barra móvil residual
      document.getElementById('stickyBar')?.classList.add('translate-y-full');

      hidePreNow();
      window.removeEventListener('resize', onResize);
    });
  };

  // Abrir/cerrar
  document.addEventListener('click', (e) => {
    const opener = e.target.closest('#openNumbers, .open-numbers');
    if (opener) { e.preventDefault(); setOpen(true, opener); return; }
    if (e.target === backdrop) setOpen(false);
  });
  closeBtn?.addEventListener('click', () => setOpen(false));
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !sheet.classList.contains('hidden')) setOpen(false);
  });

  // API global simple
  window.__openNumbersSheet  = (openerEl = null) => setOpen(true, openerEl);
  window.__closeNumbersSheet = () => setOpen(false);
}
