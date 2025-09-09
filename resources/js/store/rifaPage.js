
import { qs, qsa, money, getCsrf, firstById, debounce } from './helpers';

// Exporto init para que lo invoque index.js

export function initRifaPage() {
  
  const grid = qs('#numbersGrid');
  if (!grid) return;

  const cfg = {
    price:    Number(window.Store?.Rifa?.price     ?? 0),
    min:      Number(window.Store?.Rifa?.min       ?? 1),
    max:      Number(window.Store?.Rifa?.max       ?? 9999),
    postUrl:  window.Store?.Rifa?.postUrl   ?? null,
    minutes:  Number(window.Store?.Rifa?.minutes   ?? 20),
    pageSize: Number(window.Store?.Rifa?.pageSize  ?? 200),
    csrf:     getCsrf(),
  };
  // normaliza
  cfg.min = Math.max(1, Math.floor(cfg.min));
  cfg.max = Math.max(cfg.min, Math.floor(cfg.max || 9999));

  // elementos
  const totalEl    = firstById('totalAmount');
  const countEl    = firstById('selCount','count');
  const search     = firstById('searchNumber','findNum');
  const luckBtn    = firstById('btnLuck','randomPick');
  const reserveBtn = firstById('btnReserve','continue');
  const buyNowBtn  = firstById('btnBuyNow');

  const headCount  = firstById('hSelCount');
  const headTotal  = firstById('hTotalAmount');
  const headCount2 = firstById('hSelCount2');
  const incBtn     = firstById('qtyInc');
  const decBtn     = firstById('qtyDec');

  // modal opcional
  const overlay    = firstById('confirmOverlay');
  const modalCloseBtn  = firstById('modalClose');
  const listEl     = firstById('selList');
  const sumEl      = firstById('selTotal');
  const btnOnlyR   = firstById('confirmReserve');
  const btnPay     = firstById('confirmPay');

  // live region
  let live = qs('#live-region');
  if (!live) {
    live = document.createElement('div');
    live.id = 'live-region';
    live.setAttribute('aria-live','polite');
    live.className = 'sr-only';
    document.body.appendChild(live);
  }

  // estado
  const selected = new Set();
  window.Store = window.Store || {};
  window.Store.Selected = selected;

  // helpers selección
  const setBtnSelected = (btn, on) => {
    btn.classList.toggle('selected', !!on);
    btn.setAttribute('aria-pressed', on ? 'true':'false');

    // LIMPIA todas las clases dinámicas de estados ANTES de agregar las nuevas
    btn.classList.remove(
      'bg-white', 'border-blue-100', 'text-gray-800', 'hover:bg-blue-50', 'cursor-pointer',
      'bg-yellow-100', 'border-yellow-200', 'text-yellow-700', 'opacity-80', 'cursor-not-allowed',
      'bg-gray-100', 'border-gray-200', 'text-gray-400', 'opacity-60',
      'bg-[var(--primary)]', 'text-white', 'border-[var(--primary)]', 'ring-2', 'ring-[var(--primary)]', 'brightness-110', 'shadow',
      // Quita también cualquier class dinámica posible
      ...Array.from(btn.classList).filter(c => c.startsWith('border-') || c.startsWith('bg-') || c.startsWith('ring-'))
    );

    if (on) {
      // SELECCIONADO
      btn.classList.add('bg-[var(--primary)]', 'text-white', 'border-[var(--primary)]', 'ring-2', 'ring-[var(--primary)]', 'brightness-110', 'shadow');
    } else {
      // NO SELECCIONADO: disponible
      btn.classList.add('bg-white', 'border-blue-100', 'text-gray-800', 'hover:bg-blue-50', 'cursor-pointer');
    }
  };

  const isDisponible = (btn) =>
    btn.classList.contains('disponible') ||
    (btn.dataset.state ?? btn.dataset.s) === 'disponible';

  // sticky móvil
  let sticky = qs('#stickyBar');
  let stickyCount = sticky ? qs('#mSelCount', sticky) : null;
  let stickyTotal = sticky ? qs('#mTotalAmount', sticky) : null;
  let stickyBtn   = sticky ? qs('#mContinue', sticky) : null;

  function mountStickyBarIfMissing() {
    if (sticky) return;
    const wrap = document.createElement('div');
    wrap.id = 'stickyBar';
    wrap.className = 'fixed inset-x-0 bottom-0 z-40 sm:hidden translate-y-full transition-transform duration-200';
    wrap.innerHTML = `
      <div class="mx-auto max-w-6xl px-4 pb-3">
        <div class="flex items-center gap-3 rounded-2xl border border-black/10 bg-white/90 backdrop-blur p-3 shadow-lg">
          <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-black/10">
            <span class="text-xs opacity-70">SELECCIONADOS</span>
            <strong id="mSelCount">0</strong>
          </div>
          <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-black/10">
            <span class="text-xs opacity-70">TOTAL</span>
            <strong id="mTotalAmount">$0.00</strong>
          </div>
          <button id="mContinue" class="btn btn-primary ml-auto">Continuar</button>
        </div>
      </div>`;
    document.body.appendChild(wrap);
    sticky = wrap;
    stickyCount = qs('#mSelCount', sticky);
    stickyTotal = qs('#mTotalAmount', sticky);
    stickyBtn   = qs('#mContinue', sticky);
  }
  function showSticky(show) {
    if (!sticky) return;
    sticky.classList.toggle('translate-y-full', !show);
    sticky.classList.toggle('translate-y-0', show);
  }
  function bindStickyAction() {
    if (stickyBtn && reserveBtn) {
      stickyBtn.addEventListener('click', (e) => { e.preventDefault(); reserveBtn.click(); }, { once:false });
    }
  }

  // totales
  function syncTotals() {
    const totalStr = money(selected.size * cfg.price);
    countEl && (countEl.textContent = selected.size);
    totalEl && (totalEl.textContent = totalStr);
    headCount  && (headCount.textContent  = selected.size);
    headTotal  && (headTotal.textContent  = totalStr);
    headCount2 && (headCount2.textContent = selected.size);

    const canProceed = selected.size >= cfg.min && selected.size <= cfg.max;
    if (reserveBtn) reserveBtn.disabled = !canProceed;

    mountStickyBarIfMissing();
    if (sticky) {
      if (stickyTotal) stickyTotal.textContent = totalStr;
      if (stickyCount) stickyCount.textContent = String(selected.size);
      if (stickyBtn)   stickyBtn.disabled = !canProceed;
      showSticky(selected.size > 0 && !!cfg.postUrl);
      bindStickyAction();
    }

    // si hay checkout embebido, mantenerlo al día
    try { window.updateCheckoutSummary?.(); } catch {}
    live.textContent = `Seleccionados ${selected.size}`;
  }

  // toggle
  function toggleNumber(btn) {
    if (!isDisponible(btn) || btn.disabled) return;
    const num = Number(btn.dataset.num);
    if (selected.has(num)) {
      selected.delete(num); setBtnSelected(btn,false); syncTotals(); return;
    }
    if (selected.size >= cfg.max) {
      try { btn.animate([{transform:'scale(.96)'},{transform:'scale(1.06)'},{transform:'scale(1)'}],{duration:220,easing:'ease-out'}); } catch {}
      return;
    }
    selected.add(num); setBtnSelected(btn,true);
    try { btn.animate([{transform:'scale(.96)'},{transform:'scale(1.06)'},{transform:'scale(1)'}],{duration:220,easing:'ease-out'}); } catch {}
    syncTotals();
  }

  // paginación
  const all = qsa('.num', grid);
  all.forEach((el, i) => el.dataset.page = String(Math.floor(i / cfg.pageSize) + 1));
  const totalPages = Math.max(1, Math.ceil(all.length / cfg.pageSize));
  let currentPage = 1;

  let cols = 5;
  const computeCols = () => {
    const raw = getComputedStyle(grid).gridTemplateColumns || '';
    cols = (raw.split(' ').filter(Boolean).length) || 5;
  };

  function renderPage(page) {
    currentPage = Math.min(Math.max(1, page), totalPages);
    const target = String(currentPage);
    requestAnimationFrame(() => {
      for (const el of all) el.style.display = (el.dataset.page === target) ? '' : 'none';
      updatePagers();
      computeCols();
      const firstVisible = all.find(el => el.style.display !== 'none' && !el.disabled);
      firstVisible?.focus({ preventScroll: true });
    });
  }
  function updatePagers() {
    qsa('.pager').forEach(p => {
      const t = qs('.pi-text', p);
      if (t) t.textContent = `Pag: ${currentPage}/${totalPages}`;
      qsa('[data-act]', p).forEach(btn => {
        const act = btn.getAttribute('data-act');
        let dis = false;
        if (act === 'first' || act === 'prev') dis = currentPage <= 1;
        if (act === 'last'  || act === 'next') dis = currentPage >= totalPages;
        btn.disabled = dis;
      });
    });
  }
  document.addEventListener('click', (e) => {
    const b = e.target.closest('.pager [data-act]');
    if (!b) return;
    const act = b.getAttribute('data-act');
    if (act === 'first') return renderPage(1);
    if (act === 'prev')  return renderPage(currentPage - 1);
    if (act === 'next')  return renderPage(currentPage + 1);
    if (act === 'last')  return renderPage(totalPages);
  });

  // click grid
  document.addEventListener('click', (e) => {
    const b = e.target.closest('#numbersGrid .num');
    if (!b) return;
    toggleNumber(b);
  });

  // teclado grid
  grid.addEventListener('keydown', (e) => {
    const current = e.target.closest('.num');
    if (!current) return;
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleNumber(current); return; }
    const visibles = all.filter(el => el.style.display !== 'none');
    const idx = visibles.indexOf(current); if (idx < 0) return;
    let nextIdx = null;
    if (e.key === 'ArrowRight') nextIdx = idx + 1;
    if (e.key === 'ArrowLeft')  nextIdx = idx - 1;
    if (e.key === 'ArrowDown')  nextIdx = idx + cols;
    if (e.key === 'ArrowUp')    nextIdx = idx - cols;
    if (nextIdx !== null) { e.preventDefault(); const next = visibles[nextIdx]; if (next) next.focus(); }
  });

  // búsqueda (exacto por label + prefijo)
  if (search) {
    let lastHighlighted = null;
    const highlightClass = 'is-search';
    const labelLen = (all.find(el => el.dataset.label)?.dataset.label?.length) || 0;
    const toLabel = (s) => (s||'').trim().padStart(labelLen, '0');
    const clearHighlight = () => { if (lastHighlighted){ lastHighlighted.classList.remove(highlightClass); lastHighlighted = null; } };

    const onInput = (q) => {
      q = (q || '').trim();
      if (!q) {
        const target = String(currentPage);
        for (const el of all) el.style.display = (el.dataset.page === target) ? '' : 'none';
        clearHighlight(); return;
      }
      const qLabel = toLabel(q);
      const exact = all.find(el => (el.dataset.label || '') === q || (el.dataset.label || '') === qLabel);
      if (exact) {
        const pg = Number(exact.dataset.page);
        if (pg !== currentPage) renderPage(pg);
        clearHighlight(); exact.classList.add(highlightClass); lastHighlighted = exact;
        try { exact.scrollIntoView({ behavior:'smooth', block:'center' }); } catch {}
        return;
      }
      const pageNow = String(currentPage);
      clearHighlight();
      for (const el of all) {
        if (el.dataset.page !== pageNow) continue;
        const lbl = (el.dataset.label || '');
        el.style.display = (lbl.startsWith(q) || lbl.startsWith(qLabel)) ? '' : 'none';
      }
    };

    search.addEventListener('input', debounce(e => onInput(e.target.value), 120));
    search.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        const q = (search.value || '').trim(); if (!q) return;
        const qLabel = toLabel(q);
        const hit = all.find(el => (el.dataset.label || '') === q || (el.dataset.label || '') === qLabel);
        if (hit && isDisponible(hit)) { renderPage(Number(hit.dataset.page)); toggleNumber(hit); }
      } else if (e.key === 'Escape') { search.value = ''; onInput(''); }
    });
  }

  // suerte
  const sheetEl = document.getElementById('numbersSheet');

  // ===== WhatsApp float: ocultar SOLO mientras se eligen números =====
  const WA_SELECTOR = 'a[aria-label="Contactar por WhatsApp"], a[href*="wa.me"], a[href*="api.whatsapp.com"]';
  const waFloatEl = document.querySelector(WA_SELECTOR);
  const sheet = document.getElementById('numbersSheet');

  function updateWhatsappFloatVisibility() {
    if (!waFloatEl) return;
    const sheetOpen = !!(sheet && !sheet.classList.contains('hidden'));
    // Oculta cuando el panel de números está abierto; se muestra en checkout u otras vistas
    waFloatEl.style.display = sheetOpen ? 'none' : '';
  }

  // Observa cuando el panel se abre/cierra (clase hidden)
  if (sheet) {
    const obs = new MutationObserver(updateWhatsappFloatVisibility);
    obs.observe(sheet, { attributes: true, attributeFilter: ['class'] });
  }

  // Asegura estado correcto al cargar
  updateWhatsappFloatVisibility();

  // Cuando el usuario presiona Continuar (puede cerrar sheet y abrir checkout), refresca visibilidad
  document.addEventListener('click', (e) => {
    const b = e.target.closest('#btnReserve, #mContinue');
    if (b) setTimeout(updateWhatsappFloatVisibility, 0);
  });
  // ===== fin WhatsApp float =====

  const preOverlay  = document.getElementById('preNumbers');
  const preTitle    = preOverlay?.querySelector('.pre-anim__title') || null;
  const preSub      = preOverlay?.querySelector('.pre-anim__sub')   || null;
  let luckTimer = null;
  const showLuckyPre = (minMs = 500, title='Eligiendo al azar…', sub='Un momento') => {
    if (!preOverlay) return () => {};
    if (preTitle) preTitle.textContent = title;
    if (preSub)   preSub.textContent   = sub;
    preOverlay.classList.remove('hidden'); preOverlay.classList.add('is-open');
    sheetEl?.setAttribute('aria-busy','true');
    const started = Date.now();
    return () => {
      const rest = Math.max(0, minMs - (Date.now() - started));
      clearTimeout(luckTimer);
      luckTimer = setTimeout(() => {
        preOverlay.classList.add('hidden'); preOverlay.classList.remove('is-open');
        sheetEl?.removeAttribute('aria-busy');
      }, rest);
    };
  };

  luckBtn?.addEventListener('click', () => {
    const avail = all.filter(el => el.style.display !== 'none' && isDisponible(el) && !selected.has(Number(el.dataset.num)));
    if (!avail.length) return;
    luckBtn.disabled = true;
    const stop = showLuckyPre(1200);
    setTimeout(() => {
      const el = avail[Math.floor(Math.random() * avail.length)];
      toggleNumber(el);
      try { el.scrollIntoView({ behavior:'smooth', block:'center' }); } catch {}
      stop(); setTimeout(() => (luckBtn.disabled = false), 250);
    }, 500);
  });

  // +/- cantidad rápida
  const pickRandomAvailable = () => {
    const avail = all.filter(el => el.style.display !== 'none' && isDisponible(el) && !selected.has(Number(el.dataset.num)));
    return avail.length ? avail[Math.floor(Math.random() * avail.length)] : null;
  };
  incBtn?.addEventListener('click', () => { if (selected.size >= cfg.max) return; const el = pickRandomAvailable(); if (el) toggleNumber(el); });
  decBtn?.addEventListener('click', () => {
    if (!selected.size) return;
    const last = Array.from(selected).pop();
    const btn = last != null ? qs(`.num[data-num="${last}"]`, grid) : null;
    if (btn) toggleNumber(btn);
  });

  // comprar = continuar
  if (buyNowBtn && reserveBtn) buyNowBtn.addEventListener('click', (e) => { e.preventDefault(); reserveBtn.click(); });

  // --------- 🔴 MODAL DE CONFIRMACIÓN: FLUJO RESERVA / PAGO AHORA ---------
  function openModal(nums) {
    if (!overlay) return doReserve(nums);
    if (listEl) {
      listEl.innerHTML = '';
      nums.forEach(n => { const s = document.createElement('span'); s.className = 'chip'; s.textContent = `#${n}`; listEl.appendChild(s); });
    }
    if (sumEl) sumEl.textContent = money(nums.length * cfg.price);
    overlay.classList.remove('hidden');
    document.documentElement.classList.add('overflow-y-hidden');
  }
  function closeModal() { if (!overlay) return; overlay.classList.add('hidden'); document.documentElement.classList.remove('overflow-y-hidden'); }
  modalCloseBtn?.addEventListener('click', closeModal);
  overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

  function buildCheckoutUrlFromPostUrl(postUrl, code) {
    if (!postUrl || !code) return null;
    const m = String(postUrl).match(/^(.*?\/t\/[^/]+)/);
    return m ? `${m[1]}/checkout/${code}` : null;
  }

  // ------------- FLUJO RESERVA Y PAGO AHORA DESDE EL MODAL ----------------
  async function doReserve(nums, extra = null, triggerBtn = null, flowType = 'reserve') {
    if (!cfg.postUrl) { alert('Falta URL para reservar.'); return; }

    const uniq = Array.from(new Set((nums || []).map(n => Number(n))))
      .filter(Number.isFinite)
      .sort((a, b) => a - b);
    if (!uniq.length) { alert('No seleccionaste números.'); return; }

    const btn = triggerBtn || reserveBtn;
    const setLoading = (on) => {
      if (!btn) return;
      btn.disabled = !!on;
      if (on) { btn.dataset._txt = btn.textContent; btn.textContent = 'Reservando…'; }
      else if (btn.dataset._txt) { btn.textContent = btn.dataset._txt; delete btn.dataset._txt; }
    };

    try {
      setLoading(true);

      const params = new URLSearchParams();
      uniq.forEach(n => params.append('numbers[]', String(n)));
      params.append('minutes', String(cfg.minutes));
      params.append('flow', flowType); // <-- USAR el tipo de flujo: reserve | pay
      if (flowType === 'pay') params.append('pay_now', 'true');
      if (extra && typeof extra === 'object') {
        Object.entries(extra).forEach(([k, v]) => {
          if (v != null && String(v).trim() !== '') params.append(k, String(v));
        });
      }

      const res = await fetch(cfg.postUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': cfg.csrf,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json, text/plain, */*',
        },
        credentials: 'same-origin',
        body: params,
      });

      let data = null;
      try { data = await res.clone().json(); }
      catch { data = { ok: false, message: (await res.text())?.slice(0, 400) || null }; }

      if (res.ok && (data?.redirect || data?.url)) {
        try { window.__closeNumbersSheet?.(); } catch {}
        try { document.getElementById('numbersSheet')?.classList.add('hidden'); } catch {}
        window.location.assign(data.redirect || data.url);
        return;
      }

      if (res.ok && (data?.ok || data?.status === 'ok')) {
        const code = data?.code || data?.order_code || data?.reference || null;
        const fallback = buildCheckoutUrlFromPostUrl(cfg.postUrl, code);
        if (fallback) {
          try { window.__closeNumbersSheet?.(); } catch {}
          try { document.getElementById('numbersSheet')?.classList.add('hidden'); } catch {}
          window.location.assign(fallback);
          return;
        }
        alert('Reserva creada. Actualizando…');
        window.location.reload();
        return;
      }

      if (res.status === 409) {
        const conflicts = Array.isArray(data?.conflicts) ? data.conflicts : [];
        if (conflicts.length) {
          conflicts.forEach(n => {
            const b = document.querySelector(`#numbersGrid .num[data-num="${n}"]`);
            if (!b) return;
            selected.delete(Number(n));
            b.setAttribute('aria-pressed', 'false');
            b.classList.remove(
              'bg-[var(--primary)]','text-white','border-[var(--primary)]','ring-2','ring-[var(--primary)]','brightness-110','shadow',
              'bg-white','border-blue-100','text-gray-800','hover:bg-blue-50','cursor-pointer'
            );
            b.disabled = true;
            b.dataset.state = 'reservado';
            b.classList.add('bg-yellow-100','border-yellow-200','text-yellow-700','opacity-80','cursor-not-allowed');
          });
          syncTotals();
          alert(data?.message || `Algunos números ya no están disponibles: ${conflicts.join(', ')}`);
          return;
        }
        alert(data?.message || 'Algunos números ya no están disponibles. Actualizando…');
        window.location.reload();
        return;
      }

      if (res.status === 422) { alert(data?.message || 'Revisa los datos e intenta de nuevo.'); return; }
      if (res.status === 419) { alert('La sesión expiró. Recarga la página e inténtalo de nuevo.'); return; }
      if (res.status === 401) { alert('Debes iniciar sesión para continuar.'); return; }

      if (res.redirected && res.url) {
        try { window.__closeNumbersSheet?.(); } catch {}
        try { document.getElementById('numbersSheet')?.classList.add('hidden'); } catch {}
        window.location.assign(res.url);
        return;
      }

      throw new Error(data?.message || `Error ${res.status || ''}. No fue posible reservar.`);
    } catch (err) {
      alert(err?.message || 'Error de red. Intenta de nuevo.');
    } finally {
      setLoading(false);
    }
  }

  // Checkout embebido (Continuar)
  const sumBox         = document.getElementById('purchaseSummary');
  const inlineCheckout = document.getElementById('inlineCheckout');

  function showPagePre(minMs = 650, title='Armando tu pedido…', sub='Preparando el resumen') {
    let pre = document.getElementById('preCheckout');
    if (!pre) {
      pre = document.createElement('div');
      pre.id = 'preCheckout';
      pre.className = 'pre-anim hidden';
      pre.innerHTML = `
        <div class="pre-anim__card">
          <div class="pre-anim__head">
            <div class="pre-anim__ring"></div>
            <div><div class="pre-anim__title"></div><div class="pre-anim__sub"></div></div>
          </div>
          <div class="pre-anim__bar"><i></i></div>
        </div>`;
      document.body.appendChild(pre);
    }
    pre.querySelector('.pre-anim__title').textContent = title;
    pre.querySelector('.pre-anim__sub').textContent   = sub;
    pre.classList.remove('hidden'); pre.classList.add('is-open');
    const started = Date.now();
    return () => {
      const rest = Math.max(0, minMs - (Date.now() - started));
      setTimeout(() => { pre.classList.add('hidden'); pre.classList.remove('is-open'); }, rest);
    };
  }

  // pinta resumen inline (chips + total)
  (function defineUpdateCheckoutSummary(){
    const chipsWrap   = document.getElementById('sumList'); // chips
    const sumAmountEl = document.getElementById('sumAmount');

    const firstBtn  = grid.querySelector('.num[data-label]');
    const labelLen  = firstBtn ? (firstBtn.dataset.label || '').length : 0;
    const toLabel   = (n) => labelLen ? String(n).padStart(labelLen,'0') : String(n);

    window.updateCheckoutSummary = function() {
      if (!inlineCheckout || !chipsWrap || !sumAmountEl) return;
      const nums = Array.from(selected).sort((a,b)=>a-b);
      chipsWrap.innerHTML = '';
      nums.forEach(n => {
        const chip = document.createElement('span');
        chip.className = 'chip';
        chip.textContent = `#${toLabel(n)}`;
        chipsWrap.appendChild(chip);
      });
      // Actualiza el total en USD
      const totalUsd = nums.length * cfg.price;
      sumAmountEl.textContent = money(totalUsd);

      // === ENVÍA EVENTO PARA ACTUALIZAR EL TOTAL EN BS ===
      const event = new CustomEvent('total-changed', { detail: { usd: totalUsd } });
      window.dispatchEvent(event);
    };
  })();

  // --- Continuar (abrir modal Reservar / Pagar) ---
if (reserveBtn) {
  reserveBtn.addEventListener('click', (e) => {
    e.preventDefault();

    const nums = Array.from(selected).map(Number).sort((a, b) => a - b);
    if (nums.length < cfg.min) { alert(`Debes seleccionar al menos ${cfg.min} números.`); return; }
    if (nums.length > cfg.max) { alert(`Máximo ${cfg.max} números por compra.`); return; }

    // Cierra el bottom-sheet de números para que NO intercepte los clics
    try { window.__closeNumbersSheet?.(); } catch {}
    const sheet = document.getElementById('numbersSheet');
    if (sheet) sheet.classList.add('hidden'); // asegura display:none

    // Abre el modal de reserva/pago ahora
    window.showReservaPagoModal({
      nums,
      total: nums.length * cfg.price, // número puro, no formateado

      // Reserva normal
      onReservar: (datosFormulario) => {
        doReserve(nums, datosFormulario, null, 'reserve');
      },

      onPagar: async () => {
  // 🚩 Aquí hacemos una reserva directa tipo "pago ahora" vía AJAX, para crear la orden y redirigir al checkout
  const nums = Array.from(selected).map(Number).sort((a, b) => a - b);

  if (nums.length < cfg.min) { alert(`Debes seleccionar al menos ${cfg.min} números.`); return; }
  if (nums.length > cfg.max) { alert(`Máximo ${cfg.max} números por compra.`); return; }

  // Llama AJAX a la misma URL de reserva pero con flow "pay"
  const params = new URLSearchParams();
  nums.forEach(n => params.append('numbers[]', String(n)));
  params.append('minutes', String(cfg.minutes));
  params.append('flow', 'pay');
  params.append('pay_now', 'true');

  try {
    const res = await fetch(cfg.postUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': cfg.csrf,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json, text/plain, */*',
      },
      credentials: 'same-origin',
      body: params,
    });

    let data = null;
    try { data = await res.clone().json(); }
    catch { data = { ok: false, message: (await res.text())?.slice(0, 400) || null }; }

    if (res.ok && (data?.redirect || data?.url)) {
      // 🟢 REDIRECCIONA AL CHECKOUT DE LA ORDEN recién creada
      window.location.assign(data.redirect || data.url);
      return;
    }
    if (res.status === 409) {
      alert(data?.message || 'Algunos números ya no están disponibles. Actualizando...');
      window.location.reload();
      return;
    }
    if (res.status === 422) { alert(data?.message || 'Revisa los datos e intenta de nuevo.'); return; }
    if (res.status === 419) { alert('La sesión expiró. Recarga la página e inténtalo de nuevo.'); return; }
    if (res.status === 401) { alert('Debes iniciar sesión para continuar.'); return; }

    throw new Error(data?.message || `Error ${res.status || ''}. No fue posible reservar.`);
  } catch (err) {
    alert(err?.message || 'Error de red. Intenta de nuevo.');
  }
}


    });
  });
}



  // ====== Inline checkout: Confirmar compra ======
  const inlineForm     = document.getElementById('inlinePayForm');
  const inlineBtnPay   = document.getElementById('btnInlinePay');
  const inlineMsg      = document.getElementById('inlineMsg');
  const inlineSuccess  = document.getElementById('inlineSuccess');
  const okOrderCodeEl  = document.getElementById('okOrderCode');

  function showInlineMsg(text, type = 'error') {
    if (!inlineMsg) return;
    inlineMsg.textContent = text || '';
    inlineMsg.className = 'min-h-[32px] py-1 text-base ' +
      (type === 'success' ? 'text-green-700' : 'text-red-600');
  }

  function setPayLoading(on) {
    if (!inlineBtnPay) return;
    inlineBtnPay.disabled = !!on;
    inlineBtnPay.style.opacity = on ? '0.8' : '';
    if (on) {
      inlineBtnPay.dataset._txt = inlineBtnPay.textContent;
      inlineBtnPay.textContent = 'Enviando...';
    } else if (inlineBtnPay.dataset._txt) {
      inlineBtnPay.textContent = inlineBtnPay.dataset._txt;
      delete inlineBtnPay.dataset._txt;
    }
  }

  function markHtmlErrors(form, invalid = []) {
    // Oculta todos los errores
    form?.querySelectorAll('.msg-error').forEach(e => e.hidden = true);
    // Muestra los de los campos inválidos
    invalid.forEach(input => {
      const msg = input.closest('div')?.querySelector('.msg-error');
      if (msg) msg.hidden = false;
    });
  }

  async function submitInlineCheckout(e) {
    e.preventDefault();
    showInlineMsg('');

    if (!inlineForm) return showInlineMsg('No se encontró el formulario.');

    // Validación HTML5
    const invalids = Array.from(inlineForm.querySelectorAll('input,select,textarea'))
      .filter(el => !el.checkValidity());
    markHtmlErrors(inlineForm, invalids);
    if (invalids.length) {
      inlineForm.reportValidity?.();
      return;
    }

    // Números seleccionados
    const selectedNums = Array.from(window.Store?.Selected || []);
    if (!selectedNums.length) {
      return showInlineMsg('Debes seleccionar al menos un número antes de confirmar.');
    }

    // Construir FormData (multipart)
    const fd = new FormData(inlineForm);
    // Adjunta números y minutos
    selectedNums.sort((a,b)=>a-b).forEach(n => fd.append('numbers[]', String(n)));
    fd.append('minutes', String(cfg.minutes));
    fd.append('flow', 'pay');
fd.append('pay_now', 'true');

    try {
      setPayLoading(true);

      const res = await fetch(cfg.postUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': cfg.csrf,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: fd,
        credentials: 'same-origin'
      });

      // Intenta decodificar JSON, si no, texto
      let data = null;
      try { data = await res.clone().json(); } catch { data = { ok:false, message: await res.text() }; }

      // Redirección directa si el backend la envía
      if (res.ok && (data?.redirect || data?.url)) {
    // Para flujo "pay", redirigir directamente al checkout
    if (data.flow === 'pay') {
        window.location.assign(data.redirect || data.url);
        return;
    }
    // Para flujo "reserve", mostrar mensaje
    alert('¡Reserva exitosa! Te enviamos el enlace de pago a tu email. Revisa tu bandeja de entrada.');
    return;
}

      // Estados comunes
      if (res.status === 409) {
        showInlineMsg(data?.message || 'Algunos números ya no están disponibles. Actualizando...');
        setTimeout(() => window.location.reload(), 800);
        return;
      }
      if (res.status === 422) {
        // Errores de validación del backend
        const msg = (data?.message || 'Revisa los datos.') +
                    (data?.errors ? ' ' + Object.values(data.errors).flat().join(' ') : '');
        showInlineMsg(msg);
        return;
      }
      if (res.status === 419) { showInlineMsg('La sesión expiró. Recarga la página e inténtalo de nuevo.'); return; }
      if (res.status === 401) { showInlineMsg('Debes iniciar sesión para continuar.'); return; }

      // Éxito sin redirect
      if (res.ok && (data?.ok || data?.status === 'ok')) {
        const code = data?.order_code || data?.code || data?.reference || '';
        if (okOrderCodeEl) okOrderCodeEl.textContent = code;
        if (inlineSuccess) inlineSuccess.classList.remove('hidden');
        if (inlineForm) inlineForm.classList.add('hidden');
        showInlineMsg('', 'success');
        return;
      }

      // Error genérico
      throw new Error(data?.message || `Error ${res.status || ''}. No fue posible procesar el pago.`);
    } catch (err) {
      showInlineMsg(err.message || 'Error de red. Intenta de nuevo.');
    } finally {
      setPayLoading(false);
    }
  }

  inlineBtnPay?.addEventListener('click', submitInlineCheckout);
  // ====== Fin inline checkout ======

  // estado inicial
  renderPage(1);
  syncTotals();
}
