<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Accounts - Control Panel</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Outfit', sans-serif; color-scheme: dark; }
        .font-inter { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-200 overflow-hidden uppercase">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-950 backdrop-blur-md border-b border-white/10 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
                <span class="text-xl font-bold text-pink-600 font-inter group-hover/brand:text-pink-500 transition-colors">Mini Walee</span>
                <span class="text-slate-600 font-light text-xl italic font-inter">/</span>
                <span class="text-sm font-medium text-white tracking-wide uppercase font-inter group-hover/brand:text-pink-400 transition-colors">Control Panel</span>
            </a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <a href="{{ route('dashboard.facebook') }}" class="text-xs font-black text-slate-500 tracking-[0.2em] hover:text-white transition-colors uppercase font-inter">Facebook</a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <span class="text-xs font-black text-pink-500 tracking-[0.2em] uppercase font-inter">Accounts</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-pink-600/10 hover:bg-pink-600/20 border border-pink-500/20 rounded-xl transition-all group">
                <svg class="w-4 h-4 text-pink-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black text-pink-400 uppercase tracking-widest">Copilot</span>
            </a>
            <div class="relative">
                <button id="accountBtn" class="flex items-center justify-center w-10 h-10 bg-slate-800/50 border border-white/10 rounded-full hover:border-pink-500/30 transition-all focus:outline-none overflow-hidden group">
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ asset(Auth::user()->profile_photo_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    @else
                        <div class="w-full h-full bg-pink-600 flex items-center justify-center text-white text-xs font-black">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>

                <!-- Dropdown Menu -->
                <div id="accountDropdown" class="absolute right-0 mt-3 w-56 bg-slate-950 border border-white/10 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2">
                    <div class="px-4 py-3 border-b border-white/10 mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                        <p class="text-xs font-bold text-white truncate lowercase">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <button onclick="document.getElementById('profileModal').classList.remove('hidden'); closeAllDropdowns();" class="w-full flex items-center space-x-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/5 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Profile Settings</span>
                    </button>

                    <div class="my-2 border-t border-white/10"></div>

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
                <button id="notifBtn" class="relative p-2.5 text-slate-400 hover:text-pink-400 transition-colors focus:outline-none bg-white/5 rounded-full border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-0 right-0 bg-pink-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>

                <!-- Notifications Dropdown Content -->
                <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-slate-950 border border-white/10 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2 overflow-hidden">
                    <div class="p-4 border-b border-white/10 flex items-center justify-between">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest text-pink-500">Broadcasts</h3>
                        <span class="text-[9px] font-bold text-slate-500 uppercase">Live Feed</span>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto p-2 space-y-2">
                        <div class="text-center py-6 text-slate-700 dark:text-slate-600 text-[10px] italic uppercase tracking-widest lowercase">Scanning...</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-950 border border-white/10 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white uppercase tracking-tight">User Profile</h2>
                <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex flex-col items-center mb-4">
                    <div class="w-24 h-24 rounded-full border-2 border-pink-500/20 overflow-hidden bg-slate-950 mb-4 shadow-2xl">
                        <img id="profile_preview" src="{{ Auth::user()->profile_photo_url ?: 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4f46e5&color=fff' }}" class="w-full h-full object-cover">
                    </div>
                    <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">Active Identity Asset</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Profile Photo URL</label>
                    <input type="url" name="profile_photo_url" id="profile_url_input" value="{{ Auth::user()->profile_photo_url }}" required placeholder="https://..." 
                           oninput="document.getElementById('profile_preview').src = this.value || 'https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff'"
                           class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-pink-600/50">
                </div>
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 py-3 rounded-xl font-bold transition-all shadow-lg shadow-pink-600/20 active:scale-95 text-xs uppercase tracking-widest">Update Profile Asset</button>
            </form>
        </div>
    </div>
        <div class="max-w-7xl mx-auto">
            
            @if(session('success'))
            <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center shadow-lg animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black tracking-widest uppercase">{{ session('success') }}</span>
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">Connected <span class="text-pink-500">Facebook Accounts</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2 italic">Entity Management Interface</p>
                </div>
                
                <button onclick="document.getElementById('addAccountModal').classList.remove('hidden')" class="px-8 py-4 bg-pink-600 hover:bg-pink-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-pink-600/20 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Add Account
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-slate-950 border border-white/10 rounded-[3rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 bg-slate-900">
                                <th class="px-10 py-6 text-[10px] font-black text-pink-400 tracking-[0.2em] uppercase">Account Name</th>
                                <th class="px-8 py-6 text-[10px] font-black text-pink-400 tracking-[0.2em] uppercase">Page ID</th>
                                <th class="px-8 py-6 text-[10px] font-black text-pink-400 tracking-[0.2em] uppercase">Link</th>
                                <th class="px-10 py-6 text-[10px] font-black text-pink-400 tracking-[0.2em] uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($accounts as $account)
                            <tr class="group hover:bg-pink-500/5 transition-colors duration-300">
                                <td class="px-10 py-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 border border-white/10 flex items-center justify-center text-xs font-black text-pink-400 group-hover:border-pink-500/50 transition-all">
                                            {{ substr($account->name, 0, 1) }}
                                        </div>
                                        <span class="text-[11px] font-black text-white tracking-widest lowercase">{{ $account->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-[10px] font-bold text-slate-500 tracking-widest font-inter">#{{ $account->page_id }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <a href="{{ $account->link }}" target="_blank" class="text-[9px] font-black text-pink-500 hover:text-white underline underline-offset-4 tracking-[0.1em] lowercase flex items-center group/link">
                                        View Page
                                        <svg class="w-2.5 h-2.5 ml-1.5 opacity-0 group-hover/link:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <button onclick="openEditModal({{ json_encode($account) }})" class="w-10 h-10 inline-flex items-center justify-center bg-pink-500/10 text-pink-500 rounded-xl hover:bg-pink-500 hover:text-white transition-all border border-pink-500/10 mr-2 group/edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                    <form action="{{ route('dashboard.facebook.accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Disconnect this account?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all border border-red-500/10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-10 py-24 text-center opacity-30">
                                    <p class="text-xs font-black text-slate-500 tracking-[0.4em] uppercase italic">No entities connected</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Account Modal -->
    <div id="addAccountModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-white/10 w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-1 uppercase">Connect Account</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none uppercase">Social graph authentication interface</p>
                </div>
                <button onclick="document.getElementById('addAccountModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-500 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form action="{{ route('dashboard.facebook.accounts.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Account / Page Name</label>
                    <input type="text" name="name" required placeholder="e.g. Josh Dev Official" 
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all lowercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Page URL</label>
                    <input type="url" name="link" required placeholder="https://facebook.com/page-name" 
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all lowercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Page ID</label>
                    <input type="text" name="page_id" required placeholder="123456789012345" 
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Access Token</label>
                    <input type="text" name="access_token" required placeholder="EAAb..." 
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-pink-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em] uppercase">
                        Initialize Connection
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div id="editAccountModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-white/10 w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-1 uppercase">Edit Account</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none uppercase">Modify connection parameters</p>
                </div>
                <button onclick="document.getElementById('editAccountModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-500 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="editAccountForm" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')
                <input type="hidden" id="edit_id" name="id">
                
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Account / Page Name</label>
                    <input type="text" id="edit_name" name="name" required 
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all lowercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Page URL</label>
                    <input type="url" id="edit_link" name="link" required
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all lowercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Page ID</label>
                    <input type="text" id="edit_page_id" name="page_id" required 
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 uppercase">Access Token</label>
                    <input type="text" id="edit_access_token" name="access_token" required
                           class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-pink-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em] uppercase">
                        Update Connection
                    </button>
                </div>
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
            if (notifDropdown) notifDropdown.classList.add('opacity-0', 'invisible');
            if (accountDropdown) accountDropdown.classList.add('opacity-0', 'invisible');
        }

        async function fetchNotifs() {
            try {
                const res = await fetch('{{ route('notifications') }}');
                const data = await res.json();
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.classList.remove('hidden');
                    list.innerHTML = data.map(n => `
                        <div class="p-3 bg-slate-950 border border-white/10 rounded-xl hover:border-pink-500/30 transition-all text-left">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-[11px] font-bold text-white leading-tight normal-case">${n.titulo}</h3>
                                <span class="text-[9px] font-medium text-slate-500 whitespace-nowrap ml-2">${n.fecha_format}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 leading-relaxed normal-case">${n.texto}</p>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="text-center py-10 text-slate-700 dark:text-slate-600 text-[10px] uppercase font-bold">Clear Records</div>';
                }
            } catch (e) {}
        }

        if (notifBtn) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isVisible = !notifDropdown.classList.contains('invisible');
                closeAllDropdowns();
                if (!isVisible) {
                    notifDropdown.classList.remove('opacity-0', 'invisible');
                    fetchNotifs();
                }
            });
        }

        if (accountBtn) {
            accountBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isVisible = !accountDropdown.classList.contains('invisible');
                closeAllDropdowns();
                if (!isVisible) {
                    accountDropdown.classList.remove('opacity-0', 'invisible');
                }
            });
        }

        document.addEventListener('click', (e) => {
            if (notifDropdown && accountDropdown) {
                if (!notifDropdown.contains(e.target) && !accountDropdown.contains(e.target)) {
                    closeAllDropdowns();
                }
            }
        });

        fetchNotifs();

        function openEditModal(account) {
            document.getElementById('edit_name').value = account.name;
            document.getElementById('edit_link').value = account.link;
            document.getElementById('edit_page_id').value = account.page_id;
            document.getElementById('edit_access_token').value = account.access_token || '';
            let url = "{{ route('dashboard.facebook.accounts.update', ':id') }}";
            document.getElementById('editAccountForm').action = url.replace(':id', account.id);
            
            document.getElementById('editAccountModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
