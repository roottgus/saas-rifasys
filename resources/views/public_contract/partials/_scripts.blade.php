{{-- Signature Pad --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<script>
    function resizeCanvas(canvas, sigPad) {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        const ctx = canvas.getContext('2d');
        ctx.scale(ratio, ratio);
        if (sigPad) {
            sigPad.clear();
            ctx.font = '14px Arial';
            ctx.fillStyle = '#e2e8f0';
            ctx.textAlign = 'center';
            ctx.fillText('Firme aquí', rect.width / 2, rect.height / 2);
        }
    }

    const canvas = document.getElementById('signature-pad');
    if (canvas) {
        canvas.style.width = '100%';
        canvas.style.height = '250px';

        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255,255,255,1)',
            penColor: '#0f172a',
            minWidth: 0.5,
            maxWidth: 2.5,
            throttle: 16,
            velocityFilterWeight: 0.7
        });

        resizeCanvas(canvas, signaturePad);
        window.addEventListener('resize', () => resizeCanvas(canvas, signaturePad), { passive: true });

        const clearBtn = document.getElementById('clear-signature');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                signaturePad.clear();
                resizeCanvas(canvas, signaturePad);
            });
        }

        const form = document.getElementById('firma-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-4 right-4 z-50 max-w-md p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl shadow-xl';
                    alertDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold">Por favor, realice su firma electrónica antes de continuar.</span>
                        </div>
                    `;
                    document.body.appendChild(alertDiv);
                    setTimeout(() => alertDiv.remove(), 5000);
                    document.getElementById('signature-pad').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
                const dataUrl = signaturePad.toDataURL('image/png');
                document.getElementById('signature_data').value = dataUrl;
                document.getElementById('signature_image').value = dataUrl;

                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    const span = btn.querySelector('span');
                    if (span) {
                        span.innerHTML = `
                            <svg class="animate-spin h-5 w-5 mr-2 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Procesando firma...
                        `;
                    }
                }
                return true;
            }, { passive: false });
        }

        const up1 = document.getElementById('cedula-upload');
        const up2 = document.getElementById('conalot-upload');
        if (up1) up1.addEventListener('change', e => {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const display = document.getElementById('cedula-filename');
                display.textContent = '✓ ' + fileName;
                display.classList.remove('hidden');
            }
        });
        if (up2) up2.addEventListener('change', e => {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const display = document.getElementById('conalot-filename');
                display.textContent = '✓ ' + fileName;
                display.classList.remove('hidden');
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
            });
        });
    }
</script>

<style>
    .firma-canvas { cursor: crosshair; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); }
    @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);} 100% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);} }
    .animate-pulse { animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    input[type="file"] + label > div:hover { transform: scale(1.02); transition: transform 0.2s; }
    * { transition-property: background-color, border-color, color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
</style>
