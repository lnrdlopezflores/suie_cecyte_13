@extends($plantilla ?? 'cpanel/plantillaestudiante')
@section('title', 'Restablecer Contraseña')

@section('content')
<main class="flex-1 max-w-2xl w-full mx-auto p-4 sm:p-6 md:p-8 space-y-6 transition-colors duration-200">

    <!-- ENCABEZADO DE SECCIÓN -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between gap-4">
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 rounded-2xl {{ $esPrimerIngreso ? 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800' : 'bg-custom-light text-custom-primary border-custom-primary/25' }} border flex items-center justify-center shrink-0 shadow-3xs">
                <span class="material-icons-round text-2xl">{{ $esPrimerIngreso ? 'vpn_key' : 'lock_reset' }}</span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        {{ $rolLabel }}
                    </span>
                    @if($esPrimerIngreso)
                        <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                            Primer Ingreso
                        </span>
                    @endif
                </div>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-slate-100 mt-1 truncate">
                    {{ $esPrimerIngreso ? 'Configura tu Contraseña Personal' : 'Restablecer Contraseña' }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                    Usuario: <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ auth()->user()->username }}</span>
                </p>
            </div>
        </div>

        @if(!$esPrimerIngreso && \Illuminate\Support\Facades\Route::has($rutaPanel))
            <a href="{{ route($rutaPanel) }}" 
               class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-colors shrink-0">
                <span class="material-icons-round text-base">arrow_back</span>
                <span>Volver</span>
            </a>
        @endif
    </div>

    <!-- AVISO DE PRIMER INGRESO -->
    @if($esPrimerIngreso)
        <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/70 text-amber-900 dark:text-amber-200 flex items-start gap-3.5">
            <span class="material-icons-round text-xl text-amber-600 dark:text-amber-400 shrink-0 mt-0.5">info</span>
            <div class="text-xs sm:text-sm space-y-1">
                <p class="font-extrabold">Cambio de contraseña requerido por seguridad</p>
                <p class="leading-relaxed opacity-90">
                    Hemos detectado que es tu primer acceso al sistema SUIE. Por favor sustituye tu contraseña temporal por una clave personal antes de continuar usando la plataforma.
                </p>
            </div>
        </div>
    @endif

    <!-- ALERTAS DE ERROR O ÉXITO -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs sm:text-sm font-bold flex items-center gap-3">
            <span class="material-icons-round text-xl text-emerald-600 dark:text-emerald-400 shrink-0">check_circle</span>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200 text-xs sm:text-sm space-y-1">
            <div class="flex items-center gap-2 font-extrabold">
                <span class="material-icons-round text-lg text-rose-600 dark:text-rose-400">error</span>
                <span>Revisa los siguientes campos:</span>
            </div>
            <ul class="list-disc list-inside pl-6 space-y-0.5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORMULARIO PRINCIPAL -->
    <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <form id="formResetPassword" action="{{ route('password.rol.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Contraseña Actual (Solo visible cuando NO es primer ingreso) -->
            @if(!$esPrimerIngreso)
                <div>
                    <label for="current_password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Contraseña Actual
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                               class="w-full bg-slate-50 dark:bg-slate-800/70 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-custom-primary/30 focus:border-custom-primary rounded-2xl px-4 py-3.5 pr-12 text-sm font-medium transition-all"
                               placeholder="Ingresa tu contraseña actual">
                        <button type="button" onclick="togglePassField('current_password', 'eye_current')" 
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 cursor-pointer">
                            <span id="eye_current" class="material-icons-round text-xl">visibility</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Nueva Contraseña -->
            <div>
                <label for="new_password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    Nueva Contraseña
                </label>
                <div class="relative">
                    <input type="password" id="new_password" name="password" required minlength="8" autocomplete="new-password"
                           class="w-full bg-slate-50 dark:bg-slate-800/70 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-custom-primary/30 focus:border-custom-primary rounded-2xl px-4 py-3.5 pr-12 text-sm font-medium transition-all"
                           placeholder="Mínimo 8 caracteres (letras y números)">
                    <button type="button" onclick="togglePassField('new_password', 'eye_new')" 
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 cursor-pointer">
                        <span id="eye_new" class="material-icons-round text-xl">visibility</span>
                    </button>
                </div>

                <!-- Barra de Fortaleza -->
                <div class="mt-2.5 space-y-1.5">
                    <div class="flex gap-1.5 h-1.5">
                        <div id="bar-1" class="flex-1 rounded-full bg-slate-200 dark:bg-slate-800 transition-colors"></div>
                        <div id="bar-2" class="flex-1 rounded-full bg-slate-200 dark:bg-slate-800 transition-colors"></div>
                        <div id="bar-3" class="flex-1 rounded-full bg-slate-200 dark:bg-slate-800 transition-colors"></div>
                        <div id="bar-4" class="flex-1 rounded-full bg-slate-200 dark:bg-slate-800 transition-colors"></div>
                    </div>
                    <p id="strength-text" class="text-[11px] font-bold text-slate-400">Seguridad: Sin evaluar</p>
                </div>
            </div>

            <!-- Confirmar Nueva Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    Confirmar Nueva Contraseña
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password"
                           class="w-full bg-slate-50 dark:bg-slate-800/70 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 focus:ring-2 focus:ring-custom-primary/30 focus:border-custom-primary rounded-2xl px-4 py-3.5 pr-12 text-sm font-medium transition-all"
                           placeholder="Repite la nueva contraseña">
                    <button type="button" onclick="togglePassField('password_confirmation', 'eye_confirm')" 
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 cursor-pointer">
                        <span id="eye_confirm" class="material-icons-round text-xl">visibility</span>
                    </button>
                </div>
                <p id="match-status" class="hidden text-xs font-bold mt-1.5"></p>
            </div>

            <!-- Requisitos -->
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                <p class="text-xs font-extrabold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                    <span class="material-icons-round text-base text-custom-primary">verified_user</span>
                    Requisitos de la contraseña:
                </p>
                <ul class="text-xs text-slate-500 dark:text-slate-400 space-y-1">
                    <li id="req-len" class="flex items-center gap-1.5">
                        <span class="material-icons-round text-sm text-slate-400">radio_button_unchecked</span>
                        <span>Mínimo 8 caracteres de longitud</span>
                    </li>
                    <li id="req-letter" class="flex items-center gap-1.5">
                        <span class="material-icons-round text-sm text-slate-400">radio_button_unchecked</span>
                        <span>Al menos una letra (A-Z / a-z)</span>
                    </li>
                    <li id="req-num" class="flex items-center gap-1.5">
                        <span class="material-icons-round text-sm text-slate-400">radio_button_unchecked</span>
                        <span>Al menos un número (0-9)</span>
                    </li>
                </ul>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                        class="flex-1 py-3.5 px-6 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-sm rounded-2xl shadow-sm transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-98">
                    <span class="material-icons-round text-lg">save</span>
                    <span>Actualizar Contraseña</span>
                </button>

                @if(!$esPrimerIngreso && \Illuminate\Support\Facades\Route::has($rutaPanel))
                    <a href="{{ route($rutaPanel) }}" 
                       class="py-3.5 px-6 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-sm rounded-2xl transition-colors text-center">
                        Cancelar
                    </a>
                @endif
            </div>
        </form>
    </div>

</main>

<script>
    function togglePassField(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const passInput    = document.getElementById('new_password');
        const confirmInput = document.getElementById('password_confirmation');
        const matchStatus  = document.getElementById('match-status');
        const strengthText = document.getElementById('strength-text');
        const form         = document.getElementById('formResetPassword');

        const bars = [
            document.getElementById('bar-1'),
            document.getElementById('bar-2'),
            document.getElementById('bar-3'),
            document.getElementById('bar-4'),
        ];

        function updateRequirement(id, valid) {
            const el = document.getElementById(id);
            if (!el) return;
            const icon = el.querySelector('.material-icons-round');
            if (valid) {
                el.classList.add('text-emerald-600', 'dark:text-emerald-400', 'font-bold');
                icon.textContent = 'check_circle';
                icon.classList.remove('text-slate-400');
                icon.classList.add('text-emerald-500');
            } else {
                el.classList.remove('text-emerald-600', 'dark:text-emerald-400', 'font-bold');
                icon.textContent = 'radio_button_unchecked';
                icon.classList.remove('text-emerald-500');
                icon.classList.add('text-slate-400');
            }
        }

        function evaluatePassword() {
            const val = passInput.value;
            const hasLen    = val.length >= 8;
            const hasLetter = /[a-zA-Z]/.test(val);
            const hasNum    = /[0-9]/.test(val);
            const hasSpec   = /[^a-zA-Z0-9]/.test(val);

            updateRequirement('req-len', hasLen);
            updateRequirement('req-letter', hasLetter);
            updateRequirement('req-num', hasNum);

            let score = 0;
            if (val.length > 0) score++;
            if (hasLen) score++;
            if (hasLetter && hasNum) score++;
            if (hasLen && hasSpec) score++;

            const colors = ['bg-rose-500', 'bg-amber-500', 'bg-blue-500', 'bg-emerald-500'];
            const labels = ['Muy débil', 'Regular', 'Buena', 'Excelente'];

            bars.forEach((bar, idx) => {
                bar.className = 'flex-1 rounded-full transition-colors ' + (idx < score ? colors[score - 1] : 'bg-slate-200 dark:bg-slate-800');
            });

            strengthText.textContent = val.length === 0
                ? 'Seguridad: Sin evaluar'
                : 'Seguridad: ' + labels[score - 1];
        }

        function checkMatch() {
            if (!confirmInput.value) {
                matchStatus.classList.add('hidden');
                return true;
            }
            matchStatus.classList.remove('hidden');
            if (passInput.value === confirmInput.value) {
                matchStatus.textContent = '✓ Las contraseñas coinciden';
                matchStatus.className = 'text-xs font-bold mt-1.5 text-emerald-600 dark:text-emerald-400';
                return true;
            } else {
                matchStatus.textContent = '✕ Las contraseñas no coinciden';
                matchStatus.className = 'text-xs font-bold mt-1.5 text-rose-600 dark:text-rose-400';
                return false;
            }
        }

        passInput.addEventListener('input', () => {
            evaluatePassword();
            checkMatch();
        });
        confirmInput.addEventListener('input', checkMatch);

        form.addEventListener('submit', function (e) {
            if (!checkMatch()) {
                e.preventDefault();
                confirmInput.focus();
            }
        });
    });
</script>
@endsection