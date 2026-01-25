<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - josh dev</title>
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
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full flex bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Sidebar (ChatGPT style) -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col hidden md:flex">
        <div class="p-4 border-b border-slate-800 flex items-center justify-between">
            <span class="text-lg font-bold text-indigo-400">josh dev</span>
            <button class="p-1 hover:bg-slate-800 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-2 space-y-1">
            <div class="text-xs font-semibold text-slate-500 px-3 py-2 uppercase tracking-wider">History</div>
            @if(session('chat_history'))
                @foreach(array_reverse(session('chat_history')) as $msg)
                    @if($msg['role'] == 'user')
                    <div class="px-3 py-2 rounded-lg hover:bg-slate-800 cursor-pointer text-sm truncate">
                        {{ $msg['content'] }}
                    </div>
                    @endif
                @endforeach
            @else
                <div class="px-3 py-2 text-sm text-slate-500 italic">No threads found</div>
            @endif
        </div>
        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center space-x-3 text-sm font-medium">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white">JD</div>
                <span>Josh Dev</span>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col relative h-full">
        <!-- Navbar -->
        <nav class="h-16 bg-slate-950/80 backdrop-blur-md border-b border-slate-900 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center space-x-4 md:hidden">
                <span class="text-lg font-bold text-indigo-400">josh dev</span>
            </div>
            <div></div>
            <div class="flex items-center space-x-4">
                <button id="notifBtn" class="relative p-2 text-slate-400 hover:text-indigo-400 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-1 right-1 bg-indigo-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Logout</button>
                </form>
            </div>
        </nav>

        <!-- Chat Area -->
        <div class="flex-1 overflow-y-auto p-4 md:p-10 space-y-6 flex flex-col items-center">
            @if(!session('chat_history'))
                <div class="flex-1 flex flex-col items-center justify-center space-y-4 opacity-50">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke-width="1.5"/></svg>
                    </div>
                    <h2 class="text-xl font-medium text-slate-400 italic">How can I help you today?</h2>
                </div>
            @else
                <div class="w-full max-w-3xl space-y-8">
                    @foreach(session('chat_history') as $msg)
                        <div class="flex items-start space-x-4 {{ $msg['role'] == 'user' ? 'justify-end' : '' }}">
                            @if($msg['role'] != 'user')
                                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">JD</div>
                            @endif
                            <div class="max-w-[85%] px-4 py-2 rounded-2xl {{ $msg['role'] == 'user' ? 'bg-slate-800 text-slate-100' : 'bg-transparent text-slate-300' }}">
                                <p class="leading-relaxed">{{ $msg['content'] }}</p>
                            </div>
                            @if($msg['role'] == 'user')
                                <div class="w-8 h-8 rounded-lg bg-slate-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">U</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Input Area (ChatGPT style) -->
        <div class="p-4 md:p-8 bg-gradient-to-t from-slate-950 via-slate-950 to-transparent sticky bottom-0">
            <div class="max-w-3xl mx-auto relative group">
                <form action="{{ route('chat') }}" method="POST">
                    @csrf
                    <div class="relative flex items-center">
                        <input type="text" name="message" required autocomplete="off" 
                               class="w-full bg-slate-900 border border-slate-700/50 rounded-2xl px-6 py-4 pr-16 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-2xl placeholder-slate-500 text-slate-100" 
                               placeholder="Message josh dev...">
                        <button type="submit" class="absolute right-3 p-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl transition-all shadow-lg active:scale-95 disabled:opacity-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </form>
                <p class="text-[10px] text-center mt-3 text-slate-600 uppercase tracking-widest font-semibold">josh dev can make mistakes. Verify important info.</p>
            </div>
        </div>
    </main>

    <!-- Modal Notificaciones (Premium) -->
    <div id="notifModal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="notifContainer">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Notifications</h2>
                            <p class="text-xs text-slate-500">Your recent updates</p>
                        </div>
                    </div>
                    <button id="closeModal" class="p-2 hover:bg-slate-800 rounded-full text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
                <div id="notifList" class="max-h-[60vh] overflow-y-auto p-4 space-y-3">
                    <!-- Notifications JS -->
                    <div class="flex items-center justify-center py-10 opacity-50">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                    </div>
                </div>
                <div class="p-4 bg-slate-900 border-t border-slate-800 text-center">
                    <button class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">Mark all as read</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById('notifBtn');
        const modal = document.getElementById('notifModal');
        const container = document.getElementById('notifContainer');
        const list = document.getElementById('notifList');
        const close = document.getElementById('closeModal');
        const badge = document.getElementById('notifBadge');

        async function fetchNotifs() {
            try {
                const res = await fetch('{{ route('notifications') }}');
                const data = await res.json();
                
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.classList.remove('hidden');
                    
                    list.innerHTML = data.map(n => `
                        <div class="group p-4 bg-slate-800/30 hover:bg-indigo-500/5 border border-slate-800 hover:border-indigo-500/20 rounded-xl transition-all duration-200">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-bold text-indigo-400 uppercase tracking-tighter">${n.origen}</span>
                                <span class="text-[10px] text-slate-600 font-medium">${new Date(n.created_at).toLocaleDateString()}</span>
                            </div>
                            <h3 class="text-md font-bold text-slate-200 mb-1 leading-tight">${n.titulo}</h3>
                            <p class="text-sm text-slate-400 leading-relaxed">${n.texto}</p>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="text-center py-10 text-slate-500 text-sm italic">No notifications for you yet.</div>';
                }
            } catch (e) {
                list.innerHTML = '<div class="text-center py-10 text-red-500 text-sm">Error loading content.</div>';
            }
        }

        btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                container.classList.remove('scale-95', 'opacity-0');
            }, 10);
            fetchNotifs();
        });

        const hideModal = () => {
            container.classList.add('scale-95', 'opacity-0');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        };

        close.addEventListener('click', hideModal);
        modal.addEventListener('click', (e) => { if(e.target === modal) hideModal(); });

        // Initial badge fetch
        fetchNotifs();
    </script>
</body>
</html>
