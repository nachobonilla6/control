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
            <span class="text-sm font-medium text-slate-400 tracking-wide uppercase">Control Panel</span>
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
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-black text-white mb-2">System Orchestration</h1>
                <p class="text-slate-400">Select a category to manage your environment.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                <a href="{{ route($category['route']) }}" class="group relative bg-slate-900 border border-slate-800 rounded-3xl p-8 transition-all duration-300 hover:scale-[1.03] hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/10 active:scale-100">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-600/5 blur-[60px] group-hover:bg-indigo-600/15 transition-all"></div>
                    
                    <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-3xl mb-6 border border-indigo-500/20">
                        {{ $category['icon'] }}
                    </div>

                    <h2 class="text-2xl font-bold text-white mb-3 group-hover:text-indigo-400 transition-colors">{{ $category['name'] }}</h2>
                    <p class="text-slate-400 leading-relaxed mb-6">{{ $category['description'] }}</p>

                    <div class="flex items-center text-indigo-400 font-bold text-xs uppercase tracking-widest">
                        Configure
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </main>

</body>
</html>
