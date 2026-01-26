<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - Control Panel</title>
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
        body { font-family: 'Outfit', sans-serif; }
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
            <a href="{{ route('dashboard.clients') }}" class="text-xs font-black text-indigo-500 tracking-[0.2em] hover:text-white transition-colors uppercase font-inter">Clients</a>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-indigo-600/10 hover:bg-indigo-600/20 border border-indigo-500/20 rounded-xl transition-all group">
                <svg class="w-4 h-4 text-indigo-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Copilot</span>
            </a>
            <!-- Account Dropdown -->
            <div class="relative">
                <button id="accountBtn" class="flex items-center justify-center w-10 h-10 bg-slate-800/50 border border-slate-800 rounded-full hover:border-indigo-500/30 transition-all focus:outline-none overflow-hidden group">
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ asset(Auth::user()->profile_photo_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    @else
                        <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-xs font-black">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>

                <!-- Dropdown Menu -->
                <div id="accountDropdown" class="absolute right-0 mt-3 w-56 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2">
                    <div class="px-4 py-3 border-b border-slate-800 mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                        <p class="text-xs font-bold text-white truncate lowercase">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <button onclick="document.getElementById('profileModal').classList.remove('hidden'); closeAllDropdowns();" class="w-full flex items-center space-x-3 px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Profile Settings</span>
                    </button>

                    <div class="my-2 border-t border-slate-800"></div>

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
                <button id="notifBtn" class="relative p-2.5 text-slate-400 hover:text-indigo-400 transition-colors focus:outline-none bg-slate-800/50 rounded-full border border-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-0 right-0 bg-indigo-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>

                <!-- Notifications Dropdown Content -->
                <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2 overflow-hidden">
                    <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest text-indigo-400">Broadcasts</h3>
                        <span class="text-[9px] font-bold text-slate-600 uppercase">Live Feed</span>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto p-2 space-y-2">
                        <div class="text-center py-6 text-slate-600 text-[10px] italic uppercase tracking-widest lowercase">Scanning...</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-8 md:p-12">
        <div class="max-w-7xl mx-auto">
            
            @if(session('success'))
            <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center shadow-lg animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black tracking-widest">{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-8 bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-2xl shadow-lg animate-in fade-in slide-in-from-top-4">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-[10px] font-black tracking-widest uppercase">Attention: Errors detected</span>
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-[9px] font-bold tracking-wider">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">Manage <span class="text-indigo-500">Clients</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2 text-center md:text-left">Business relationships database</p>
                </div>
                
                <div class="flex items-center space-x-6 bg-slate-900 border border-slate-800 p-2 rounded-2xl">
                    <div class="px-6 py-2 text-center border-r border-slate-800">
                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Total</p>
                        <p class="text-xl font-black text-white">{{ $totalClients }}</p>
                    </div>
                    <div class="px-6 py-2 text-center border-r border-slate-800">
                        <p class="text-[8px] font-black text-amber-500 uppercase tracking-widest mb-1">Queued</p>
                        <p class="text-xl font-black text-white">{{ $queuedCount }}</p>
                    </div>
                    <div class="px-6 py-2 text-center">
                        <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mb-1">Sent</p>
                        <p class="text-xl font-black text-white">{{ $sentCount }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard.templates') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all border border-slate-700 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Templates
                    </a>
                    <button onclick="openExtractModal()" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-emerald-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Extract
                    </button>
                    <button onclick="openCreateModal()" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-indigo-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        New Record
                    </button>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-950">
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Name / Company</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Contact</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Location</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Industry</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Status</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($clients as $client)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 font-black text-xs border border-indigo-500/10">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white leading-none mb-1">{{ $client->name }}</p>
                                            <p class="text-[9px] font-medium text-slate-500 lowercase">{{ $client->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-slate-400">{{ $client->phone ?: '---' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-slate-400">{{ $client->location ?: '---' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    @if($client->industry)
                                    <span class="px-3 py-1 bg-slate-950 border border-white/5 rounded-lg text-[9px] font-black text-slate-500">{{ $client->industry }}</span>
                                    @else
                                    <span class="text-slate-800 italic text-[10px]">No tag</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <form action="{{ route('dashboard.clients.toggle-status', $client->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Click to switch status" class="group/status">
                                            @if($client->status === 'sent')
                                                <span class="flex items-center text-[9px] font-black text-emerald-400 tracking-widest group-hover/status:text-amber-400 transition-colors">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                                    SENT
                                                </span>
                                            @else
                                                <span class="flex items-center text-[9px] font-black text-amber-400 tracking-widest group-hover/status:text-emerald-400 transition-colors">
                                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                                                    QUEUED
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button 
                                            onclick="openEditModal({{ json_encode($client) }})"
                                            class="w-9 h-9 flex items-center justify-center bg-indigo-600/10 text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white transition-all border border-indigo-500/10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                        <form action="{{ route('dashboard.clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Delete client from system?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all border border-red-500/10">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <p class="text-xs font-black text-slate-700 tracking-[0.3em] italic">No active client records found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($clients->hasPages())
                <div class="px-8 py-6 bg-slate-900/50 border-t border-slate-800/50">
                    {{ $clients->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="clientModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-7xl rounded-[2.5rem] overflow-hidden shadow-2xl p-8 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 id="modalTitle" class="text-2xl font-black text-white italic tracking-tighter mb-0.5">New Record</h2>
                    <p id="modalSubtitle" class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Register business entity • {{ $queuedCount }} QUEUED | {{ $sentCount }} SENT</p>
                </div>
                <button onclick="document.getElementById('clientModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Magic Paste AI Section -->
                <div id="aiSection" class="lg:col-span-3 bg-indigo-600/5 border border-indigo-500/20 rounded-3xl p-5 relative overflow-hidden group h-full">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-500/10 blur-2xl rounded-full"></div>
                    <label class="block text-[9px] font-black text-indigo-400 tracking-[0.2em] mb-4 uppercase flex items-center">
                        <svg class="w-3 h-3 mr-2 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/></svg>
                        Magic Paste (AI Extractor)
                    </label>
                    <textarea id="magicText" rows="4" placeholder="Paste email signature or client info here..." 
                              class="w-full bg-slate-950/50 border border-slate-800 rounded-2xl px-4 py-3 text-[11px] text-slate-300 focus:outline-none focus:border-indigo-500/50 transition-all resize-none mb-4 placeholder:text-slate-700 h-32"></textarea>
                    <button type="button" onclick="parseMagicText()" id="magicBtn" class="w-full py-3 bg-indigo-600/20 hover:bg-indigo-600 text-indigo-400 hover:text-white rounded-xl text-[9px] font-black tracking-widest uppercase transition-all flex items-center justify-center">
                        <span>Auto-Sync Data</span>
                    </button>
                    <div class="mt-6">
                        <p class="text-[8px] font-bold text-slate-600 uppercase tracking-[0.2em] leading-relaxed">
                            Automatically process names, emails, and phones using pattern recognition algorithms.
                        </p>
                    </div>
                </div>

                <!-- Form Section -->
                <div id="formContainer" class="lg:col-span-9">
                    <form id="clientForm" action="{{ route('dashboard.clients.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div id="methodField"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 px-1">Full Name / Company</label>
                                <input type="text" name="name" id="form_name" required placeholder="e.g. Tech Solutions S.A." 
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all normal-case">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 px-1">Contact Email</label>
                                <input type="email" name="email" id="form_email" required placeholder="client@example.com" 
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all normal-case">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 px-1">Location</label>
                                <input type="text" name="location" id="form_location" placeholder="City, Country" 
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all normal-case">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 px-1">Phone</label>
                                <input type="text" name="phone" id="form_phone" placeholder="+00 0000-0000" 
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 px-1">Industry / Sector</label>
                                <input type="text" name="industry" id="form_industry" placeholder="e.g. Automotive" 
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all normal-case">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 px-1">Operation Status</label>
                                <div class="relative">
                                    <select name="status" id="form_status" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                        <option value="queued">QUEUED</option>
                                        <option value="sent">SENT</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 pt-0">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-black py-4 rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                                Process Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Extract Modal -->
    <div id="extractModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl p-8 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-0.5">Extract Clients</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Filter & extract clients by language, country, city & industry</p>
                </div>
                <button onclick="document.getElementById('extractModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="extractForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Language -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">Language</label>
                        <div class="relative">
                            <select id="extract_language" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">Select Language</option>
                                <option value="english">English</option>
                                <option value="spanish">Spanish</option>
                                <option value="french">French</option>
                                <option value="portuguese">Portuguese</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">Country</label>
                        <div class="relative">
                            <select id="extract_country" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">Select Country</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">City</label>
                        <div class="relative">
                            <select id="extract_city" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">Select City</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Industry -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">Industry</label>
                        <div class="relative">
                            <select id="extract_industry" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">All Industries</option>
                                <option value="Technology">Technology</option>
                                <option value="Finance">Finance</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Retail">Retail</option>
                                <option value="Automotive">Automotive</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Hospitality">Hospitality</option>
                                <option value="Education">Education</option>
                                <option value="Energy">Energy</option>
                                <option value="Telecommunications">Telecommunications</option>
                                <option value="Construction">Construction</option>
                                <option value="Agriculture">Agriculture</option>
                                <option value="Media">Media</option>
                                <option value="Entertainment">Entertainment</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" onclick="extractClients(event)" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 rounded-2xl shadow-2xl shadow-emerald-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                        Extract Records
                    </button>
                    <button type="button" onclick="document.getElementById('extractModal').classList.add('hidden')" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Country data by language
        const countryData = {
            english: {
                usa: ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego'],
                uk: ['London', 'Manchester', 'Birmingham', 'Leeds', 'Glasgow', 'Liverpool', 'Newcastle', 'Sheffield'],
                canada: ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa', 'Edmonton', 'Winnipeg', 'Quebec City'],
                australia: ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide', 'Gold Coast', 'Canberra', 'Newcastle']
            },
            spanish: {
                spain: ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao', 'Malaga', 'Cordoba', 'Alicante'],
                mexico: ['Mexico City', 'Guadalajara', 'Monterrey', 'Cancun', 'Playa del Carmen', 'Puerto Vallarta', 'Los Cabos', 'Merida'],
                argentina: ['Buenos Aires', 'Cordoba', 'Rosario', 'Mendoza', 'La Plata', 'Tucuman', 'Mar del Plata', 'Salta'],
                colombia: ['Bogota', 'Medellin', 'Cali', 'Barranquilla', 'Cartagena', 'Bucaramanga', 'Santa Marta', 'Pereira']
            },
            french: {
                france: ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier'],
                belgium: ['Brussels', 'Antwerp', 'Ghent', 'Charleroi', 'Liege', 'Bruges', 'Namur', 'Louvain'],
                switzerland: ['Zurich', 'Geneva', 'Basel', 'Bern', 'Lausanne', 'Lucerne', 'St. Gallen', 'Winterthur'],
                canada: ['Montreal', 'Quebec City', 'Gatineau', 'Laval', 'Sherbrooke', 'Trois-Rivières', 'Saint-Jérôme', 'Saguenay']
            },
            portuguese: {
                brazil: ['São Paulo', 'Rio de Janeiro', 'Brasília', 'Salvador', 'Fortaleza', 'Belo Horizonte', 'Manaus', 'Curitiba'],
                portugal: ['Lisbon', 'Porto', 'Covilha', 'Covilha', 'Braga', 'Funchal', 'Aveiro', 'Viseu'],
                angola: ['Luanda', 'Benguela', 'Lobito', 'Namibe', 'Huambo', 'Kuito', 'Saurimo', 'Menongue'],
                mozambique: ['Maputo', 'Beira', 'Nampula', 'Chimoio', 'Quelimane', 'Tete', 'Inhambane', 'Xai-Xai']
            }
        };

        // Update country dropdown based on language
        document.getElementById('extract_language').addEventListener('change', function() {
            const language = this.value;
            const countrySelect = document.getElementById('extract_country');
            countrySelect.innerHTML = '<option value="">Select Country</option>';
            
            if (language && countryData[language]) {
                Object.keys(countryData[language]).forEach(country => {
                    const option = document.createElement('option');
                    option.value = country;
                    option.text = country.charAt(0).toUpperCase() + country.slice(1);
                    countrySelect.appendChild(option);
                });
            }
            
            // Reset city dropdown
            document.getElementById('extract_city').innerHTML = '<option value="">Select City</option>';
        });

        // Update city dropdown based on country
        document.getElementById('extract_country').addEventListener('change', function() {
            const language = document.getElementById('extract_language').value;
            const country = this.value;
            const citySelect = document.getElementById('extract_city');
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            if (language && country && countryData[language] && countryData[language][country]) {
                countryData[language][country].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.text = city;
                    citySelect.appendChild(option);
                });
            }
        });

        function openExtractModal() {
            document.getElementById('extract_language').value = '';
            document.getElementById('extract_country').innerHTML = '<option value="">Select Country</option>';
            document.getElementById('extract_city').innerHTML = '<option value="">Select City</option>';
            document.getElementById('extract_industry').value = '';
            document.getElementById('extractModal').classList.remove('hidden');
        }

        async function extractClients(event) {
            event.preventDefault();
            
            const language = document.getElementById('extract_language').value;
            const country = document.getElementById('extract_country').value;
            const city = document.getElementById('extract_city').value;
            const industry = document.getElementById('extract_industry').value;
            
            if (!language || !country || !city) {
                alert('Please fill in all required fields');
                return;
            }

            // Build the extraction text: country city industry (in one line)
            const extractionText = `${country} ${city} ${industry || ''}`.trim();

            try {
                // Send to n8n webhook
                const n8nResponse = await fetch('https://n8n.srv1137974.hstgr.cloud/webhook-test/extra-manual', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        language,
                        text: extractionText,
                        country,
                        city,
                        industry: industry || null,
                        user_id: '{{ Auth::id() }}',
                        user_email: '{{ Auth::user()->email }}'
                    })
                });

                const n8nData = await n8nResponse.json();

                if (n8nResponse.ok) {
                    alert('✓ Extraction initiated successfully!\nLanguage: ' + language + '\nData: ' + extractionText);
                    document.getElementById('extractModal').classList.add('hidden');
                    // Optionally reload the clients list
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (n8nData.message || 'Unknown error'));
                }
            } catch (e) {
                alert('Error processing extraction: ' + e.message);
            }
        }

        function openCreateModal() {
            const form = document.getElementById('clientForm');
            form.action = "{{ route('dashboard.clients.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('modalTitle').innerText = 'New Record';
            document.getElementById('modalSubtitle').innerText = 'Register business entity • {{ $queuedCount }} QUEUED | {{ $sentCount }} SENT';
            
            document.getElementById('form_name').value = '';
            document.getElementById('form_email').value = '';
            document.getElementById('form_location').value = '';
            document.getElementById('form_phone').value = '';
            document.getElementById('form_industry').value = '';
            document.getElementById('form_status').value = 'queued';
            document.getElementById('magicText').value = '';

            // Show AI section for new registration
            document.getElementById('aiSection').classList.remove('hidden');
            document.getElementById('formContainer').classList.replace('lg:col-span-full', 'lg:col-span-9');
            
            document.getElementById('clientModal').classList.remove('hidden');
        }

        function openEditModal(client) {
            const form = document.getElementById('clientForm');
            form.action = `/dashboard/clients/${client.id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            document.getElementById('modalTitle').innerText = 'Edit Client';
            document.getElementById('modalSubtitle').innerText = 'Updating: ' + client.name + ' • Status: ' + (client.status || 'queued').toUpperCase();
            
            document.getElementById('form_name').value = client.name;
            document.getElementById('form_email').value = client.email;
            document.getElementById('form_location').value = client.location || '';
            document.getElementById('form_phone').value = client.phone || '';
            document.getElementById('form_industry').value = client.industry || '';
            document.getElementById('form_status').value = client.status || 'queued';
            
            // Hide AI section for editing and make form full width
            document.getElementById('aiSection').classList.add('hidden');
            document.getElementById('formContainer').classList.replace('lg:col-span-9', 'lg:col-span-full');
            
            document.getElementById('clientModal').classList.remove('hidden');
        }

        async function parseMagicText() {
            const text = document.getElementById('magicText').value;
            if (!text) return;

            const btn = document.getElementById('magicBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span>Processing...</span>';
            btn.disabled = true;

            try {
                const response = await fetch('{{ route('dashboard.clients.parse') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ text })
                });
                
                const data = await response.json();
                
                if (data.name) document.getElementById('form_name').value = data.name;
                if (data.email) document.getElementById('form_email').value = data.email;
                if (data.phone) document.getElementById('form_phone').value = data.phone;
                if (data.location) document.getElementById('form_location').value = data.location;
                if (data.industry) document.getElementById('form_industry').value = data.industry;
                
                // Visual feedback
                document.getElementById('magicText').value = '';
                btn.innerHTML = '<span>Synced!</span>';
                btn.classList.replace('bg-indigo-600/20', 'bg-emerald-600/20');
                btn.classList.replace('text-indigo-400', 'text-emerald-400');
                
                setTimeout(() => {
                    btn.innerHTML = '<span>Auto-Sync Data</span>';
                    btn.classList.replace('bg-emerald-600/20', 'bg-indigo-600/20');
                    btn.classList.replace('text-emerald-400', 'text-indigo-400');
                    btn.disabled = false;
                }, 2000);

            } catch (e) {
                alert('Error processing text.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        // Standard Dashboard JS logic
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
                        <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl hover:border-indigo-500/30 transition-all text-left">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-[11px] font-bold text-slate-200 leading-tight">${n.titulo}</h3>
                                <span class="text-[9px] font-medium text-slate-600 whitespace-nowrap ml-2">${n.fecha_format}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 leading-relaxed">${n.texto}</p>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="text-center py-10 text-slate-600 text-[10px] uppercase font-bold">Clear Records</div>';
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
