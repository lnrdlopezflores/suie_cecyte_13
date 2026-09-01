<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - @yield('title')</title>
    <!-- Tailwind CSS vía CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Iconos de Google -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="icon" href="/assets/images/logo.png" type="icon">
    <!-- Inyección Dinámica y Sobrescritura Global de Colores SUIE -->
<style>
    :root {
        --color-primary: {{ $colorPrimario ?? '#841B44' }};
        --color-primary-hover: {{ $colorHover ?? '#681535' }};
        --color-primary-light: {{ $colorLight ?? '#fdf2f4' }};
    }

    /* 1. Clases utilitarias directas */
    .bg-custom-primary { background-color: var(--color-primary) !important; }
    .text-custom-primary { color: var(--color-primary) !important; }
    .border-custom-primary { border-color: var(--color-primary) !important; }
    .hover\:bg-custom-primary-hover:hover { background-color: var(--color-primary-hover) !important; }

    /* 2. Sobrescritura mágica para selectores de Tailwind fijos existentes */
    [class*="bg-[#841B44]"],
    [class*="bg-\[\#841B44\]"] {
        background-color: var(--color-primary) !important;
    }

    [class*="text-[#841B44]"],
    [class*="text-\[\#841B44\]"] {
        color: var(--color-primary) !important;
    }

    [class*="border-[#841B44]"],
    [class*="border-\[\#841B44\]"] {
        border-color: var(--color-primary) !important;
    }

    [class*="hover:bg-[#681535]"]:hover,
    [class*="hover:bg-[#6b1536]"]:hover,
    [class*="hover:bg-\[\#681535\]"]:hover,
    [class*="hover:bg-\[\#6b1536\]"]:hover {
        background-color: var(--color-primary-hover) !important;
    }

    [class*="hover:text-[#841B44]"]:hover,
    [class*="hover:text-\[\#841B44\]"]:hover {
        color: var(--color-primary) !important;
    }

    [class*="bg-rose-50"] {
        background-color: var(--color-primary-light) !important;
    }
</style>
</head>
<body class="bg-slate-100 font-sans text-slate-800">

    <div class="min-h-screen flex flex-col">
        <!-- ENCABEZADO -->
        <header class="bg-slate-900 text-white shadow-md px-6 py-3 flex justify-between items-center shrink-0">
            <div class="flex items-center space-x-3">
                <span class="material-icons-round text-2xl text-rose-500">hub</span>
                <div>
                    <h1 class="text-lg font-bold tracking-wider leading-none">SUIE</h1>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mt-0.5">Orientación Educativa</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold">Mtra. Laura Esquivel</p>
                    <p class="text-xs text-rose-400 font-medium">Filtro de Retención Escolar</p>
                </div>
                <div class="w-9 h-9 bg-slate-700 rounded-full flex items-center justify-center font-bold text-sm">
                </div>
            </div>
        </header>
        @yield('content')
    </div>
</body>
</html>