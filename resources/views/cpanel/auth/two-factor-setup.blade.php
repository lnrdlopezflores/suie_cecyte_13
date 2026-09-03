@php
    $rolActual = strtolower(auth()->user()->rol ?? 'administrador');
    $plantilla = match($rolActual) {
        'estudiante', 'alumno' => 'cpanel.plantillaestudiante',
        'docente'              => 'cpanel.plantilladocente',
        'control escolar'      => 'cpanel.plantillaCE',
        'coordinador'          => 'cpanel.plantillacoordinacion',
        default                => 'cpanel.plantillaadmin',
    };
@endphp

@extends($plantilla)
@section('title', 'Seguridad - Google Authenticator')

@section('content')
<main class="flex-1 max-w-4xl w-full mx-auto p-4 md:p-8 space-y-8 text-sm md:text-base transition-colors duration-200">
    
    <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-8">
        
        <!-- ENCABEZADO -->
        <div class="border-b border-slate-100 dark:border-slate-800 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                    <span class="material-icons-round text-base">security</span>
                    <span>Protección de Cuenta</span>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100">
                    Verificación en Dos Pasos (Google Authenticator)
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">
                    Añade una capa extra de seguridad a tu cuenta solicitando un código TOTP desde tu smartphone.
                </p>
            </div>

            <div class="shrink-0">
                @if($isEnabled)
                    <span class="inline-flex items-center text-emerald-700 dark:text-emerald-400 font-extrabold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 px-3.5 py-1 rounded-full text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> 2FA Activado
                    </span>
                @else
                    <span class="inline-flex items-center text-amber-700 dark:text-amber-400 font-extrabold bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 px-3.5 py-1 rounded-full text-xs">
                        No configurado
                    </span>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 rounded-2xl text-xs md:text-sm font-semibold flex items-center gap-2.5">
                <span class="material-icons-round text-lg text-emerald-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(!$isEnabled)
            <!-- PASO A PASO DE ACTIVACIÓN -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                
                <!-- QR SVG -->
                <div class="md:col-span-5 flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-800/60 rounded-3xl border border-slate-200 dark:border-slate-700/80 text-center space-y-3">
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200">
                        {!! $qrCodeSvg !!}
                    </div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">
                        Escanea este código con la app Google Authenticator
                    </span>
                </div>

                <!-- INSTRUCCIONES Y CÓDIGO -->
                <div class="md:col-span-7 space-y-5">
                    <div class="space-y-2">
                        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base">
                            1. Abre Google Authenticator en tu teléfono
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Pulsa el botón <strong>+</strong> y selecciona <strong>Escanear código QR</strong>. Si tu cámara no funciona, ingresa esta clave secreta manualmente:
                        </p>
                        <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded-xl font-mono text-xs font-bold text-custom-primary select-all break-all border border-slate-200 dark:border-slate-700">
                            {{ $secretKey }}
                        </div>
                    </div>

                    <form action="{{ route('2fa.enable') }}" method="POST" class="space-y-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                        @csrf
                        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base">
                            2. Ingresa el código de confirmación
                        </h3>
                        
                        <div>
                            <input type="text" name="code" maxlength="6" required inputmode="numeric"
                                   class="w-full max-w-xs text-center font-mono text-xl font-black bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 focus:ring-2 focus:ring-custom-primary focus:outline-hidden"
                                   placeholder="000000">
                            @error('code')
                                <p class="text-rose-600 text-xs mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="px-6 py-3 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-md transition-all cursor-pointer">
                            Confirmar y Activar 2FA
                        </button>
                    </form>
                </div>
            </div>

        @else
            <!-- OPCIÓN PARA DESACTIVAR -->
            <div class="p-6 bg-slate-50 dark:bg-slate-800/40 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4 max-w-xl">
                <div>
                    <h3 class="font-black text-slate-900 dark:text-slate-100 text-base">Desvincular Google Authenticator</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Si perdiste o cambiaste tu teléfono, puedes desactivar el segundo factor ingresando tu contraseña de usuario actual.
                    </p>
                </div>

                <form action="{{ route('2fa.disable') }}" method="POST" class="space-y-3" onsubmit="return confirm('¿Seguro que deseas desactivar la verificación en dos pasos?')">
                    @csrf
                    <div>
                        <input type="password" name="current_password" required placeholder="Tu contraseña actual"
                               class="w-full bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl p-3 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-hidden">
                        @error('current_password')
                            <p class="text-rose-600 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-2xl shadow-xs transition-colors cursor-pointer">
                        Desactivar Verificación 2FA
                    </button>
                </form>
            </div>
        @endif

    </div>
</main>
@endsection