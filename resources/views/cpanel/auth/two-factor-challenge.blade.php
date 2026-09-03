<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - Verificación en Dos Pasos</title>
    
    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Detección y Persistencia de Tema -->
    <script>
        (function() {
            const localTheme = localStorage.getItem('suie_theme_challenge');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (localTheme === 'dark' || (!localTheme && systemDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Variables CSS Dinámicas de Color Institucional -->
    <style>
        :root {
            --color-primary: {{ $colorPrimario ?? '#841B44' }};
            --color-primary-hover: {{ $colorHover ?? '#681535' }};
            --color-primary-light: {{ $colorLight ?? '#fdf2f4' }};
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-custom-primary { background-color: var(--color-primary) !important; color: #ffffff !important; }
        .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }
        .text-custom-primary { color: var(--color-primary) !important; }
        .border-custom-primary { border-color: var(--color-primary) !important; }
        .bg-custom-light { background-color: var(--color-primary-light) !important; }
        .focus\:ring-custom-primary:focus { --tw-ring-color: var(--color-primary) !important; }

        .bg-grid-pattern {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased relative overflow-hidden bg-grid-pattern selection:bg-custom-primary selection:text-white">

    <!-- Brillo Ambiental Trasero Adaptativo -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-custom-primary opacity-15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-slate-700 opacity-20 blur-3xl pointer-events-none"></div>

    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl p-7 sm:p-9 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-7 relative z-10 transition-colors duration-200">
        
        <!-- Identidad Institucional y Encabezado -->
        <div class="text-center space-y-3">
            <div class="w-16 h-16 bg-custom-light text-custom-primary rounded-2xl mx-auto flex items-center justify-center border border-custom-primary/20 shadow-2xs">
                <span class="material-icons-round text-3xl">phonelink_lock</span>
            </div>
            
            <div class="space-y-1">
                <div class="flex items-center justify-center gap-1.5 text-custom-primary text-[11px] font-black uppercase tracking-widest">
                    <span>SUIE</span>
                    <span>•</span>
                    <span>CECyTE 13</span>
                </div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">
                    Verificación 2FA
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed font-medium">
                    Ingresa el código temporal de 6 dígitos generado en tu aplicación <strong>Google Authenticator</strong>.
                </p>
            </div>
        </div>

        <!-- Alerta de Errores -->
        @if($errors->any())
            <div class="p-4 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900/60 text-rose-800 dark:text-rose-200 rounded-2xl text-xs flex items-center gap-3 shadow-3xs font-medium">
                <span class="material-icons-round text-lg shrink-0 text-rose-600 dark:text-rose-400">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('2fa.verify') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="block text-center font-bold text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Código de Seguridad
                </label>
                
                <div class="relative">
                    <input type="text" name="code" maxlength="6" autofocus required inputmode="numeric" autocomplete="one-time-code"
                           class="w-full text-center text-3xl font-mono font-black tracking-[0.35em] bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-slate-100 border border-slate-300 dark:border-slate-700 rounded-2xl py-3.5 focus:ring-2 focus:ring-custom-primary focus:outline-hidden transition-all shadow-inner"
                           placeholder="000000">
                </div>
            </div>

            <button type="submit" 
                    class="w-full py-4 bg-custom-primary hover:bg-custom-primary-hover text-white font-extrabold text-sm rounded-2xl shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center justify-center gap-2">
                <span class="material-icons-round text-lg">verified_user</span>
                <span>Validar y Entrar</span>
            </button>
        </form>

        <!-- Pie de Modal / Cancelar -->
        <div class="text-center border-t border-slate-100 dark:border-slate-800/80 pt-5">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-custom-primary dark:text-slate-400 dark:hover:text-custom-primary font-bold transition-colors">
                <span class="material-icons-round text-sm">arrow_back</span>
                <span>Regresar al inicio de sesión</span>
            </a>
        </div>
    </div>

</body>
</html>