{{-- resources/views/store/partials/rifa-scripts.blade.php --}}
<script>
(function () {
  // Shortcuts para selectores rápidos
  const qs  = (s, r=document)=>r.querySelector(s);
  const qsa = (s, r=document)=>Array.from(r.querySelectorAll(s));

  // Sheet principal y elementos clave
  const sheet = qs('#numbersSheet');
  const panel = qs('#numbersPanel') || qs('#numbersSheet .panel');
  const back  = qs('#numbersBackdrop') || qs('#numbersSheet + .sheet-backdrop');
  const close = qs('#closeNumbers');
  const triggers = [qs('#goNumbers'), ...qsa('a[href$="#boletos"]')].filter(Boolean);

  // Botón principal para continuar
  const cont  = qs('#btnReserve');
  const mCont = qs('#mContinue');

  function lockScroll(on){
    document.documentElement.classList.toggle('overflow-y-hidden', !!on);
  }

  // --- Loader/Pre-animación ---
  function ensurePre() {
    let pre = qs('#preAnim');
    if (!pre) {
      pre = document.createElement('div');
      pre.id = 'preAnim';
      pre.className = 'pre-anim hidden';
      pre.innerHTML = `
        <div class="pre-card" role="dialog" aria-live="polite" aria-label="Preparando boletos">
          <div class="pre-head">
            <div class="pre-icon" aria-hidden="true">
              <svg viewBox="0 0 48 48" style="width:36px;height:36px;display:block;">
                <path fill="currentColor"
                  d="M8 16c0-2.2 1.8-4 4-4h24c2.2 0 4 1.8 4 4v4a4 4 0 0 0 0 8v4c0 2.2-1.8 4-4 4H12c-2.2 0-4-1.8-4-4v-4a4 4 0 0 0 0-8v-4z"/>
                <path class="dash" d="M24 14v20" />
              </svg>
            </div>
            <div>
              <div class="pre-title">Preparando tus números…</div>
              <div class="pre-sub">Un instante, por favor</div>
            </div>
          </div>
          <div class="pre-bar"><i></i></div>
        </div>`;
      document.body.appendChild(pre);
    }
    return pre;
  }

  const PRE_DURATION_MS = 500;
  const SHEET_ANIM_MS   = 250;

  function showPre() {
    const pre = ensurePre();
    pre.classList.remove('hidden');
    lockScroll(true);
    return pre;
  }

  function hidePre() {
    const pre = qs('#preAnim');
    if (!pre) return;
    pre.classList.add('hidden');
    if (!sheet || sheet.classList.contains('hidden')) {
      lockScroll(false);
    }
  }

  // --- Abrir/Cerrar Sheet ---
  function openSheet() {
    if (!sheet) return;
    sheet.classList.remove('hidden');
    back  && back.classList.add('opacity-0');
    panel && panel.classList.add('translate-y-full');
    requestAnimationFrame(() => {
      back  && back.classList.remove('opacity-0');
      panel && panel.classList.remove('translate-y-full');
      lockScroll(true);
      if (close) setTimeout(() => { try { close.focus({ preventScroll:true }); } catch {} }, SHEET_ANIM_MS + 20);
    });
  }

  function closeSheet() {
    if (!sheet) return;
    back  && back.classList.add('opacity-0');
    panel && panel.classList.add('translate-y-full');
    setTimeout(() => {
      sheet.classList.add('hidden');
      lockScroll(false);
      if (location.hash === '#boletos') {
        try { history.replaceState(null, '', location.pathname + location.search); } catch {}
      }
    }, SHEET_ANIM_MS);
  }

  // --- Eventos ---
  triggers.forEach(t => {
    t.addEventListener('click', (e) => {
      e.preventDefault();
      showPre();
      setTimeout(() => {
        openSheet();
        setTimeout(() => { hidePre(); }, 200);
        try { history.replaceState(null, '', location.pathname + location.search + '#boletos'); } catch {}
      }, PRE_DURATION_MS);
    });
  });

  close && close.addEventListener('click', closeSheet);
  back  && back.addEventListener('click', closeSheet);
  window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeSheet(); });

  if (location.hash === '#boletos') {
    setTimeout(() => { openSheet(); }, 80);
  }

  mCont && cont && mCont.addEventListener('click', (e) => { e.preventDefault(); cont.click(); });
})();
</script>
