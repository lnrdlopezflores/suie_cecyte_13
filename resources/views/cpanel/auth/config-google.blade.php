@extends('cpanel/plantillaadmin')
@section('title', 'Configuración de Autenticación Google')

@section('content')
<main class="flex-1 max-w-5xl w-full mx-auto p-4 md:p-8 space-y-8 text-sm md:text-base transition-colors duration-200">

    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-custom-primary text-xs font-black uppercase tracking-widest mb-1">
                <span class="material-icons-round text-base">security</span>
                <span>Seguridad e Inicio de Sesión Único</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                Configuración de Google OAuth (SSO)
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-1">
                Administra las credenciales de API de Google Cloud, dominios autorizados y políticas de enlace automático.
            </p>
        </div>

        <!-- Tarjeta de Estado del Servicio -->
        <div class="bg-white dark:bg-slate-900 px-5 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-3xs flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 rounded-xl {{ !empty($clientId) && !empty($clientSecret) ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 border border-emerald-200 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-950/50 text-amber-600 border border-amber-200 dark:border-amber-800' }} flex items-center justify-center">
                <span class="material-icons-round text-xl">{{ !empty($clientId) && !empty($clientSecret) ? 'verified' : 'warning' }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider block">Estado del SSO</span>
                <span class="font-black text-xs md:text-sm {{ !empty($clientId) && !empty($clientSecret) ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                    {{ !empty($clientId) && !empty($clientSecret) ? 'Credenciales Listas' : 'Pendiente de Configurar' }}
                </span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 md:p-5 rounded-2xl text-xs md:text-sm font-semibold flex items-center justify-between shadow-3xs">
            <div class="flex items-center gap-3">
                <span class="material-icons-round text-xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                <span class="material-icons-round text-base">close</span>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- COLUMNA PRINCIPAL: FORMULARIO -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-6">
                
                <form action="{{ route('admin.google-auth.update') }}" method="POST" class="space-y-6 text-xs">
                    @csrf

                    <!-- Interruptor de Servicio -->
                    <div class="flex items-center justify-between p-4 bg-slate-50/80 dark:bg-slate-800/40 rounded-2xl border border-slate-200/80 dark:border-slate-700">
                        <div class="space-y-0.5">
                            <label for="activo" class="font-bold text-slate-800 dark:text-slate-100 text-xs md:text-sm block cursor-pointer">
                                Habilitar botón de "Continuar con Google"
                            </label>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Permite a los usuarios ingresar mediante su cuenta Google vinculada.
                            </p>
                        </div>
                        <input type="checkbox" name="activo" id="activo" value="1" {{ $activo ? 'checked' : '' }}
                               class="w-5 h-5 rounded-md text-custom-primary focus:ring-custom-primary border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer">
                    </div>

                    <!-- Credenciales de Google Cloud -->
                    <div class="space-y-4">
                        <p class="text-[11px] font-black uppercase text-slate-400 tracking-wider">Credenciales de API</p>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Google Client ID</label>
                            <input type="text" name="client_id" value="{{ old('client_id', $clientId) }}" 
                                   class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-mono border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all text-xs" 
                                   placeholder="ej: 123456789-xxxxxxxx.apps.googleusercontent.com">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Google Client Secret</label>
                            <div class="relative">
                                <input type="password" name="client_secret" id="client_secret_input" value="{{ old('client_secret', $clientSecret) }}" 
                                       class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-mono border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 pr-12 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all text-xs" 
                                       placeholder="GOCSPX-xxxxxxxxxxxxxxxxxxxx">
                                <button type="button" onclick="toggleSecretVisibility()" 
                                        class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                                    <span id="secret_icon" class="material-icons-round text-lg">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Políticas de Dominio y Acceso -->
                    <div class="space-y-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <p class="text-[11px] font-black uppercase text-slate-400 tracking-wider">Políticas de Dominio</p>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Modo de Autenticación *</label>
                            <select name="modo_acceso" 
                                    class="w-full bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 font-bold focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all cursor-pointer">
                                <option value="hibrido" {{ $modoAcceso == 'hibrido' ? 'selected' : '' }}>
                                    Híbrido (Admitir cuentas institucionales y personales registradas)
                                </option>
                                <option value="solo_institucional" {{ $modoAcceso == 'solo_institucional' ? 'selected' : '' }}>
                                    Estricto (Solo cuentas del dominio institucional establecido)
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Dominio Institucional Oficial</label>
                            <input type="text" name="dominio_permitido" value="{{ old('dominio_permitido', $dominioPermitido) }}" required 
                                   class="w-full bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-mono border border-slate-300 dark:border-slate-700 rounded-2xl p-3.5 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all text-xs" 
                                   placeholder="cecytlax.edu.mx">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <button type="submit" 
                                class="px-7 py-3.5 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-xs rounded-2xl shadow-xs hover:shadow-md transition-all cursor-pointer flex items-center gap-2">
                            <span class="material-icons-round text-base">save</span>
                            <span>Guardar Parámetros de Google OAuth</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- COLUMNA LATERAL: INSTRUCCIONES Y URI DE RETORNO -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs space-y-4">
                <div class="flex items-center gap-2 text-custom-primary font-black text-xs uppercase tracking-wider">
                    <span class="material-icons-round text-base">link</span>
                    <span>URI de Redirección Autorizada</span>
                </div>
                
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Esta es la dirección exacta que debes copiar y pegar en la consola de Google Cloud dentro de <strong>URIs de redireccionamiento autorizados</strong>:
                </p>

                <div class="relative bg-slate-50 dark:bg-slate-800 p-3.5 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <code id="redirect_uri_text" class="font-mono text-xs font-bold text-slate-800 dark:text-slate-200 break-all select-all">
                        {{ $redirectUri }}
                    </code>
                    <button type="button" onclick="copiarUri()" 
                            class="mt-2.5 w-full py-2 bg-white dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-600 flex items-center justify-center gap-1.5 transition-colors cursor-pointer shadow-3xs">
                        <span class="material-icons-round text-sm">content_copy</span>
                        <span id="copy_btn_text">Copiar URI</span>
                    </button>
                </div>
            </div>

            <!-- Recordatorio de Google Cloud -->
            <div class="p-5 bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 rounded-3xl space-y-2 text-xs text-indigo-900 dark:text-indigo-300">
                <div class="flex items-center gap-2 font-black text-[11px] uppercase tracking-wider">
                    <span class="material-icons-round text-base">info</span>
                    <span>Google Cloud Console</span>
                </div>
                <p class="leading-relaxed text-[11px]">
                    Recuerda que si ejecutas pruebas locales en <code>127.0.0.1:8000</code> y también en <code>localhost:8000</code>, debes agregar ambas variantes en la consola de Google para evitar el error <code>redirect_uri_mismatch</code>.
                </p>
            </div>
        </div>

    </div>
</main>

<script>
    function toggleSecretVisibility() {
        const input = document.getElementById('client_secret_input');
        const icon = document.getElementById('secret_icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerText = 'visibility_off';
        } else {
            input.type = 'password';
            icon.innerText = 'visibility';
        }
    }

    function copiarUri() {
        const text = document.getElementById('redirect_uri_text').innerText.trim();
        navigator.clipboard.writeText(text).then(() => {
            const btnText = document.getElementById('copy_btn_text');
            btnText.innerText = '¡Copiado al portapapeles!';
            setTimeout(() => {
                btnText.innerText = 'Copiar URI';
            }, 2500);
        });
    }
</script>
@endsection