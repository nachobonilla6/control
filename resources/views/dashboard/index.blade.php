<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              indigo: {
                50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8',
                500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81',
              },
            },
          },
        },
      };
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-slate-800/50 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <span class="text-xl font-bold text-indigo-400">josh dev</span>
            <span class="text-slate-700 font-light text-xl">/</span>
            <span class="text-sm font-medium text-slate-400 tracking-wide uppercase">Dashboard Control</span>
        </div>
        <div class="flex items-center space-x-4">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-6 md:p-10">
        <div class="max-w-7xl mx-auto">
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-white mb-2">Welcome Back, {{ Auth::user()->name }}</h1>
                <p class="text-slate-400">Select a system to manage and view history.</p>
            </div>

            <!-- Grid of Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($bots as $bot)
                <a href="{{ route('dashboard.history', $bot['id']) }}" class="group relative bg-slate-900 border border-slate-800 rounded-2xl p-6 transition-all duration-300 hover:scale-[1.02] hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/10 overflow-hidden">
                    <!-- Subtle Background Glow -->
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-600/10 blur-[50px] group-hover:bg-indigo-600/20 transition-all"></div>
                    
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600/20 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                            <span class="text-xl font-bold font-mono">{{ $bot['icon'] }}</span>
                        </div>
                        <div class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Active</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-400 transition-colors">{{ $bot['name'] }}</h2>
                    <p class="text-slate-400 text-sm mb-6 leading-relaxed">{{ $bot['description'] }}</p>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-800/50">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Messages</span>
                            <span class="text-lg font-mono text-indigo-300">{{ number_format($bot['count']) }}</span>
                        </div>
                        <div class="text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity transform group-hover:translate-x-1 duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </a>
                @endforeach
                
                <!-- Placeholder for future bots -->
                <div class="border border-dashed border-slate-800 rounded-2xl p-6 flex flex-col items-center justify-center opacity-40 hover:opacity-60 transition-opacity">
                    <div class="w-14 h-14 rounded-full border border-dashed border-slate-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Deploy New Bot</span>
                </div>
            </div>
            
            <p class="mt-16 text-center text-xs text-slate-600 uppercase tracking-widest font-semibold">
                Control Management Console • v1.2
            </p>
        </div>
    </main>

</body>
</html>
