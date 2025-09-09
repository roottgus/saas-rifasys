@php
  // ===== Tenant & slug =====
  $__tenant = (isset($tenant) && $tenant instanceof \App\Models\Tenant)
      ? $tenant
      : (request()->route('tenant') instanceof \App\Models\Tenant ? request()->route('tenant') : null);
  $tSlug = $tSlug ?? ($__tenant?->slug ?? null);

  // ===== 1) Usa lo que viene del controller (si vino) =====
  $accountsForJs = collect($accountsForJs ?? []);

  // ===== 2) Fallback: SIEMPRE reconstruir (soporte array y modelo) =====
  if ($accountsForJs->isEmpty() && isset($paymentAccounts) && count($paymentAccounts)) {
      $accountsForJs = collect($paymentAccounts)->map(function ($acc) {
          // Si es array plano (como el tuyo)
          $isArray = is_array($acc);

          $usdRaw  = $isArray ? ($acc['usd_enabled'] ?? null) : (method_exists($acc, 'getRawOriginal') ? $acc->getRawOriginal('usd_enabled') : ($acc->usd_enabled ?? null));
          $bsRaw   = $isArray ? ($acc['bs_enabled']  ?? null) : (method_exists($acc, 'getRawOriginal') ? $acc->getRawOriginal('bs_enabled')  : ($acc->bs_enabled  ?? null));
          $tasaRaw = $isArray ? ($acc['tasa_bs']     ?? null) : (method_exists($acc, 'getRawOriginal') ? $acc->getRawOriginal('tasa_bs')     : ($acc->tasa_bs     ?? null));

          $usd = ((int)$usdRaw) === 1;
          $bs  = ((int)$bsRaw)  === 1;
          $tx  = (isset($tasaRaw) && $tasaRaw !== '' && is_numeric($tasaRaw)) ? (float)$tasaRaw : null;

          return [
              'id'               => $isArray ? $acc['id'] : $acc->id,
              'logo'             => $isArray
                                      ? (isset($acc['logo']) && $acc['logo'] ? asset('storage/' . ltrim($acc['logo'], '/')) : null)
                                      : ($acc->logo ? asset('storage/' . ltrim($acc->logo, '/')) : null),
              'etiqueta'         => $isArray ? $acc['etiqueta'] : $acc->etiqueta,
              'banco'            => $isArray ? $acc['banco'] : $acc->banco,
              'numero'           => $isArray ? $acc['numero'] : $acc->numero,
              'iban'             => $isArray ? $acc['iban'] : $acc->iban,
              'titular'          => $isArray ? $acc['titular'] : $acc->titular,
              'documento'        => $isArray ? $acc['documento'] : $acc->documento,
              'email'            => $isArray ? $acc['email'] : $acc->email,
              'wallet'           => $isArray ? $acc['wallet'] : $acc->wallet,
              'red'              => $isArray ? $acc['red'] : $acc->red,
              'notes'            => $isArray ? $acc['notes'] : $acc->notes,
              'requiere_voucher' => $isArray
                                      ? (bool) ($acc['requiere_voucher'] ?? false)
                                      : (bool) ($acc->requiere_voucher ?? false),

              'usd_enabled'      => $usd,
              'bs_enabled'       => $bs,
              'tasa_bs'          => $tx,
              'can_bs'           => $bs && $tx !== null && $tx > 0,

              'accepted_currencies' => array_values(array_filter([
                  $usd ? 'USD' : null,
                  ($bs && $tx !== null && $tx > 0) ? 'VES' : null,
              ])),
          ];
      })->values();
  }

  // ===== 3) Selección inicial =====
  $firstId = $selectedAccountId
      ?? optional($accountsForJs->firstWhere('can_bs', true))['id']
      ?? optional($accountsForJs->firstWhere('usd_enabled', true))['id']
      ?? optional($accountsForJs->first())['id']
      ?? '';

  // ===== 4) URLs =====
  $orderCode = (isset($order) && $order && $order->code) ? $order->code : ($rifa->slug ?? null);
  $payUrl    = ($tSlug && $orderCode && isset($order) && $order)
      ? url("/t/{$tSlug}/checkout/{$orderCode}/pagar")
      : '';
@endphp

<div
  id="inlineCheckout"
  class="mt-8 mx-auto w-full max-w-3xl xl:max-w-4xl hidden rounded-2xl border border-gray-200 bg-white/95 backdrop-blur-sm shadow-[0_6px_40px_rgba(32,42,62,.14),0_2px_12px_rgba(11,18,32,.16)]"
>
  {{-- HEADER --}}
  <div class="relative overflow-hidden rounded-t-2xl">
    <div class="h-1.5 w-full" style="background:var(--primary)"></div>
    <div class="px-6 pt-6 pb-2 text-center">
      <h2 class="mt-1 text-2xl font-black tracking-wide text-[var(--primary)] uppercase">
        Finaliza tu compra
      </h2>

      {{-- Chips de selección --}}
      <div class="mt-2 flex flex-col items-center gap-2">
        <span class="text-xs font-semibold uppercase tracking-widest text-gray-500">Tu selección</span>
        <div class="mt-1 flex min-h-[32px] flex-wrap items-center justify-center gap-2">
          <div id="sumList" class="chips-scroll flex max-w-[340px] flex-wrap gap-1 overflow-x-auto">
            @if(isset($order) && $order && $order->items)
              @foreach($order->items->pluck('numero')->sort() as $num)
                <span class="pill bg-white border border-blue-200 px-3 py-1 text-blue-800 font-bold shadow-sm text-base">
                  #{{ str_pad($num, 3, '0', STR_PAD_LEFT) }}
                </span>
              @endforeach
            @endif
          </div>

          @if(!isset($order) || !$order)
            <button id="changeSelection" type="button" aria-label="Modificar selección"
              class="ml-1 inline-flex rounded-full bg-white p-1 text-[var(--primary)] shadow ring-1 ring-gray-200 transition hover:bg-[var(--primary)]/10 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
              <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M7 7h10l-4-4 1.41-1.41L21.83 9l-7.42 7.41L13 15l4-4H7V7Zm10 10H7l4 4-1.41 1.41L2.17 15l7.42-7.41L11 9l-4 4h10v4Z"/></svg>
            </button>
          @endif
        </div>
      </div>

      {{-- TOTAL (USD) EN HEADER: dinámico --}}
      <div class="mt-2 flex flex-col items-center gap-1"
           x-data="{ usd: {{ $order?->total_amount ? (float) $order->total_amount : 0 }} }"
           @total-changed.window="usd = Number($event.detail?.usd || 0)">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total</span>
        <span id="sumAmount" class="text-2xl font-black text-yellow-600" x-text="`$${(usd||0).toFixed(2)}`">
          ${{ $order?->total_amount ? number_format($order->total_amount, 2) : '0.00' }}
        </span>
      </div>
    </div>
  </div>

  {{-- FORMULARIO --}}
  <form
  id="inlinePayForm"
  class="p-4 sm:p-8"
  enctype="multipart/form-data"
  novalidate
  action="{{ $payUrl ?: '#' }}"
  data-pay-url="{{ $payUrl }}"
  data-tenant-slug="{{ $tSlug ?? '' }}"
  data-order-code="{{ $orderCode ?? '' }}"
>
  {{-- DATOS DEL CLIENTE --}}
  <div class="grid gap-4 md:grid-cols-2">
    {{-- Nombre --}}
    <div class="relative mb-2 flex flex-col gap-1">
      <label for="customer_name" class="mb-1 text-sm font-semibold uppercase tracking-wide text-gray-900">
        Nombre y apellido <span class="ml-1 font-bold text-red-500">*</span>
      </label>
      <span class="pointer-events-none absolute left-3 top-[38px] h-5 w-5 text-[var(--primary)] opacity-70">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4 0-8 2-8 6v2h16v-2c0-4-4-6-8-6Z"/></svg>
      </span>
      <input id="customer_name" name="customer_name" autocomplete="name" placeholder="Ej. María Gómez" required
        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 pl-10 text-base text-gray-900 transition focus:outline-none focus:ring-2 focus:ring-[var(--primary)]"
        value="{{ old('customer_name', $order->customer_name ?? '') }}"
      />
      <p class="msg-error mt-1 text-xs font-bold text-red-600" hidden>Ingresa tu nombre completo.</p>
    </div>

    {{-- WhatsApp --}}
    <div class="relative mb-2 flex flex-col gap-1">
      <label class="mb-1 text-sm font-semibold uppercase tracking-wide text-gray-900">
        WhatsApp <span class="ml-1 font-bold text-red-500">*</span>
      </label>
      <input id="iti_whatsapp" type="tel" placeholder="Número WhatsApp" autocomplete="tel" required
        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-base text-gray-900 transition focus:outline-none focus:ring-2 focus:ring-[var(--primary)]"
        value="{{ old('customer_whatsapp', $order->customer_phone ?? '') }}"
      />
      <input type="hidden" name="country_code" id="country_code">
      <input type="hidden" name="customer_whatsapp" id="customer_whatsapp">
      <p class="mt-1 text-xs text-gray-400">Elige el país y escribe tu número. Ej: 4121234567</p>
      <p class="msg-error mt-1 text-xs font-bold text-red-600" hidden>Ingresa un WhatsApp válido.</p>
    </div>

    {{-- Email --}}
    <div class="md:col-span-2 relative mb-2 flex flex-col gap-1">
      <label for="customer_email" class="mb-1 text-sm font-semibold uppercase tracking-wide text-gray-900">
        Email <span class="ml-1 font-bold text-red-500">*</span>
      </label>
      <span class="pointer-events-none absolute left-3 top-[38px] h-5 w-5 text-[var(--primary)] opacity-70">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5-8-5V6l8 5 8-5Z"/></svg>
      </span>
      <input id="customer_email" type="email" name="customer_email" autocomplete="email" placeholder="tu@correo.com" required
        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 pl-10 text-base text-gray-900 transition focus:outline-none focus:ring-2 focus:ring-[var(--primary)]"
        value="{{ old('customer_email', $order->customer_email ?? '') }}"
      />
      <p class="mt-1 text-xs text-gray-400">Te enviaremos aquí tu confirmación.</p>
      <p class="msg-error mt-1 text-xs font-bold text-red-600" hidden>Ingresa un correo válido.</p>
    </div>
  </div>

  {{-- METODOS DE PAGO --}}
  <div
    x-data="{
      accounts: @js($accountsForJs->values()->all()),
      selected: @js((string) $firstId),
      showVoucher: false,
      usd: {{ $order?->total_amount ? (float) $order->total_amount : 10 }},
      tasaBs: null,
      isBsEnabled: false,
      isUsdEnabled: true,

      select(id) {
        this.selected = String(id);
        const acc = this.accounts.find(a => String(a.id) === String(this.selected));
        if (!acc) return;

        this.showVoucher  = !!acc.requiere_voucher;
        this.isBsEnabled  = !!acc.bs_enabled;
        this.isUsdEnabled = !!acc.usd_enabled;
        this.tasaBs       = acc.tasa_bs ? Number(acc.tasa_bs) : null;

        if (this.$refs.paymentInput) {
          this.$refs.paymentInput.value = this.selected;
          this.$refs.paymentInput.dispatchEvent(new Event('change'));
        }
      }
    }"
    x-init="if (selected && accounts.length) { select(selected); }"
    @total-changed.window="usd = Number($event.detail?.usd || 0)"
    class="mt-4 flex flex-col gap-3"
  >

    {{-- Selector de métodos --}}
    <div class="flex flex-col items-center">
      <span class="mb-1 inline-flex items-center gap-2 text-lg font-black text-[var(--primary)]">
        <i class="fa-solid fa-credit-card" aria-hidden="true"></i>
        <span>Métodos de pago</span>
      </span>
      <span class="mb-2 text-xs tracking-wider text-gray-600">Selecciona cómo quieres pagar</span>
    </div>
    <div class="-mx-1 overflow-x-auto py-2">
      <div class="flex justify-center gap-4 px-1" role="radiogroup" aria-label="Métodos de pago">
        <template x-for="acc in accounts" :key="acc.id">
          <label class="relative inline-flex h-16 w-16 cursor-pointer select-none items-center justify-center rounded-xl border bg-gradient-to-t from-blue-50 via-white to-white shadow transition-all duration-300 focus-within:ring-2 focus-within:ring-[var(--primary)]"
            :class="selected == acc.id ? 'scale-110 border-[var(--primary)] ring-2 ring-yellow-400 shadow-xl z-10' : 'border-gray-200 hover:border-[var(--primary)] hover:ring-2 hover:ring-blue-300 opacity-90'">
            <input type="radio" class="sr-only" name="payment_logo_choice" :value="acc.id"
                   @change="select(acc.id)" :checked="selected == acc.id" :aria-checked="selected == acc.id">
            <template x-if="acc.logo">
              <img :src="acc.logo" alt="Logo método de pago" class="mb-1 h-11 w-11 object-contain drop-shadow" loading="lazy">
            </template>
            <template x-if="!acc.logo">
              <span class="mb-1 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4H2V7Zm0 6h22v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4Z"/></svg>
              </span>
            </template>
          </label>
        </template>
      </div>
    </div>
    <input x-ref="paymentInput" type="hidden" name="payment_account_id" required :value="selected" />

    {{-- Datos y totales --}}
    <template x-if="selected">
      <div class="animate-fadein mt-2 flex flex-col items-center gap-1" x-cloak>
        <template x-for="acc in accounts" :key="'data'+acc.id">
          <div x-show="String(acc.id) === String(selected)" class="text-center">
            <template x-if="acc.banco">
              <div class="mb-0.5 text-sm font-semibold text-blue-700" x-text="acc.banco"></div>
            </template>
            <template x-if="acc.numero">
              <div class="mb-1 flex w-full items-center justify-center gap-2">
                <span class="select-all rounded-xl bg-gray-100 px-4 py-2 font-mono text-xl tracking-wider text-gray-900 shadow-sm">
                  <span x-text="acc.numero"></span>
                </span>
                <button type="button" @click="navigator.clipboard.writeText(acc.numero)" title="Copiar"
                  class="rounded-full border border-yellow-400 bg-yellow-100 p-2 text-yellow-700 shadow transition hover:bg-yellow-200">
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4a2 2 0 0 0-2 2v12h2V3h12V1Zm3 4H8a2 2 0 0 0 2 2v14l4-4h9a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Z"/></svg>
                </button>
              </div>
            </template>
            <div class="mt-0.5 flex flex-col items-center text-[15px]">
              <template x-if="acc.titular">
                <span class="leading-tight font-semibold text-gray-900" x-text="acc.titular"></span>
              </template>
              <template x-if="acc.documento">
                <span class="leading-tight text-xs text-blue-800" x-text="'CI/RIF: ' + acc.documento"></span>
              </template>
            </div>
            <template x-if="acc.notes">
              <div class="mt-1 text-center text-xs font-semibold text-yellow-800" x-text="acc.notes"></div>
            </template>
            <div class="mt-3 flex flex-col items-center gap-1">
              <template x-if="isBsEnabled && Number(tasaBs) > 0">
                <div class="flex flex-col items-center">
                  <span class="inline-flex items-center rounded-full bg-green-100 px-4 py-2 text-sm font-bold text-green-800 border border-green-300">
                    <span class="mr-1">Total en Bs:</span>
                    <span x-text="new Intl.NumberFormat('es-VE', {minimumFractionDigits: 2,maximumFractionDigits: 2}).format((Number(usd)||0) * Number(tasaBs))"></span>
                  </span>
                  <span class="mt-1 text-xs text-gray-600"
                        x-text="`Tasa: Bs. ${Number(tasaBs).toLocaleString('es-VE', { minimumFractionDigits: 2 })} por USD`"></span>
                </div>
              </template>
              <template x-if="isUsdEnabled">
                <div class="flex flex-col items-center">
                  <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-sm font-extrabold text-yellow-700">
                    <span class="mr-1">Total:</span>
                    <span x-text="new Intl.NumberFormat('en-US',{style:'currency',currency:'USD'}).format(Number(usd)||0)"></span>
                  </span>
                </div>
              </template>
            </div>
          </div>
        </template>
      </div>
    </template>

    {{-- COMPROBANTE DE PAGO (100% obligatorio cuando lo requiere) --}}
    <div x-show="showVoucher" x-transition.opacity>
      <div x-data="{
        file:null, fileUrl:null, error:null,
        clearFile(){ this.file=null; this.fileUrl=null; this.$refs.voucherInput.value=''; },
        handleFile(e){
          this.error=null; const f=e.target.files[0]; if(!f) return this.clearFile();
          if(f.size>5*1024*1024){ this.error='El archivo es muy grande (máx 5MB)'; return this.clearFile(); }
          if(!['image/jpeg','image/png','image/webp','application/pdf'].includes(f.type)){
            this.error='Formato no permitido'; return this.clearFile();
          }
          this.file=f; this.fileUrl = f.type==='application/pdf' ? '/img/pdf-icon.png' : URL.createObjectURL(f);
        }
      }">
        <div class="flex flex-col items-center">
          <span class="mb-2 inline-flex items-center gap-2 text-lg font-black text-red-600">
            <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
            <span>COMPROBANTE DE PAGO</span>
          </span>
          <div class="w-full max-w-md">
            <label for="voucherInput" class="relative block w-full cursor-pointer rounded-xl border-2 border-dashed border-red-400 bg-red-50 px-4 py-4 text-center font-semibold text-gray-700 transition hover:bg-red-100"
              :class="error ? 'border-red-500 bg-red-100' : ''">
              <input x-ref="voucherInput" id="voucherInput" class="hidden" type="file" name="voucher" accept=".jpg,.jpeg,.png,.webp,.pdf" @change="handleFile" required>
              <template x-if="fileUrl">
                <div class="flex flex-col items-center gap-2">
                  <template x-if="file && file.type==='application/pdf'">
                    <img :src="fileUrl" alt="PDF" class="h-32 rounded object-contain shadow" />
                  </template>
                  <template x-if="file && file.type!=='application/pdf'">
                    <img :src="fileUrl" alt="Comprobante" class="max-h-40 max-w-xs rounded border border-gray-300 shadow" />
                  </template>
                  <button type="button" @click.prevent="clearFile" class="mt-1 text-xs text-red-500 underline transition hover:text-red-700">Quitar archivo</button>
                </div>
              </template>
              <div x-show="!fileUrl" class="flex flex-col items-center justify-center">
                <svg class="mb-2 h-6 w-6 text-red-500" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16l4-4h10a2 2 0 0 0 2-2V8l-6-6Z"/></svg>
                <div class="mt-1">Arrastra tu comprobante aquí o haz <span class="underline font-semibold">clic</span> para seleccionarlo</div>
                <div class="mt-2 text-xs text-gray-500">Formatos: JPG, PNG, PDF | Máx 5MB.</div>
              </div>
            </label>
            <template x-if="error">
              <div class="mt-2 flex items-center gap-2 text-xs font-bold text-red-600" role="alert">
                <svg class="h-4 w-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M18 16.08A2.91 2.91 0 0 1 15.09 19H4.91A2.91 2.91 0 0 1 2 16.09V7.91A2.91 2.91 0 0 1 4.91 5H6V4a4 4 0 0 1 8 0v1h1.09A2.91 2.91 0 0 1 18 7.91v8.17zM8 4a2 2 0 0 1 4 0v1H8V4z"/>
                </svg>
                <span x-text="error"></span>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- REFERENCIA BANCARIA --}}
  <div class="mt-4">
    <label for="referencia" class="mb-1 flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-900">
      <svg class="h-4 w-4 text-blue-700" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5a2 2 0 0 1 2-2h6v6H3V5Zm0 8h8v8H5a2 2 0 0 1-2-2v-6Zm10-8h6a2 2 0 0 1 2 2v6h-8V5Zm0 8h8v6a2 2 0 0 1-2 2h-6v-8Z"/></svg>
      Referencia bancaria <span class="text-base text-red-500">*</span>
    </label>
    <input type="text" name="referencia" id="referencia" maxlength="64" required placeholder="Código o número de referencia del pago"
      class="w-full rounded-xl border-2 border-blue-200 bg-white px-4 py-2 text-base font-semibold text-blue-900 shadow-sm transition placeholder:font-normal placeholder:text-blue-400 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400"/>
    <div class="mt-1 flex items-center gap-1">
      <svg class="h-3.5 w-3.5 text-blue-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-6h2Zm0-8h-2V7h2Z"/></svg>
      <span class="text-xs text-blue-600/80">Coloca el código único de tu transferencia.</span>
    </div>
    <p class="msg-error mt-1 text-xs font-bold text-red-600" hidden>Debes ingresar la referencia bancaria.</p>
  </div>

  <div id="inlineMsg" class="min-h-[32px] py-1 text-base text-red-600"></div>

  {{-- Términos + CTA --}}
  <div class="mt-2 flex flex-col gap-3">
    <div class="flex items-start gap-2 text-xs text-gray-700">
      <input id="accept_terms" type="checkbox" name="accept_terms" required class="mt-1"/>
      <label for="accept_terms" class="cursor-pointer select-none">Acepto los</label>
      <button id="openTermsBtn" type="button" aria-haspopup="dialog" aria-controls="terms-modal"
        class="align-baseline cursor-pointer bg-transparent p-0 text-[var(--primary)] underline transition hover:text-blue-800">
        términos y condiciones
      </button>.
    </div>
    <button id="btnInlinePay" type="submit"
      class="mx-auto block min-w-[140px] max-w-[210px] rounded-lg bg-[var(--primary)] px-7 py-2 font-black text-white shadow-sm transition hover:brightness-105 active:brightness-95">
      Confirmar compra
    </button>
  </div>
</form>

<div class="checkout-foot"></div>

  {{-- Estado de éxito --}}
  <div id="inlineSuccess" class="hidden p-4">
    <div class="rounded-xl border border-black/10 bg-green-50 p-3 text-green-700">
      <div class="font-semibold">¡Listo! Recibimos tu solicitud de pago.</div>
      <div class="text-sm opacity-80">Te avisaremos cuando sea verificada. Guarda tu código: <strong id="okOrderCode"></strong>.</div>
    </div>
    @if($tSlug)
      <a href="{{ url("/t/{$tSlug}/verify") }}" class="mt-3 inline-block rounded-lg border border-[var(--primary)] bg-white px-5 py-2 font-bold text-[var(--primary)] transition hover:bg-blue-50">Ir al verificador</a>
    @endif
  </div>

  <!-- MODAL Alpine de advertencia por comprobante obligatorio -->
<div
  id="voucherModal"
  x-data="{ show: false }"
  x-show="show"
  style="background:rgba(25,30,60,0.45)"
  class="fixed inset-0 z-[300] flex items-center justify-center transition"
  x-cloak
>
  <div class="bg-white rounded-2xl shadow-2xl border border-red-200 max-w-xs w-full px-6 py-8 flex flex-col items-center relative animate-fadein-up">
    <span class="text-5xl mb-2 text-red-500"><i class="fa-solid fa-triangle-exclamation"></i></span>
    <div class="text-lg font-extrabold text-red-700 mb-3">¡Falta comprobante!</div>
    <div class="text-gray-700 text-center mb-5 text-sm">Debes adjuntar el comprobante de pago para poder enviar tu orden.</div>
    <button
  id="closeVoucherModalBtn"
  @click="show=false"
  onclick="closeVoucherModal()"
  class="rounded-lg bg-[var(--primary)] text-white font-bold px-6 py-2 shadow hover:brightness-110 active:scale-95"
  autofocus
>Aceptar</button>

  </div>
</div>

</div>

{{-- Listener para abrir términos --}}
<script>
  (function(){
    const btn = document.getElementById('openTermsBtn');
    if(!btn) return;
    btn.addEventListener('click', function(e){
      e.preventDefault(); e.stopPropagation();
      window.dispatchEvent(new CustomEvent('open-terms-modal'));
    }, { passive:false });
  })();
</script>

<script>
  function closeVoucherModal() {
    let modal = document.getElementById('voucherModal');
    // Si es Alpine (preferido)
    if (modal && modal.__x) {
      modal.__x.$data.show = false;
    } else {
      modal.style.display = 'none';
    }
  }
</script>


{{-- intl-tel-input --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function(){
    const input = document.getElementById('iti_whatsapp');
    if(!input || !window.intlTelInput) return;
    const iti = window.intlTelInput(input, {
      initialCountry: 've',
      preferredCountries: ['ve','co','ec','pe','cl','ar','mx','us','pa','br'],
      separateDialCode: true,
      utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js',
    });
    const hiddenCode  = document.getElementById('country_code');
    const hiddenPhone = document.getElementById('customer_whatsapp');
    function syncFields(){
      const c = iti.getSelectedCountryData();
      if(hiddenCode)  hiddenCode.value  = '+' + (c?.dialCode || '');
      if(hiddenPhone) hiddenPhone.value = (input.value || '').replace(/[^\d]/g, '');
    }
    input.addEventListener('countrychange', syncFields);
    input.addEventListener('input', syncFields);
    syncFields();
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('inlinePayForm');
  const voucherInput = document.getElementById('voucherInput');
  form.addEventListener('submit', function(e) {
    // Detecta si el campo comprobante es visible y requerido
    const voucherSection = document.querySelector('[x-show="showVoucher"]');
    const isVoucherVisible = voucherSection && voucherSection.offsetParent !== null;
    if (isVoucherVisible && (!voucherInput || !voucherInput.files.length)) {
      e.preventDefault();
      let modal = document.getElementById('voucherModal');
      // Si usas Alpine, muestra el modal con Alpine; si no, usa display normal
      if (modal && modal.__x) {
        modal.__x.$data.show = true;
      } else {
        modal.style.display = 'flex';
      }
      // Focus al botón para UX
      setTimeout(() => {
        modal.querySelector('button').focus();
      }, 10);
      return false;
    }
  });
});
</script>

