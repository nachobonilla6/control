<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Bots - Control</title>
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
            <a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <span class="text-xl font-bold text-white">AI Bots Management</span>
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
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">Bot Fleet</h1>
                    <p class="text-slate-500">Monitor and view history for available assistants.</p>
                </div>
                <button class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-600/20 transition-all active:scale-95">
                    Create New Bot
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($bots as $bot)
                <a href="{{ route('dashboard.history', $bot['id']) }}" class="group bg-slate-900 border border-slate-800 rounded-2xl p-6 transition-all hover:border-indigo-500/30 hover:bg-indigo-500/[0.02]">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20 font-mono font-bold text-lg">
                            {{ $bot['icon'] }}
                        </div>
                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase rounded-full border border-emerald-500/20">Active</span>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-2">{{ $bot['name'] }}</h2>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">{{ $bot['description'] }}</p>
                    <div class="pt-6 border-t border-slate-800/50 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-600 uppercase">Records</span>
                            <span class="text-indigo-400 font-mono">{{ number_format($bot['count']) }}</span>
                        </div>
                        <div class="p-2 rounded-lg bg-slate-800 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </main>

</body>
</html>
