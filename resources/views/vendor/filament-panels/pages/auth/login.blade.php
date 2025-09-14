@props([
    'action' => null,
    'heading' => null,
    'subheading' => null,
])

<x-filament-panels::page.simple>
    @php
        $isAdminPanel = str_contains(request()->path(), 'admin');
        $panelName = $isAdminPanel ? 'Panel Administrativo' : 'Rifasys';
    @endphp

    <style>
        body, .fi-simple-layout, .fi-simple-main-ctn {
            background: linear-gradient(135deg, #174888 0%, #315C95 50%) !important;
        }
        .fi-simple-layout,
        .fi-simple-main-ctn,
        .fi-simple-main {
            min-width: 100vw !important;
            width: 100vw !important;
            max-width: 100vw !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box;
        }
        .fi-simple-main {
            all: unset !important;
            width: 700px !important;
            max-width: 800px !important;
            margin: 0 auto !important;
            padding: 0 !important;
            background: transparent !important;
        }
        .login-card-pr {
            display: flex;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 36px 0 rgba(23,72,136,0.09), 0 1.5px 8px 0 rgba(49,92,149,0.10);
            min-height: 320px;
            position: relative;
            animation: loginFadeIn 0.9s cubic-bezier(.45,1.16,.69,.94) both;
        }
        @keyframes loginFadeIn {
            0% { opacity: 0; transform: translateY(24px) scale(.97);}
            100% { opacity: 1; transform: none;}
        }
        .login-left-pr {
            flex: 1.2;
            background: linear-gradient(135deg, #174888 0%, #315C95 100%);
            color: #fff;
            padding: 2rem 1.5rem 2rem 1.7rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
        }
        .login-left-pr img {
            width: 70px;
            height: 70px;
            margin-bottom: 1rem;
            border-radius: 18px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            background: #fff;
            object-fit: contain;
            padding: 8px;
        }
        .login-welcome-pr {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
            letter-spacing: -.02em;
        }
        .login-text-pr {
            font-size: 1.08rem;
            line-height: 1.5;
            margin-bottom: 1.1rem;
            font-weight: 400;
            color: #f3f4f6;
        }
        .feature-list-pr {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .feature-item-pr {
            display: flex;
            align-items: center;
            margin-bottom: 0.55rem;
            font-size: 0.98rem;
        }
        .check-icon-pr {
            background: rgba(255,255,255,0.18);
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            width: 23px;
            height: 23px;
        }
        .check-icon-pr svg {
            width: 13px;
            height: 13px;
            color: #fff;
        }
        /* Right side - Login Form */
        .login-right-pr {
            flex: 1.2;
            padding: 2.2rem 1.3rem 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-title-pr {
            font-size: 1.25rem;
            font-weight: 700;
            color: #174888;
            text-align: center;
            margin-bottom: 0.2rem;
            letter-spacing: -.02em;
        }
        .form-subtitle-pr {
            color: #5e789a;
            text-align: center;
            margin-bottom: 1.2rem;
            font-size: 0.99rem;
        }
        .fi-fo-field-wrp-label label {
            color: #315C95 !important;
            font-weight: 600 !important;
            font-size: 0.97rem !important;
            margin-bottom: 0.15rem !important;
        }
        .fi-input-wrp input {
            border: 2px solid #e2e8f0 !important;
            border-radius: 13px !important;
            padding: 0.68rem 1rem !important;
            font-size: 1rem !important;
        }
        .fi-input-wrp input:focus {
            border-color: #174888 !important;
            box-shadow: 0 0 0 2px #17488833 !important;
        }
        .fi-btn {
            background: linear-gradient(90deg, #174888 0%, #315C95 100%) !important;
            border: none !important;
            border-radius: 13px !important;
            padding: 0.8rem !important;
            width: 100% !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            margin-top: 0.9rem;
            color: #fff !important;
            transition: box-shadow 0.22s cubic-bezier(.36,1.21,.49,.99), transform 0.13s cubic-bezier(.36,1.21,.49,.99);
        }
        .fi-btn:hover {
            filter: brightness(1.10);
            box-shadow: 0 4px 20px #315c9540;
            transform: translateY(-1.5px);
        }
        .links-row-pr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.7rem;
            margin-bottom: 0.7rem;
        }
        .links-row-pr a {
            color: #174888;
            font-size: 0.89rem;
            font-weight: 600;
            text-decoration: none;
        }
        .links-row-pr a:hover {
            color: #315C95;
            text-decoration: underline;
        }
        /* Footer Publienred */
        .footer-publienred {
            background: none;
            border-top: 1.5px dashed #dbeafe;
            padding: 0.8rem 0 0.1rem 0;
            text-align: center;
            font-size: 0.89rem;
            color: #748cbc;
            width: 100%;
            margin-top: 0.8rem;
        }
        .footer-publienred a {
            color: #174888;
            font-weight: 700;
            text-decoration: none;
            margin: 0 0.1rem;
        }
        .footer-publienred a:hover {
            text-decoration: underline;
            color: #315C95;
        }
        .logo-box {
            width: 200px;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem auto;
            background: rgba(255,255,255,0.18);
            border-radius: 20px;
            backdrop-filter: blur(5px);
            overflow: visible;
        }
        .logo-img-pr {
            width: 190px !important;
            height: 190px !important;
            object-fit: contain;
            display: block;
            margin: 0;
            padding: 0;
        }
        @media (max-width: 1000px) {
            .fi-simple-main {
                width: 98vw !important;
                max-width: 98vw !important;
            }
            .login-card-pr {
                max-width: 98vw !important;
            }
        }
        @media (max-width: 900px) {
            .fi-simple-main { width: 99vw !important; }
            .login-card-pr { min-height: 320px; }
        }
        @media (max-width: 720px) {
            .login-card-pr {
                flex-direction: column;
                min-height: 0;
                max-width: 99vw !important;
                box-shadow: 0 2px 8px 0 rgba(23,72,136,0.10);
            }
            .login-left-pr, .login-right-pr {
                width: 100% !important;
                min-width: 0;
                padding: 1.1rem 0.7rem !important;
            }
            .login-left-pr {
                align-items: center;
                text-align: center;
                padding-bottom: 0.8rem !important;
            }
            .logo-box {
                width: 120px;
                height: 120px;
                margin-bottom: 0.6rem;
            }
            .logo-img-pr {
                width: 95px !important;
                height: 95px !important;
            }
            .form-title-pr {
                font-size: 1.12rem;
            }
        }
        @media (max-width: 480px) {
            .login-card-pr {
                border-radius: 12px;
                margin: 0 0.2rem;
            }
            .login-left-pr, .login-right-pr {
                padding: 0.7rem 0.3rem !important;
            }
            .feature-item-pr {
                font-size: 0.87rem;
            }
            .footer-publienred {
                font-size: 0.76rem;
                padding: 0.7rem 0 0.07rem 0;
            }
        }
    </style>

    <main>
        <div class="login-card-pr" role="main">
            <!-- LEFT COLUMN -->
            <div class="login-left-pr">
                <div class="logo-box">
                    <img src="{{ asset('images/logoppal.png') }}" alt="Logo principal de Rifasys" class="logo-img-pr">
                </div>
                <div class="login-welcome-pr">¡Bienvenido!</div>
                <div class="login-text-pr">
                    Por favor, ingresa tus credenciales para acceder a tu cuenta, gestionar tus rifas y consultar información exclusiva.
                </div>
                <ul class="feature-list-pr">
                    <li class="feature-item-pr">
                        <span class="check-icon-pr">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Gestiona múltiples rifas simultáneamente
                    </li>
                    <li class="feature-item-pr">
                        <span class="check-icon-pr">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Reportes y análisis en tiempo real
                    </li>
                    <li class="feature-item-pr">
                        <span class="check-icon-pr">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Pagos seguros y verificados
                    </li>
                    <li class="feature-item-pr">
                        <span class="check-icon-pr">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Soporte 24/7 para tu negocio
                    </li>
                </ul>
            </div>
            <!-- RIGHT COLUMN -->
            <div class="login-right-pr">
                <div class="form-title-pr">Iniciar Sesión</div>
                <div class="form-subtitle-pr">Ingresa a tu cuenta de {{ $panelName }}</div>
                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}

                    <div class="links-row-pr">
                        <div>
                            @if (filament()->hasRegistration())
                                <a href="{{ filament()->getRegistrationUrl() }}">Crear cuenta</a>
                            @endif
                        </div>
                    </div>
                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="$this->hasFullWidthFormActions()"
                    />
                </x-filament-panels::form>
                <div class="footer-publienred">
                    diseñado por <a href="https://publienred.com" target="_blank">Publienred</a> ·
                    <a href="https://www.instagram.com/publienredca/" target="_blank">Instagram</a>
                </div>
            </div>
        </div>
    </main>
</x-filament-panels::page.simple>
