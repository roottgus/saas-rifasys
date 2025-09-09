{{-- Modal Reserva o Pagar Ahora --}}
<div id="reservaPagoModal" class="fixed inset-0 z-[120] hidden opacity-0 transition-opacity duration-200 modal-bg" style="background:rgba(30,34,58,0.68); backdrop-filter: blur(3px);">
  <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-0 w-full max-w-md relative animate-fadein-up scale-98 modal-content"
      style="animation:fadeInUp 0.22s cubic-bezier(.16,1,.3,1);">
      {{-- Cerrar (X) --}}
      <button type="button" class="modal-close absolute right-3 top-3 p-2 text-gray-400 hover:text-red-500 rounded-full transition">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M6 6l12 12M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </button>

      {{-- Encabezado --}}
      <div class="px-7 pt-6 pb-2 flex flex-col items-center">
        <div class="flex items-center gap-2 mb-2">
          <span class="bg-yellow-100 text-yellow-800 rounded-full px-2 py-1 text-xs font-bold flex items-center gap-1">
            <i class="fa-solid fa-clock text-yellow-500"></i> Elige cómo finalizar
          </span>
        </div>
        <h2 class="text-xl sm:text-2xl font-extrabold text-gray-800 mb-0.5 text-center uppercase tracking-wide">¿Qué deseas hacer?</h2>
        <p class="text-base text-gray-600 mb-0.5 text-center max-w-sm">
          Puedes <span class="font-bold text-yellow-700">Reservar</span> tus números <b>4 horas</b> o <span class="font-bold text-blue-700">Pagar ahora</span> y asegurar tu participación.
        </p>
      </div>

      {{-- Selección y total --}}
      <div class="px-7 flex flex-col items-center justify-center gap-2 mb-2">
        <div class="flex flex-wrap gap-1 items-center justify-center max-w-xs" id="modalNums"></div>
        <div class="text-lg font-bold text-blue-700">
          Total: <span id="modalSum">$0.00</span>
        </div>
        <div id="modalMsg" class="text-xs text-yellow-700 text-center mt-1 mb-2"></div>
      </div>

      {{-- BOTONES y FORM --}}
      <div id="modalBtnsBox" class="flex flex-col sm:flex-row gap-3 px-7 pb-6">
        <button id="btnModalReservar" type="button"
          class="w-full py-3 px-2 rounded-xl font-extrabold bg-gradient-to-b from-yellow-200 to-yellow-500 text-yellow-900 border border-yellow-400 shadow hover:brightness-105 transition flex-1">
          <i class="fa-solid fa-clock mr-1"></i> Reservar por 4 horas
        </button>
        <button id="btnModalPagar" type="button"
          class="w-full py-3 px-2 rounded-xl font-extrabold bg-gradient-to-b from-blue-700 to-blue-900 text-white border border-blue-800 shadow hover:brightness-110 transition flex-1">
          <i class="fa-solid fa-bolt mr-1"></i> Pagar ahora
        </button>
      </div>

      {{-- Formulario de reserva (incrustado, solo se muestra si das a Reservar) --}}
      <div id="reservaFormBox" class="w-full max-w-md mx-auto bg-white rounded-2xl px-6 pt-2 pb-6 flex flex-col gap-5 hidden">
        <form id="formReserva" class="flex flex-col gap-4">
          <div>
            <label class="block font-bold mb-1 text-gray-800">Nombre completo <span class="text-red-500">*</span></label>
            <input type="text" name="nombre" required class="input input-bordered w-full" placeholder="Ej: Juan Pérez">
          </div>

          {{-- WhatsApp (intl-tel-input con banderas) --}}
          <div>
            <label class="block font-bold mb-1 text-gray-800">
              WhatsApp <span class="text-red-500">*</span>
            </label>

            <input id="iti_whatsapp_reserva"
                   type="tel"
                   class="input input-bordered w-full"
                   placeholder="Número WhatsApp"
                   inputmode="tel"
                   autocomplete="tel"
                   required>

            {{-- Campos ocultos para backend (mismo contrato que ya tenías) --}}
            <input type="hidden" name="cc" id="res_cc">                 {{-- +58 --}}
            <input type="hidden" name="whatsapp" id="res_whatsapp">     {{-- 4140000000 (solo dígitos) --}}
            <input type="hidden" name="whatsapp_full" id="res_whatsapp_full"> {{-- +58 4140000000 --}}

            {{-- Ayuda / vista previa --}}
            <p class="text-xs text-gray-500 mt-1">
              Se guardará como
              <span id="res_wpp_preview" class="font-semibold text-gray-700">+58 __________</span>.
            </p>

            {{-- Error opcional --}}
            <p id="res_wpp_error" class="text-xs text-red-600 mt-1 hidden">Ingresa un WhatsApp válido.</p>
          </div>

          <div>
            <label class="block font-bold mb-1 text-gray-800">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" required class="input input-bordered w-full" placeholder="Ej: correo@ejemplo.com">
          </div>

          <div>
            <label class="block font-bold mb-1 text-gray-800">¿Cómo prefieres que te contactemos? <span class="text-red-500">*</span></label>
            <div class="flex gap-4 mt-1">
              <label class="flex items-center gap-2">
                <input type="radio" name="preferencia" value="whatsapp" required class="form-radio">
                <span>WhatsApp</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="radio" name="preferencia" value="email" required class="form-radio">
                <span>Email</span>
              </label>
            </div>
          </div>

          <div id="msgReserva" class="min-h-[32px] text-base text-center text-red-600"></div>
          <button type="submit"
                  id="btnReservarFinal"
                  class="w-full h-12 mt-1 rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 font-black text-lg text-black shadow-lg hover:brightness-110 transition">
            Reservar mis boletos
          </button>
        </form>
      </div>
    </div>
  </div>

  <style>
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(22px) scale(0.97);}
      100% { opacity: 1; transform: translateY(0) scale(1);}
    }
    .scale-98 { transform: scale(.98);}
    .modal-bg { transition: opacity .22s cubic-bezier(.16,1,.3,1);}
    .modal-content { animation: fadeInUp 0.22s cubic-bezier(.16,1,.3,1);}
  </style>
</div>

<!-- Assets intl-tel-input (usa estos si no están ya en tu layout) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js"></script>

<script>
  (function initReservaPhone(){
    function onReady(cb){ if(document.readyState!=='loading') cb(); else document.addEventListener('DOMContentLoaded', cb); }
    onReady(function(){
      var input = document.getElementById('iti_whatsapp_reserva');
      if(!input || !window.intlTelInput) return;

      var iti = window.intlTelInput(input, {
        initialCountry: 've',
        preferredCountries: ['ve','co','ec','pe','cl','ar','mx','us','pa','br','es'],
        separateDialCode: true,
        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js',
      });

      var cc = document.getElementById('res_cc');
      var local = document.getElementById('res_whatsapp');
      var full = document.getElementById('res_whatsapp_full');
      var prev = document.getElementById('res_wpp_preview');
      var err  = document.getElementById('res_wpp_error');

      function sync(){
        var c = iti.getSelectedCountryData();
        var dial = c && c.dialCode ? '+' + c.dialCode : '';
        var digits = (input.value || '').replace(/[^\d]/g,'');
        if(cc)   cc.value   = dial;
        if(local) local.value = digits;
        if(full) full.value = (dial + ' ' + digits).trim();
        if(prev) prev.textContent = full.value || (dial + ' __________');
        if(err)  err.classList.add('hidden'); // ocultamos error al teclear
      }

      input.addEventListener('countrychange', sync);
      input.addEventListener('input', sync);
      sync();

      // Validación opcional al enviar
      var form = document.getElementById('formReserva');
      if(form){
        form.addEventListener('submit', function(e){
          // Si la librería dice que no es válido, mostramos error suave y cancelamos
          if(!iti.isValidNumber()){
            e.preventDefault();
            if(err) err.classList.remove('hidden');
            input.focus();
          }
        });
      }

      // Si el modal se abre oculto, refrescamos ancho/lugar de bandera al mostrar
      var modal = document.getElementById('reservaPagoModal');
      if(modal){
        var observer = new MutationObserver(function(){
          if(getComputedStyle(modal).display !== 'none'){
            // Pequeño truco para recalcular layout
            setTimeout(function(){ window.dispatchEvent(new Event('resize')); }, 60);
          }
        });
        observer.observe(modal, { attributes:true, attributeFilter:['class','style'] });
      }
    });
  })();
</script>