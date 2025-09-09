// resources/js/store/checkout.js
import { firstById } from './helpers';

export function initCheckoutPage() {
  // Botón "Cambiar" (abrir sheet de números si existe)
  
  const changeBtn = document.getElementById('changeSelection') || document.getElementById('btnChangeSelection');
  changeBtn?.addEventListener('click', (e) => {
    e.preventDefault();
    try { window.__openNumbersSheet?.(changeBtn); } catch {}
    setTimeout(() => document.getElementById('searchNumber')?.focus(), 60);
  });

  // Select de pago + voucher (mostrar/ocultar)
  const select = firstById('paymentSelect') || firstById('payment_account_id') || document.querySelector('input[name="payment_account_id"]');
  const help   = firstById('paymentHelp');
  const wrap   = firstById('voucherWrap');
  if (select) {
    const onChange = () => {
      const opt = (select.tagName === 'SELECT') ? select.selectedOptions[0] : null;
      if (!opt) return;
      const requires = opt.dataset.requires === '1';
      if (help) help.textContent = requires
        ? 'Este método requiere subir comprobante.'
        : 'No requiere comprobante; puedes adjuntarlo si lo deseas.';
      if (wrap) wrap.classList.toggle('hidden', !requires);
    };
    select.addEventListener('change', onChange);
    onChange();
  }

  // Countdown (si existe)
  const cd = firstById('countdown');
  if (cd?.dataset.exp) {
    const end = parseInt(cd.dataset.exp, 10) * 1000;
    const tick = () => {
      const s = Math.max(0, Math.floor((end - Date.now()) / 1000));
      const m = Math.floor(s / 60);
      const ss = String(s % 60).padStart(2, '0');
      cd.textContent = `${m}:${ss}`;
    };
    tick(); setInterval(tick, 1000);
  }

  // Mostrar card si venía selección (historial)
  const inlineCheckout = document.getElementById('inlineCheckout');
  if (inlineCheckout && window.Store?.Selected && window.Store.Selected.size > 0) {
    inlineCheckout.classList.remove('hidden');
    try { window.updateCheckoutSummary?.(); } catch {}
  }

  // ---------- PAGO: submit del formulario ----------
  const form          = document.getElementById('inlinePayForm');
  const btnSubmit     = document.getElementById('btnInlinePay');
  const inlineMsg     = document.getElementById('inlineMsg');
  const inlineSuccess = document.getElementById('inlineSuccess');
  const okOrderCodeEl = document.getElementById('okOrderCode');

  if (!form) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  function showInlineMsg(text, type = 'error') {
    if (!inlineMsg) return;
    inlineMsg.textContent = text || '';
    inlineMsg.className = 'min-h-[32px] py-1 text-base ' +
      (type === 'success' ? 'text-green-700' : 'text-red-600');
  }

  function setLoading(on) {
    if (!btnSubmit) return;
    btnSubmit.disabled = !!on;
    btnSubmit.style.opacity = on ? '0.8' : '';
    if (on) {
      btnSubmit.dataset._txt = btnSubmit.textContent;
      btnSubmit.textContent = 'Enviando...';
    } else if (btnSubmit.dataset._txt) {
      btnSubmit.textContent = btnSubmit.dataset._txt;
      delete btnSubmit.dataset._txt;
    }
  }

  function markHtmlErrors(formEl, invalid = []) {
    formEl?.querySelectorAll('.msg-error').forEach(e => e.hidden = true);
    invalid.forEach(input => {
      const msg = input.closest('div')?.querySelector('.msg-error');
      if (msg) msg.hidden = false;
    });
  }

  // === ÚNICA FUNCIÓN PARA CONSTRUIR LA URL DE PAGO ===
  function buildPayUrl() {
    // Siempre prioriza los data-attributes del form
    const tenantSlug = form.dataset.tenantSlug;
    const orderCode = form.dataset.orderCode;
    if (tenantSlug && orderCode && orderCode.length >= 3) {
      const url = `/t/${tenantSlug}/checkout/${orderCode}/pagar`;
      form.dataset.payUrl = url;
      return url;
    }
    // Fallback por si acaso: intenta extraerlos de la URL si no están en el form (muy raro)
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    if (pathParts[0] === 't' && pathParts[2] === 'checkout' && pathParts[3]) {
      const url = `/t/${pathParts[1]}/checkout/${pathParts[3]}/pagar`;
      form.dataset.payUrl = url;
      return url;
    }
    // Error: no hay manera segura de construir la URL
    return '';
  }

  // Importante: el botón es type="button", así que disparamos el submit manual
  btnSubmit?.addEventListener('click', (e) => {
    e.preventDefault();
    form.requestSubmit ? form.requestSubmit() : form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true })); 
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    showInlineMsg('');

    // Validación HTML5
    const invalids = Array.from(form.querySelectorAll('input,select,textarea'))
      .filter(el => !el.checkValidity());
    markHtmlErrors(form, invalids);
    if (invalids.length) {
      form.reportValidity?.();
      return;
    }

    // Construir la URL de pago
    const payUrl = buildPayUrl();
    if (!payUrl || payUrl.trim() === '') {
      console.error('No se pudo determinar la URL de pago');
      showInlineMsg('Error: No se pudo determinar la URL de procesamiento. Por favor, recarga la página.');
      return;
    }

    try {
      setLoading(true);
      const fd = new FormData(form);

      const res = await fetch(payUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf || '',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: fd,
        credentials: 'same-origin'
      });

      let data = null;
      try { 
        data = await res.clone().json(); 
      } catch { 
        const text = await res.text();
        console.error('Respuesta no es JSON:', text);
        data = { ok: false, message: text || 'Error procesando la respuesta' }; 
      }

      // Verificar si el pago fue exitoso (más flexible)
      const isSuccess = res.ok && (
        data?.ok === true || 
        data?.status === 'ok' || 
        data?.success === true ||
        data?.status === 'success' ||
        (res.status === 200 && !data?.error)
      );

      if (isSuccess) {
        // Mostrar SweetAlert si está disponible
        if (typeof Swal !== 'undefined') {
          await Swal.fire({
            icon: 'success',
            title: '¡Pago enviado!',
            text: data?.message || 'Tu pago ha sido recibido y será verificado pronto.',
            showConfirmButton: false,
            timer: 2500,
            background: '#f0fdf4',
            color: '#166534',
            customClass: { popup: 'animate__animated animate__fadeInDown' }
          });
        } else {
          // Si no hay SweetAlert, mostrar mensaje normal
          showInlineMsg('¡Pago procesado exitosamente!', 'success');
        }

        // Mostrar sección de éxito y ocultar formulario
        if (inlineSuccess) {
          inlineSuccess.classList.remove('hidden');
          form.classList.add('hidden');
        }

        // Mostrar código de orden
        if (okOrderCodeEl) {
          const orderCode = data?.order_code || data?.code || form.dataset.orderCode || '';
          okOrderCodeEl.textContent = orderCode;
        }

        // Manejar redirección si existe
        if (data?.redirect_url) {
          setTimeout(() => {
            window.location.href = data.redirect_url;
          }, 3000);
        } else if (data?.order_code || data?.code) {
          // Intentar construir URL de confirmación
          const orderCode = data.order_code || data.code;
          const tenantSlug = form.dataset.tenantSlug || window.location.pathname.split('/')[2];
          if (tenantSlug && orderCode) {
            const confirmUrl = `/t/${tenantSlug}/checkout/${orderCode}/confirmacion`;
            setTimeout(() => {
              window.location.href = confirmUrl;
            }, 3000);
          }
        }

        return;
      }

      // Manejo de errores específicos
      if (res.status === 422) {
        const errorMsg = data?.message || 'Por favor revisa los datos ingresados.';
        const fieldErrors = data?.errors ? Object.values(data.errors).flat().join('. ') : '';
        showInlineMsg(errorMsg + (fieldErrors ? ' ' + fieldErrors : ''));
        return;
      }

      if (res.status === 409) {
        showInlineMsg(data?.message || 'La reserva ya venció o la orden no es válida.');
        return;
      }

      if (res.status === 404) {
        showInlineMsg('La página de procesamiento no fue encontrada. Por favor, contacta soporte.');
        return;
      }

      // Error genérico
      const errorMessage = data?.message || `Error ${res.status}. No fue posible procesar el pago.`;
      showInlineMsg(errorMessage);

    } catch (err) {
      console.error('Error en el proceso de pago:', err);
      showInlineMsg('Error de conexión. Por favor verifica tu conexión e intenta nuevamente.');
    } finally {
      setLoading(false);
    }
  });
}
