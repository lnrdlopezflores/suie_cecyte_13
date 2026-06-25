<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <div class="min-h-screen flex flex-col">
        <header class="bg-indigo-900 text-white shadow-md px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="material-icons-round text-3xl text-indigo-300">hub</span>
                <div>
                    <h1 class="text-xl font-bold tracking-wider">SUIE</h1>
                    <p class="text-xs text-indigo-200">Sistema Unificado de Integración Educativa</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                @if(auth()->check())
                    <div class="text-right hidden md:block">
                        @if(auth()->user()->docente)
                            <p class="text-sm font-semibold">
                                Prof. {{ auth()->user()->docente->nombre }} {{ auth()->user()->docente->apellido_paterno }}
                            </p>
                        @else
                            <p class="text-sm font-semibold">
                                {{ auth()->user()->rol ?? 'Usuario Registrado' }}
                            </p>
                        @endif
                        <p class="text-xs text-indigo-300">
                            ID: {{ auth()->user()->username }}
                        </p>
                    </div>

                    <div class="w-10 h-10 bg-indigo-700 rounded-full flex items-center justify-center font-bold border border-indigo-400 uppercase" title="Perfil">
                        @if(auth()->user()->docente)
                            {{ mb_substr(auth()->user()->docente->nombre, 0, 1) }}{{ mb_substr(auth()->user()->docente->apellido_paterno, 0, 1) }}
                        @else
                            {{ mb_substr(auth()->user()->username, 0, 2) }}
                        @endif
                    </div>
                @else
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-semibold">Usuario Invitado</p>
                    </div>
                @endif
            </div>
        </header>
        
        <main class="flex-1">
            @yield('content')
        </main>
    </div>
</body>
</html>
