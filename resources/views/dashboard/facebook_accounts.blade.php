<!DOCTYPE html>
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
    <nav class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-slate-800/50 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
                <span class="text-xl font-bold text-indigo-400 font-inter group-hover/brand:text-white transition-colors">josh dev</span>
                <span class="text-slate-700 font-light text-xl italic font-inter">/</span>
                <span class="text-sm font-medium text-slate-400 tracking-wide uppercase font-inter group-hover/brand:text-indigo-400 transition-colors">Control Panel</span>
            </a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <a href="{{ route('dashboard.facebook') }}" class="text-xs font-black text-slate-500 tracking-[0.2em] hover:text-white transition-colors uppercase font-inter">Facebook</a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <span class="text-xs font-black text-indigo-500 tracking-[0.2em] uppercase font-inter">Accounts</span>
        </div>
        <div class="flex items-center space-x-4">
            <button id="accountBtn" class="flex items-center justify-center w-10 h-10 bg-slate-800/50 border border-slate-800 rounded-full hover:border-indigo-500/30 transition-all focus:outline-none overflow-hidden group">
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                @else
                    <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-xs font-black">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-8 md:p-12">
        <div class="max-w-7xl mx-auto">
            
            @if(session('success'))
            <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center shadow-lg animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black tracking-widest uppercase">{{ session('success') }}</span>
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">Connected <span class="text-indigo-500">Facebook Accounts</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2 italic">Entity Management Interface</p>
                </div>
                
                <button onclick="document.getElementById('addAccountModal').classList.remove('hidden')" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-indigo-600/20 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Add Account
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-[3rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800/50 bg-slate-800/20">
                                <th class="px-10 py-6 text-[10px] font-black text-indigo-400 tracking-[0.2em] uppercase">Account Name</th>
                                <th class="px-8 py-6 text-[10px] font-black text-indigo-400 tracking-[0.2em] uppercase">Page ID</th>
                                <th class="px-8 py-6 text-[10px] font-black text-indigo-400 tracking-[0.2em] uppercase">Link</th>
                                <th class="px-10 py-6 text-[10px] font-black text-indigo-400 tracking-[0.2em] uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/30">
                            @forelse($accounts as $account)
                            <tr class="group hover:bg-indigo-500/5 transition-colors duration-300">
                                <td class="px-10 py-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-center text-xs font-black text-indigo-400 group-hover:border-indigo-500/50 transition-all">
                                            {{ substr($account->name, 0, 1) }}
                                        </div>
                                        <span class="text-[11px] font-black text-white tracking-widest lowercase">{{ $account->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-[10px] font-bold text-slate-400 tracking-widest font-inter">#{{ $account->page_id }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <a href="{{ $account->link }}" target="_blank" class="text-[9px] font-black text-indigo-500 hover:text-white underline underline-offset-4 tracking-[0.1em] lowercase flex items-center group/link">
                                        View Page
                                        <svg class="w-2.5 h-2.5 ml-1.5 opacity-0 group-hover/link:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                </td>
                                <td class="px-10 py-6 text-right">
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
        <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-1 uppercase">Connect Account</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none uppercase">Social graph authentication interface</p>
                </div>
                <button onclick="document.getElementById('addAccountModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form action="{{ route('dashboard.facebook.accounts.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Account / Page Name</label>
                    <input type="text" name="name" required placeholder="e.g. Josh Dev Official" 
                           class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all lowercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Page URL</label>
                    <input type="url" name="link" required placeholder="https://facebook.com/page-name" 
                           class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all lowercase">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Page ID</label>
                    <input type="text" name="page_id" required placeholder="123456789012345" 
                           class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em] uppercase">
                        Initialize Connection
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('accountBtn').addEventListener('click', () => {
            window.location.href = "{{ route('dashboard') }}";
        });
    </script>
</body>
</html>
