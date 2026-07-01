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