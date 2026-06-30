<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIE Finanzas - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body class="bg-slate-100 font-sans text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-6 py-3 flex justify-between items-center shrink-0 z-40 shadow-2xs">
        <div class="flex items-center space-x-3">
            <span class="material-icons-round text-2xl text-[#841B44]">account_balance</span>
            <div>
                <h1 class="text-base font-black tracking-wider leading-none text-[#841B44]">SUIE</h1>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Departamento de Finanzas y Caja</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900">Oficina de Glosa y Tesorería</p>
                <p class="text-[10px] text-[#841B44] font-bold uppercase font-mono tracking-wider">
                    {{ auth()->user()->username ?? 'FINANZAS-01' }}
                </p>
            </div>
            <div class="w-8 h-8 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center font-black border border-emerald-100 text-xs">
                $
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <aside class="w-64 bg-slate-900 text-slate-400 flex flex-col h-full shrink-0 border-r border-slate-800 justify-between">
            
            <div class="p-4 space-y-6">
                <div>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest px-3 block mb-2">Ingresos por Caja</span>
                    <nav class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">fact_check</span> Revisar Fichas (Pagos)
                        </a>
                        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">point_of_sale</span> Registrar Cobro Directo
                        </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-colors hover:bg-slate-800 hover:text-slate-200">
                            <span class="material-icons-round text-sm">assessment</span> Reportes de Ingresos
                        </a>
                    </nav>
                </div>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-950/30 rounded-xl transition-colors cursor-pointer">
                        <span class="material-icons-round text-sm">logout</span> Salir de Finanzas
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto flex flex-col h-full bg-slate-50">
            @yield('content')
        </main>

    </div>

</body>
</html>