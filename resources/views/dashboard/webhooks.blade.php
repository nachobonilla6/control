<!DOCTYPE html>
<html lang="en" class="h-full bg-white dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhooks - Control</title>
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
    </style>
</head>
<body class="h-full flex flex-col bg-white dark:bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-950 backdrop-blur-md border-b border-white/10 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
                <span class="text-xl font-bold text-pink-600 font-inter group-hover/brand:text-pink-500 transition-colors">Mini Walee</span>
                <span class="text-slate-600 font-light text-xl italic font-inter">/</span>
                <span class="text-sm font-medium text-white tracking-wide uppercase font-inter transition-colors">Control Panel</span>
            </a>
            <span class="text-slate-600 font-light text-xl italic font-inter">/</span>
            <a href="{{ route('dashboard.webhooks') }}" class="text-xs font-black text-pink-500 tracking-[0.2em] hover:text-white transition-colors uppercase font-inter">Webhooks</a>
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
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->email }}</p>
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
                <button id="notifBtn" class="relative p-2.5 text-white hover:text-pink-400 transition-colors focus:outline-none bg-white/10 rounded-full border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-0 right-0 bg-pink-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>

                <!-- Notifications Dropdown Content -->
                <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2 overflow-hidden">
                    <div class="p-4 border-b border-pink-200 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest text-pink-400">Broadcasts</h3>
                        <span class="text-[9px] font-bold text-slate-700 dark:text-slate-600 uppercase">Live Feed</span>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto p-2 space-y-2">
                        <div class="text-center py-6 text-slate-700 dark:text-slate-600 text-[10px] italic uppercase tracking-widest">Scanning...</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-6 md:p-10">
        <div class="max-w-7xl mx-auto">

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-sm font-bold uppercase tracking-widest">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-6 py-4 rounded-2xl flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-sm font-bold uppercase tracking-widest">{{ session('error') }}</span>
            </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- Form Column -->
                <div class="lg:col-span-1">
                    <div class="bg-slate-950 border border-white/10 rounded-3xl p-8 sticky top-0 shadow-2xl">
                        <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-tight flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-pink-600 flex items-center justify-center text-xs mr-3 text-slate-950">+</span>
                            New Webhook
                        </h2>
                        
                        <form action="{{ route('dashboard.webhooks.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Friendly Name</label>
                                <input type="text" name="name" required placeholder="e.g. n8n Production" 
                                       class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500/50 text-sm text-slate-200">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Endpoint URL</label>
                                <input type="url" name="url" required placeholder="https://..." 
                                       class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500/50 text-sm text-slate-200">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Payload (Text to Send)</label>
                                <textarea name="payload_text" rows="4" placeholder="Standard text or JSON structure..." 
                                          class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500/50 text-sm text-slate-200 resize-none"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-slate-900 dark:text-white font-bold py-4 rounded-xl shadow-lg shadow-pink-600/20 transition-all active:scale-95">
                                Register Pipeline
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List Column -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white uppercase tracking-tight">Active Pipelines</h2>
                        <span class="text-[10px] font-black text-pink-500 bg-pink-500/10 px-3 py-1 rounded-full border border-pink-500/20">
                            {{ count($webhooks) }} REGISTERED
                        </span>
                    </div>

                    <div class="space-y-6">
                        @foreach($webhooks as $webhook)
                        <div class="bg-slate-950 border border-white/10 rounded-2xl p-6 group hover:border-pink-500/30 transition-all shadow-lg relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-pink-600/5 blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-bold text-white">{{ $webhook->name }}</h3>
                                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[8px] font-black uppercase rounded border border-emerald-500/20">Operational</span>
                                    </div>
                                    <p class="text-xs font-mono text-slate-500 break-all mb-3">{{ $webhook->url }}</p>
                                    
                                    @if($webhook->payload_text)
                                    <div class="bg-white dark:bg-slate-950/50 rounded-lg p-3 border border-pink-200 dark:border-slate-800/50">
                                        <p class="text-[10px] font-bold text-pink-500 uppercase tracking-widest mb-1">Payload Content</p>
                                        <p class="text-[11px] text-slate-700 dark:text-slate-600 dark:text-slate-400 italic line-clamp-2">"{{ $webhook->payload_text }}"</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2 shrink-0 self-end md:self-center">
                                    <!-- Delete Webhook -->
                                    <form action="{{ route('dashboard.webhooks.destroy', $webhook->id) }}" method="POST" onsubmit="return confirm('Disconnect this pipeline?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove Pipeline" class="p-2.5 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-slate-900 dark:text-white transition-all border border-red-500/20">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>

                                    <!-- Edit Webhook -->
                                    <button onclick="openEditModal({{ json_encode($webhook) }})" title="Edit Pipeline" class="p-2.5 rounded-lg bg-slate-800/50 text-slate-700 dark:text-slate-600 dark:text-slate-400 border border-slate-700 hover:bg-slate-700 hover:text-slate-900 dark:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>

                                    <!-- Trigger Webhook -->
                                    <form action="{{ route('dashboard.webhooks.trigger', $webhook->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Activate Webhook" class="p-2.5 rounded-lg bg-pink-600/10 text-pink-400 border border-pink-500/20 hover:bg-pink-600 hover:text-slate-900 dark:text-white transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($webhooks->isEmpty())
                        <div class="border-2 border-dashed border-pink-200 dark:border-slate-800 rounded-3xl p-20 flex flex-col items-center justify-center opacity-20">
                            <svg class="w-12 h-12 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Waitng for active links</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="mt-20 border-t border-pink-200 dark:border-slate-800/50 pt-8 flex items-center justify-center space-x-10 opacity-30">
                <span class="text-[9px] font-black uppercase tracking-[0.3em]">AES-256 Link</span>
                <span class="text-[9px] font-black uppercase tracking-[0.3em]">Realtime Sync</span>
                <span class="text-[9px] font-black uppercase tracking-[0.3em]">Orchestration v1.3</span>
            </div>
        </div>
    </main>

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden bg-white dark:bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8 animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-tight">Identity Config</h2>
                <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Avatar Source URL</label>
                    <input type="url" name="profile_photo_url" value="{{ Auth::user()->profile_photo_url }}" required placeholder="https://..." 
                           class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-pink-600/50">
                </div>
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 py-3 rounded-xl font-bold transition-all shadow-lg active:scale-95">Sync Identity</button>
            </form>
        </div>
    </div>


    <!-- Edit Webhook Modal -->
    <div id="editWebhookModal" class="fixed inset-0 z-50 hidden bg-white dark:bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8 animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-tight">Edit Pipeline</h2>
                <button onclick="document.getElementById('editWebhookModal').classList.add('hidden')" class="text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form id="editWebhookForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Friendly Name</label>
                    <input type="text" name="name" id="edit_name" required 
                           class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-pink-600/50">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Endpoint URL</label>
                    <input type="url" name="url" id="edit_url" required 
                           class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-pink-600/50">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Payload (Text to Send)</label>
                    <textarea name="payload_text" id="edit_payload" rows="4" 
                              class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-pink-600/50 resize-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 py-3 rounded-xl font-bold transition-all shadow-lg active:scale-95">Update Configuration</button>
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

        function openEditModal(webhook) {
            const modal = document.getElementById('editWebhookModal');
            const form = document.getElementById('editWebhookForm');
            
            // Fill fields
            document.getElementById('edit_name').value = webhook.name;
            document.getElementById('edit_url').value = webhook.url;
            document.getElementById('edit_payload').value = webhook.payload_text || '';
            
            // Set action
            form.action = `/dashboard/webhooks/${webhook.id}`;
            
            modal.classList.remove('hidden');
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
