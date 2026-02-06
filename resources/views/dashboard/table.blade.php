<!DOCTYPE html>
<html lang="en" class="h-full bg-white dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - {{ $bot_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        
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
        .font-inter { font-family: 'Inter', sans-serif; }
        nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; }
        nav[role="navigation"] span, nav[role="navigation"] a {
            background-color: transparent !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        nav[role="navigation"] a:hover {
            color: #818cf8 !important;
            background-color: #1e293b !important;
        }
        nav[role="navigation"] .bg-white dark:bg-slate-950 {
            background-color: #4f46e5 !important;
            color: white !important;
            border-color: #4f46e5 !important;
        }
    </style>
</head>
<body class="h-full flex flex-col bg-white dark:bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-100 dark:bg-slate-900/50 backdrop-blur-md border-b border-pink-200 dark:border-slate-800/50 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
                <span class="text-xl font-bold text-pink-400 font-inter group-hover/brand:text-slate-900 dark:text-white transition-colors">Mini Walee</span>
                <span class="text-slate-700 font-light text-xl italic font-inter">/</span>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-600 dark:text-slate-400 tracking-wide uppercase font-inter group-hover/brand:text-pink-400 transition-colors">Control Panel</span>
            </a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <a href="{{ route('dashboard.bots') }}" class="text-xs font-black text-pink-400 hover:text-slate-900 dark:text-white transition-colors font-inter uppercase">AI Fleet</a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <span class="text-xs font-black text-pink-500 tracking-[0.2em] uppercase font-inter">{{ str_replace('-', ' ', $bot_id) }} logs</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-pink-600/10 hover:bg-pink-600/20 border border-pink-500/20 rounded-xl transition-all group">
                <svg class="w-4 h-4 text-pink-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black text-pink-400 uppercase tracking-widest">Copilot</span>
            </a>
            <!-- Account Dropdown -->
            <div class="relative">
                <button id="accountBtn" class="flex items-center justify-center w-10 h-10 bg-slate-800/50 border border-pink-200 dark:border-slate-800 rounded-full hover:border-pink-500/30 transition-all focus:outline-none overflow-hidden group">
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    @else
                        <div class="w-full h-full bg-pink-600 flex items-center justify-center text-slate-900 dark:text-white text-xs font-black">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>

                <!-- Dropdown Menu -->
                <div id="accountDropdown" class="absolute right-0 mt-3 w-56 bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2">
                    <div class="px-4 py-3 border-b border-pink-200 dark:border-slate-800 mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate lowercase">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <button onclick="document.getElementById('profileModal').classList.remove('hidden'); closeAllDropdowns();" class="w-full flex items-center space-x-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-pink-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Profile Settings</span>
                    </button>

                    <div class="my-2 border-t border-pink-200 dark:border-slate-800"></div>

                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-sm text-red-500 hover:bg-red-500/10 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="font-bold">Logout System</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notifications Dropdown -->
            <div class="relative">
                <button id="notifBtn" class="relative p-2.5 text-slate-700 dark:text-slate-600 dark:text-slate-400 hover:text-pink-400 transition-colors focus:outline-none bg-slate-800/50 rounded-full border border-pink-200 dark:border-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-0 right-0 bg-pink-600 text-[10px] font-bold text-slate-900 dark:text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>

                <!-- Notifications Dropdown Content -->
                <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2 overflow-hidden">
                    <div class="p-4 border-b border-pink-200 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest text-pink-400">Broadcasts</h3>
                        <span class="text-[9px] font-bold text-slate-700 dark:text-slate-600 uppercase">Live Feed</span>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto p-2 space-y-2">
                        <div class="text-center py-6 text-slate-700 dark:text-slate-600 text-[10px] italic uppercase tracking-widest lowercase">Scanning...</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-6 md:p-10">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-pink-600/10 flex items-center justify-center text-pink-400 border border-pink-500/20 font-mono font-bold">
                        H
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white uppercase tracking-tight">Transmission Logs</h1>
                        <p class="text-xs text-slate-500 font-medium font-mono">system.db.query(chat_history)</p>
                    </div>
                </div>
                <div class="bg-pink-600/10 border border-pink-500/20 px-4 py-2 rounded-xl">
                     <span class="text-pink-400 text-xs font-black">TOTAL ENTRIES: {{ $chat_history->total() }}</span>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-800/40">
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-pink-200 dark:border-slate-800">Index</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-pink-200 dark:border-slate-800">Thread ID</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-pink-200 dark:border-slate-800">Role</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-pink-200 dark:border-slate-800 w-1/2">Content</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-pink-200 dark:border-slate-800">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($chat_history as $row)
                            <tr class="hover:bg-pink-600/[0.02] transition-colors group">
                                <td class="px-6 py-4 text-xs font-mono text-pink-500">{{ $row->id }}</td>
                                <td class="px-6 py-4 text-[11px] text-slate-700 dark:text-slate-600 dark:text-slate-400 font-mono">{{ Str::limit($row->chat_id, 8, '') }}...</td>
                                <td class="px-6 py-4">
                                     <span class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest {{ $row->role == 'user' ? 'bg-blue-600/10 text-blue-400 border border-blue-600/20' : 'bg-pink-600/10 text-pink-400 border border-pink-600/20' }}">
                                        {{ $row->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-700 dark:text-slate-300">
                                    <div class="max-w-xl truncate group-hover:whitespace-normal group-hover:overflow-visible transition-all">
                                        {{ $row->message }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[10px] text-slate-500 whitespace-nowrap font-medium">
                                    {{ $row->created_at->format('M d, H:i:s') }}
                                </td>
                            </tr>
                            @endforeach
                            @if($chat_history->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-700 dark:text-slate-600 text-[10px] font-black uppercase tracking-widest italic">Data stream empty</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Paginacion -->
                @if($chat_history->hasPages())
                <div class="px-6 py-5 bg-slate-100 dark:bg-slate-900 border-t border-pink-200 dark:border-slate-800">
                    {{ $chat_history->links() }}
                </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                 <p class="text-[9px] font-black text-slate-700 uppercase tracking-[0.4em]">Integrated Telemetry Console</p>
            </div>

        </div>
    </main>

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden bg-white dark:bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-tight">Identity Management</h2>
                <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Avatar URL Path</label>
                    <input type="url" name="profile_photo_url" value="{{ Auth::user()->profile_photo_url }}" required placeholder="https://..." 
                           class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-pink-600/50">
                </div>
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 py-3 rounded-xl font-bold transition-all active:scale-95 shadow-lg">Commit Identity</button>
            </form>
        </div>
    </div>


    <script>
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const accountBtn = document.getElementById('accountBtn');
        const accountDropdown = document.getElementById('accountDropdown');
        const badge = document.getElementById('notifBadge');
        const list = document.getElementById('notifList');

        function closeAllDropdowns() {
            notifDropdown.classList.add('opacity-0', 'invisible');
            accountDropdown.classList.add('opacity-0', 'invisible');
        }

        async function fetchNotifs() {
            try {
                const res = await fetch('{{ route('notifications') }}');
                const data = await res.json();
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.classList.remove('hidden');
                    list.innerHTML = data.map(n => `
                        <div class="p-3 bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl hover:border-pink-500/30 transition-all text-left">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-[11px] font-bold text-slate-200 leading-tight">${n.titulo}</h3>
                                <span class="text-[9px] font-medium text-slate-700 dark:text-slate-600 whitespace-nowrap ml-2">${n.fecha_format}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 leading-relaxed">${n.texto}</p>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="text-center py-10 text-slate-700 dark:text-slate-600 text-[10px] uppercase font-bold">Transmission Stable</div>';
                }
            } catch (e) {}
        }

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = !notifDropdown.classList.contains('invisible');
            closeAllDropdowns();
            if (!isVisible) {
                notifDropdown.classList.remove('opacity-0', 'invisible');
                fetchNotifs();
            }
        });

        accountBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = !accountDropdown.classList.contains('invisible');
            closeAllDropdowns();
            if (!isVisible) {
                accountDropdown.classList.remove('opacity-0', 'invisible');
            }
        });

        document.addEventListener('click', (e) => {
            if (!notifDropdown.contains(e.target) && !accountDropdown.contains(e.target)) {
                closeAllDropdowns();
            }
        });
        fetchNotifs();
    </script>
</body>
</html>
