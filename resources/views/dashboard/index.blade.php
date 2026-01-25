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
        <div class="flex items-center space-x-6">
            <!-- Notifications -->
            <button id="notifBtn" class="relative p-2 text-slate-400 hover:text-indigo-400 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span id="notifBadge" class="absolute top-1 right-1 bg-indigo-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
            </button>

            <!-- User Profile -->
            <div class="flex items-center space-x-3 cursor-pointer group" onclick="document.getElementById('profileModal').classList.remove('hidden')">
                <div class="text-right hidden md:block">
                    <p class="text-xs font-bold text-white leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-500 uppercase tracking-tighter">Admin</p>
                </div>
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-800 group-hover:border-indigo-500 transition-colors">
                @else
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black group-hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-600/20">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2 text-slate-500 hover:text-red-400 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
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

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white uppercase tracking-tight">User Profile</h2>
                <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Profile Photo URL</label>
                    <input type="url" name="profile_photo_url" value="{{ Auth::user()->profile_photo_url }}" required placeholder="https://..." 
                           class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-600/50">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20 active:scale-95">Update Picture</button>
            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notifModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </div>
                    <h2 class="text-lg font-black text-white uppercase tracking-tight">Notifications</h2>
                </div>
                <button id="closeModal" class="p-2 hover:bg-slate-800 rounded-xl text-slate-500 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <div id="notifList" class="max-h-[50vh] overflow-y-auto p-4 space-y-3">
                <div class="text-center py-10 text-slate-600 text-xs italic uppercase tracking-widest font-bold">Scanning for sync broadcasts...</div>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById('notifBtn');
        const modal = document.getElementById('notifModal');
        const close = document.getElementById('closeModal');
        const badge = document.getElementById('notifBadge');
        const list = document.getElementById('notifList');

        async function fetchNotifs() {
            try {
                const res = await fetch('{{ route('notifications') }}');
                const data = await res.json();
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.classList.remove('hidden');
                    list.innerHTML = data.map(n => `
                        <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl">
                            <h3 class="text-sm font-bold text-slate-200 mb-1 leading-tight">${n.titulo}</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed">${n.texto}</p>
                        </div>
                    `).join('');
                }
            } catch (e) {}
        }

        btn.addEventListener('click', () => {
             modal.classList.remove('hidden');
             fetchNotifs();
        });
        close.addEventListener('click', () => modal.classList.add('hidden'));
        window.addEventListener('click', (e) => { if(e.target === modal) modal.classList.add('hidden'); });
        fetchNotifs();
    </script>
</body>
</html>
