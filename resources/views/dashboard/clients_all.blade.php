<!DOCTYPE html>
<html lang="en" class="h-full bg-white dark:bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Clients - Control</title>
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
    </style>
</head>
<body class="h-full flex flex-col bg-white dark:bg-white text-slate-900 dark:text-slate-900 overflow-hidden">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-950 backdrop-blur-md border-b border-white/10 flex items-center justify-between px-6 sticky top-0 z-30">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
            <span class="text-xl font-bold text-pink-600 group-hover/brand:text-pink-500 transition-colors">Mini Walee</span>
            <span class="text-slate-600 font-light text-xl italic">/</span>
            <span class="text-sm font-medium text-white tracking-wide uppercase group-hover/brand:text-pink-400 transition-colors">Control Panel</span>
        </a>
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-pink-600/10 hover:bg-pink-600/20 border border-pink-500/20 rounded-xl transition-all group">
                <svg class="w-4 h-4 text-pink-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black text-pink-400 uppercase tracking-widest">Copilot</span>
            </a>
            <!-- Account Dropdown -->
            <div class="relative">
                <button id="accountBtn" class="flex items-center justify-center w-10 h-10 bg-slate-800/50 border border-pink-200 dark:border-slate-800 rounded-full hover:border-pink-500/30 transition-all focus:outline-none overflow-hidden group">
                    @if(Auth::user()->profile_photo_url)
                    <img src="{{ asset(Auth::user()->profile_photo_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
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
                        <p class="text-xs font-bold text-slate-900 dark:text-slate-900 truncate">{{ Auth::user()->email }}</p>
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

    <div class="flex-1 overflow-y-auto">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 italic tracking-tighter">All <span class="text-pink-500">Clients</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2">Complete client database</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('dashboard.clients') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all border border-blue-500/20 active:scale-95 flex items-center shadow-2xl shadow-blue-600/20">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 5h14v2H5V5zm0 6h14v2H5v-2zm0 6h14v2H5v-2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="currentColor"/></svg>
                        Queue
                    </a>
                    <a href="{{ route('dashboard.templates') }}" class="px-8 py-4 bg-pink-600 hover:bg-pink-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-pink-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Templates
                    </a>
                    <button onclick="openCreateModal()" class="px-8 py-4 bg-pink-600 hover:bg-pink-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-pink-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        New Client
                    </button>
                    <button onclick="openExtractModal()" class="px-8 py-4 bg-purple-600 hover:bg-purple-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-purple-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Extract
                    </button>
                    <button onclick="openUploadCSVModal()" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-emerald-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 16v-4m0 0V8m0 4H8m4 0h4M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Upload CSV
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard.clients.all') }}" class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name, email, website, phone, or industry..." 
                               class="w-full bg-slate-950 border border-white/10 rounded-2xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all placeholder:text-slate-500">
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <button type="submit" class="px-8 py-4 bg-pink-600 hover:bg-pink-500 text-slate-900 dark:text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-pink-600/20 active:scale-95">
                        Search
                    </button>
                    @if($search)
                    <a href="{{ route('dashboard.clients.all') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all border border-slate-700 active:scale-95">
                        Clear
                    </a>
                    @endif
                </form>
            </div>

            <!-- Clients Table -->
            <div class="bg-slate-950 border border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 bg-slate-900">
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Name / Company</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Website</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Location</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Industry</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Status</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($clients as $client)
                            <tr class="hover:bg-white/[0.02] transition-colors group"
                                data-client-id="{{ $client->id }}"
                                data-client-name="{{ addslashes($client->name) }}"
                                data-client-email="{{ $client->email }}"
                                data-client-email2="{{ $client->email2 }}"
                                data-client-website="{{ $client->website }}"
                                data-client-location="{{ $client->location }}"
                                data-client-address="{{ $client->address }}"
                                data-client-phone="{{ $client->phone }}"
                                data-client-language="{{ $client->language }}"
                                data-client-industry="{{ $client->industry }}"
                                data-client-contact-name="{{ $client->contact_name }}"
                                data-client-status="{{ $client->status }}"
                                data-client-facebook="{{ $client->facebook }}"
                                data-client-instagram="{{ $client->instagram }}"
                                data-client-opening-hours="{{ $client->opening_hours }}"
                                data-client-notes="{{ $client->notes }}">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-pink-600/10 flex items-center justify-center text-pink-400 font-black text-xs border border-pink-500/10">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white leading-none mb-1">{{ $client->name }}</p>
                                            <p class="text-[9px] font-medium text-slate-500 lowercase">{{ $client->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($client->website)
                                        <a href="{{ $client->website }}" target="_blank" class="text-xs font-bold text-pink-400 hover:text-indigo-300 break-all" title="{{ $client->website }}">{{ Str::limit($client->website, 45, '...') }}</a>
                                    @else
                                        <p class="text-xs font-bold text-slate-600">---</p>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-white">{{ $client->location ?: '---' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    @if($client->industry)
                                    <span class="inline-flex items-center px-3 py-1.5 bg-indigo-950/30 border border-pink-500/50 rounded-lg text-[9px] font-black text-pink-400 tracking-wider uppercase">{{ $client->industry }}</span>
                                    @else
                                    <span class="text-slate-600 italic text-[10px]">No tag</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    @if($client->status === 'sent')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-950/30 border border-emerald-500/50 rounded-lg text-[9px] font-black text-emerald-400 tracking-wider uppercase">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Sent
                                        </span>
                                    @elseif($client->status === 'extracted')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-yellow-950/30 border border-yellow-500/50 rounded-lg text-[9px] font-black text-yellow-400 tracking-wider uppercase">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                            Extracted
                                        </span>
                                    @elseif($client->status === 'queued')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-950/30 border border-blue-500/50 rounded-lg text-[9px] font-black text-blue-400 tracking-wider uppercase">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                            Queued
                                        </span>
                                    @elseif($client->status === 'created')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-cyan-950/30 border border-cyan-500/50 rounded-lg text-[9px] font-black text-cyan-400 tracking-wider uppercase">
                                            <span class="w-2 h-2 bg-cyan-500 rounded-full"></span>
                                            Created
                                        </span>
                                    @elseif($client->status === 'cancelled')
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-950/30 border border-red-500/50 rounded-lg text-[9px] font-black text-red-400 tracking-wider uppercase">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800/50 border border-slate-700 rounded-lg text-[9px] font-black text-slate-400 tracking-wider uppercase">
                                            <span class="w-2 h-2 bg-slate-600 rounded-full"></span>
                                            {{ $client->status ?: 'No Status' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            onclick="openEmailModal({{ $client->id }}, '{{ addslashes($client->name) }}', '{{ $client->email }}', '{{ $client->email2 }}')"
                                            class="w-9 h-9 flex items-center justify-center bg-emerald-600/10 text-emerald-400 rounded-xl hover:bg-emerald-600 hover:text-white transition-all border border-emerald-500/10"
                                            title="Send Email">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                        <button 
                                            onclick="openEditModal({{ json_encode($client) }})"
                                            class="w-9 h-9 flex items-center justify-center bg-pink-600/10 text-pink-400 rounded-xl hover:bg-pink-600 hover:text-white transition-all border border-pink-500/10">
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
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <p class="text-xs font-black text-slate-700 tracking-[0.3em] italic">No active clients found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-white/10 bg-slate-900">
                    {{ $clients->links() }}
                </div>
                <!-- Extract button moved to top next to Back -->
            </div>
        </div>
    </div>

    <!-- Client Edit Modal -->
    <div id="clientModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-950 border border-white/10 w-full max-w-4xl rounded-[2.5rem] overflow-hidden shadow-2xl p-6 md:p-8 animate-in fade-in zoom-in duration-300 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 id="modalTitle" class="text-2xl font-black text-white italic tracking-tighter mb-0.5">Edit Client</h2>
                    <p id="modalSubtitle" class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Update client information</p>
                </div>
                <button onclick="document.getElementById('clientModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-500 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <div id="formContainer" class="lg:col-span-full">
                <form id="clientForm" action="/dashboard/clients" method="POST" class="space-y-4">
                    @csrf
                    <div id="methodField"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Full Name / Company</label>
                            <input type="text" name="name" id="form_name" required placeholder="e.g. Tech Solutions S.A." 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Contact Email</label>
                            <input type="email" name="email" id="form_email" required placeholder="client@example.com" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Website</label>
                            <input type="url" name="website" id="form_website" placeholder="https://example.com" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Location</label>
                            <input type="text" name="location" id="form_location" placeholder="City, Country" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Phone</label>
                            <input type="text" name="phone" id="form_phone" placeholder="+00 0000-0000" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Industry / Sector</label>
                            <div class="relative">
                                <select name="industry" id="form_industry" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                    <option value="">-- Select Industry --</option>
                                    <option value="Agriculture / Farm Products">Agriculture / Farm Products</option>
                                    <option value="Organic Agriculture">Organic Agriculture</option>
                                    <option value="Association / Cooperative – Food & Consumer Products">Association / Cooperative – Food & Consumer Products</option>
                                    <option value="B2B Wholesaler – Container Sales">B2B Wholesaler – Container Sales</option>
                                    <option value="Organic Chain">Organic Chain</option>
                                    <option value="Cooperative Organic">Cooperative Organic</option>
                                    <option value="Organic Cosmetics">Organic Cosmetics</option>
                                    <option value="Chain">Chain</option>
                                    <option value="Supermarket Chain">Supermarket Chain</option>
                                    <option value="Specialized Chain">Specialized Chain</option>
                                    <option value="Cooperative">Cooperative</option>
                                    <option value="Cosmetics">Cosmetics</option>
                                    <option value="Discount">Discount</option>
                                    <option value="Discount / Independent Supermarkets">Discount / Independent Supermarkets</option>
                                    <option value="B2B Distributor">B2B Distributor</option>
                                    <option value="Insect Farming – Insect Proteins">Insect Farming – Insect Proteins</option>
                                    <option value="Fine & Local Grocery">Fine & Local Grocery</option>
                                    <option value="Local Grocery Store">Local Grocery Store</option>
                                    <option value="Farm + Shop">Farm + Shop</option>
                                    <option value="Certified Farm">Certified Farm</option>
                                    <option value="Artisan Cheese Producer">Artisan Cheese Producer</option>
                                    <option value="Wholesaler">Wholesaler</option>
                                    <option value="Organic Wholesaler">Organic Wholesaler</option>
                                    <option value="B2B Natural Products Wholesaler">B2B Natural Products Wholesaler</option>
                                    <option value="Hypermarket">Hypermarket</option>
                                    <option value="Organic Importer">Organic Importer</option>
                                    <option value="Organic Store">Organic Store</option>
                                    <option value="Agricultural Market">Agricultural Market</option>
                                    <option value="Honey & Beekeeping Products">Honey & Beekeeping Products</option>
                                    <option value="Artisan Bakery / Pastry Shop">Artisan Bakery / Pastry Shop</option>
                                    <option value="Farm Products">Farm Products</option>
                                    <option value="Local Agri-Food Promotion">Local Agri-Food Promotion</option>
                                    <option value="Online & Physical Clothing Store">Online & Physical Clothing Store</option>
                                    <option value="Supermarket">Supermarket</option>
                                    <option value="Independent Supermarket">Independent Supermarket</option>
                                    <option value="Organic Supermarket">Organic Supermarket</option>
                                    <option value="Neighborhood Supermarket">Neighborhood Supermarket</option>
                                    <option value="Family-Owned Supermarket">Family-Owned Supermarket</option>
                                    <option value="Premium Supermarket">Premium Supermarket</option>
                                    <option value="Regional Supermarket">Regional Supermarket</option>
                                    <option value="Specialized Supermarket">Specialized Supermarket</option>
                                    <option value="Food Processing & Food Packaging">Food Processing & Food Packaging</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Operation Status</label>
                            <div class="relative">
                                <select name="status" id="form_status" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                    <option value="">-- Select Status --</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row: Additional Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Secondary Email</label>
                            <input type="email" name="email2" id="form_email2" placeholder="alternative@example.com" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Address</label>
                            <input type="text" name="address" id="form_address" placeholder="Street address" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Language</label>
                            <input type="text" name="language" id="form_language" placeholder="e.g. Spanish, English" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Contact Name</label>
                            <input type="text" name="contact_name" id="form_contact_name" placeholder="Person to contact" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Facebook</label>
                            <input type="url" name="facebook" id="form_facebook" placeholder="Facebook profile URL" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Instagram</label>
                            <input type="url" name="instagram" id="form_instagram" placeholder="Instagram profile URL" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all normal-case">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Opening Hours</label>
                            <input type="text" name="opening_hours" id="form_opening_hours" placeholder="e.g. 9AM-5PM" 
                                   class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Notes</label>
                        <textarea name="notes" id="form_notes" rows="3" placeholder="Add any additional notes about the client..."
                                  class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-[11px] text-white focus:outline-none focus:border-pink-500/50 transition-all resize-none placeholder:text-slate-600"></textarea>
                    </div>

                    <div class="flex items-center space-x-4 pt-0">
                        <button type="submit" class="flex-1 bg-pink-600 hover:bg-pink-500 text-white font-black py-4 rounded-2xl shadow-2xl shadow-pink-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                            Update Client
                        </button>
                        <button type="button" onclick="document.getElementById('clientModal').classList.add('hidden')" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Extract Modal -->
    <div id="extractModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-950 border border-white/10 w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl p-8 animate-in fade-in zoom-in duration-300 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-0.5 uppercase">Extract Clients</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none uppercase">Filter & extract clients by language, country, city & industry</p>
                </div>
                <button onclick="document.getElementById('extractModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-500 hover:text-white transition-all">
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
                            <select id="extract_language" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">Select Language</option>
                                <option value="english">English</option>
                                <option value="mandarin">Mandarin Chinese</option>
                                <option value="spanish">Spanish</option>
                                <option value="hindi">Hindi</option>
                                <option value="arabic">Arabic</option>
                                <option value="french">French</option>
                                <option value="bengali">Bengali</option>
                                <option value="portuguese">Portuguese</option>
                                <option value="russian">Russian</option>
                                <option value="japanese">Japanese</option>
                                <option disabled class="text-pink-400 font-black uppercase text-[10px] bg-slate-800">—— European Languages ——</option>
                                <option value="german">German</option>
                                <option value="italian">Italian</option>
                                <option value="dutch">Dutch</option>
                                <option value="norwegian">Norwegian</option>
                                <option value="danish">Danish</option>
                                <option value="polish">Polish</option>
                                <option value="greek">Greek</option>
                                <option value="czech">Czech</option>
                                <option disabled class="text-pink-400 font-black uppercase text-[10px] bg-slate-800">—— African Languages ——</option>
                                <option value="swahili">Swahili</option>
                                <option value="amharic">Amharic</option>
                                <option value="yoruba">Yoruba</option>
                                <option value="zulu">Zulu</option>
                                <option value="hausa">Hausa</option>
                                <option value="oromo">Oromo</option>
                                <option disabled class="text-pink-400 font-black uppercase text-[10px] bg-slate-800">—— Asian Languages ——</option>
                                <option value="korean">Korean</option>
                                <option value="turkish">Turkish</option>
                                <option value="vietnamese">Vietnamese</option>
                                <option value="thai">Thai</option>
                                <option value="malay">Malay / Indonesian</option>
                                <option value="punjabi">Punjabi</option>
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
                            <select id="extract_country" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
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
                            <select id="extract_city" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
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
                            <select id="extract_industry" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">All Industries</option>
                                <option value="Agriculture / Farm Products">Agriculture / Farm Products</option>
                                <option value="Organic Agriculture">Organic Agriculture</option>
                                <option value="Association / Cooperative – Food & Consumer Products">Association / Cooperative – Food & Consumer Products</option>
                                <option value="B2B Wholesaler – Container Sales">B2B Wholesaler – Container Sales</option>
                                <option value="Organic Chain">Organic Chain</option>
                                <option value="Cooperative Organic">Cooperative Organic</option>
                                <option value="Organic Cosmetics">Organic Cosmetics</option>
                                <option value="Chain">Chain</option>
                                <option value="Supermarket Chain">Supermarket Chain</option>
                                <option value="Specialized Chain">Specialized Chain</option>
                                <option value="Cooperative">Cooperative</option>
                                <option value="Cosmetics">Cosmetics</option>
                                <option value="Discount">Discount</option>
                                <option value="Discount / Independent Supermarkets">Discount / Independent Supermarkets</option>
                                <option value="B2B Distributor">B2B Distributor</option>
                                <option value="Insect Farming – Insect Proteins">Insect Farming – Insect Proteins</option>
                                <option value="Fine & Local Grocery">Fine & Local Grocery</option>
                                <option value="Local Grocery Store">Local Grocery Store</option>
                                <option value="Farm + Shop">Farm + Shop</option>
                                <option value="Certified Farm">Certified Farm</option>
                                <option value="Artisan Cheese Producer">Artisan Cheese Producer</option>
                                <option value="Wholesaler">Wholesaler</option>
                                <option value="Organic Wholesaler">Organic Wholesaler</option>
                                <option value="B2B Natural Products Wholesaler">B2B Natural Products Wholesaler</option>
                                <option value="Hypermarket">Hypermarket</option>
                                <option value="Organic Importer">Organic Importer</option>
                                <option value="Organic Store">Organic Store</option>
                                <option value="Agricultural Market">Agricultural Market</option>
                                <option value="Honey & Beekeeping Products">Honey & Beekeeping Products</option>
                                <option value="Artisan Bakery / Pastry Shop">Artisan Bakery / Pastry Shop</option>
                                <option value="Farm Products">Farm Products</option>
                                <option value="Local Agri-Food Promotion">Local Agri-Food Promotion</option>
                                <option value="Online & Physical Clothing Store">Online & Physical Clothing Store</option>
                                <option value="Supermarket">Supermarket</option>
                                <option value="Independent Supermarket">Independent Supermarket</option>
                                <option value="Organic Supermarket">Organic Supermarket</option>
                                <option value="Neighborhood Supermarket">Neighborhood Supermarket</option>
                                <option value="Family-Owned Supermarket">Family-Owned Supermarket</option>
                                <option value="Premium Supermarket">Premium Supermarket</option>
                                <option value="Regional Supermarket">Regional Supermarket</option>
                                <option value="Specialized Supermarket">Specialized Supermarket</option>
                                <option value="Food Processing & Food Packaging">Food Processing & Food Packaging</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" onclick="extractClients(event)" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 rounded-2xl shadow-2xl shadow-emerald-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                        Extract Clients
                    </button>
                    <button type="button" onclick="document.getElementById('extractModal').classList.add('hidden')" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let clientStatuses = [];
        let emailTemplates = [];

        // Define populateStatusSelect early so it's available to modals
        function populateStatusSelect() {
            const select = document.getElementById('form_status');
            const currentValue = select.value; // Preserve current value
            
            // Keep the default option
            select.innerHTML = '<option value="">-- Select Status --</option>';
            
            if (clientStatuses && clientStatuses.length > 0) {
                clientStatuses.forEach(status => {
                    const option = document.createElement('option');
                    option.value = status.name;
                    option.textContent = status.label;
                    select.appendChild(option);
                });
            } else {
                // Fallback if statuses not loaded
                const fallbackStatuses = [
                    {name: 'extracted', label: 'EXTRACTED'},
                    {name: 'queued', label: 'QUEUED'},
                    {name: 'sent', label: 'SENT'}
                ];
                fallbackStatuses.forEach(status => {
                    const option = document.createElement('option');
                    option.value = status.name;
                    option.textContent = status.label;
                    select.appendChild(option);
                });
            }
            
            // Restore previous value if it was set
            if (currentValue) {
                select.value = currentValue;
            }
        }

        function openCreateModal() {
            try {
                const form = document.getElementById('clientForm');
                form.action = "{{ route('dashboard.clients.store') }}";
                document.getElementById('methodField').innerHTML = '';
                document.getElementById('modalTitle').innerText = 'New Client';
                document.getElementById('modalSubtitle').innerText = 'Add a new client to the database';
                
                document.getElementById('form_name').value = '';
                document.getElementById('form_email').value = '';
                document.getElementById('form_website').value = '';
                document.getElementById('form_location').value = '';
                document.getElementById('form_phone').value = '';
                document.getElementById('form_industry').value = '';
                document.getElementById('form_email2').value = '';
                document.getElementById('form_address').value = '';
                document.getElementById('form_language').value = '';
                document.getElementById('form_contact_name').value = '';
                document.getElementById('form_facebook').value = '';
                document.getElementById('form_instagram').value = '';
                document.getElementById('form_opening_hours').value = '';
                document.getElementById('form_notes').value = '';
                
                // Ensure statuses are populated before opening modal
                if (!clientStatuses || clientStatuses.length === 0) {
                    populateStatusSelect();
                }
                document.getElementById('form_status').value = 'extracted';
                
                document.getElementById('clientModal').classList.remove('hidden');
            } catch (e) {
                console.error('Error opening modal:', e);
                alert('Error opening modal: ' + e.message);
            }
        }

        function openEditModal(client) {
            try {
                const form = document.getElementById('clientForm');
                form.action = `/dashboard/clients/${client.id}`;
                document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
                document.getElementById('modalTitle').innerText = 'Edit Client';
                document.getElementById('modalSubtitle').innerText = 'Updating: ' + client.name + ' • Status: ' + (client.status || 'extracted').toUpperCase();
                
                document.getElementById('form_name').value = client.name;
                document.getElementById('form_email').value = client.email;
                document.getElementById('form_website').value = client.website || '';
                document.getElementById('form_location').value = client.location || '';
                document.getElementById('form_phone').value = client.phone || '';
                document.getElementById('form_industry').value = client.industry || '';
                document.getElementById('form_email2').value = client.email2 || '';
                document.getElementById('form_address').value = client.address || '';
                document.getElementById('form_language').value = client.language || '';
                document.getElementById('form_contact_name').value = client.contact_name || '';
                document.getElementById('form_facebook').value = client.facebook || '';
                document.getElementById('form_instagram').value = client.instagram || '';
                document.getElementById('form_opening_hours').value = client.opening_hours || '';
                document.getElementById('form_notes').value = client.notes || '';
                
                // Ensure statuses are populated before opening modal
                if (!clientStatuses || clientStatuses.length === 0) {
                    populateStatusSelect();
                }
                document.getElementById('form_status').value = client.status || 'extracted';
                // Preserve current pagination/search so after update we return to the same page
                try {
                    const urlParams = new URLSearchParams(window.location.search);
                    const page = urlParams.get('page') || '';
                    const search = urlParams.get('search') || '';

                    const form = document.getElementById('clientForm');
                    let inputPage = form.querySelector('input[name="return_page"]');
                    if (!inputPage) {
                        inputPage = document.createElement('input');
                        inputPage.type = 'hidden';
                        inputPage.name = 'return_page';
                        form.appendChild(inputPage);
                    }
                    inputPage.value = page;

                    let inputSearch = form.querySelector('input[name="return_search"]');
                    if (!inputSearch) {
                        inputSearch = document.createElement('input');
                        inputSearch.type = 'hidden';
                        inputSearch.name = 'return_search';
                        form.appendChild(inputSearch);
                    }
                    inputSearch.value = search;

                    let inputFromView = form.querySelector('input[name="from_view"]');
                    if (!inputFromView) {
                        inputFromView = document.createElement('input');
                        inputFromView.type = 'hidden';
                        inputFromView.name = 'from_view';
                        form.appendChild(inputFromView);
                    }
                    inputFromView.value = 'clients_all';
                } catch (e) {
                    console.error('Could not set return page/search:', e);
                }

                document.getElementById('clientModal').classList.remove('hidden');
            } catch (e) {
                console.error('Error opening edit modal:', e);
                alert('Error opening modal: ' + e.message);
            }
        }

        // Country data by language
        const countryData = {
            english: { usa: ['New York', 'Los Angeles', 'Chicago'], uk: ['London', 'Manchester'], canada: ['Toronto', 'Vancouver'], australia: ['Sydney', 'Melbourne'], ireland: ['Dublin'], new_zealand: ['Auckland'], south_africa: ['Cape Town', 'Johannesburg'], nigeria: ['Lagos', 'Abuja'], kenya: ['Nairobi', 'Mombasa'], ghana: ['Accra'], singapore: ['Singapore'], philippines: ['Manila'], india: ['Delhi', 'Mumbai'], jamaica: ['Kingston'] },
            mandarin: { china: ['Beijing', 'Shanghai', 'Guangzhou'], taiwan: ['Taipei'], singapore: ['Singapore'], malaysia: ['Kuala Lumpur'] },
            spanish: { spain: ['Madrid', 'Barcelona'], mexico: ['Mexico City', 'Guadalajara'], argentina: ['Buenos Aires'], colombia: ['Bogota'], chile: ['Santiago'], peru: ['Lima'], venezuela: ['Caracas'], usa: ['Miami', 'Los Angeles'], equatorial_guinea: ['Malabo'], cuba: ['Havana'], dominican_republic: ['Santo Domingo'], costa_rica: ['San Jose'], panama: ['Panama City'] },
            hindi: { india: ['Mumbai', 'Delhi', 'Bangalore'], fiji: ['Suva'], mauritius: ['Port Louis'] },
            arabic: { saudi_arabia: ['Riyadh', 'Jeddah'], uae: ['Dubai', 'Abu Dhabi'], egypt: ['Cairo'], morocco: ['Casablanca', 'Rabat'], algeria: ['Algiers'], tunisia: ['Tunis'], iraq: ['Baghdad'], jordan: ['Amman'], lebanon: ['Beirut'], kuwait: ['Kuwait City'], qatar: ['Doha'], bahrain: ['Manama'], oman: ['Muscat'], yemen: ['Sanaa'], libya: ['Tripoli'], sudan: ['Khartoum'], palestine: ['Ramallah'] },
            french: { france: ['Paris', 'Lyon'], canada: ['Montreal', 'Quebec City'], belgium: ['Brussels'], switzerland: ['Geneva'], senegal: ['Dakar'], ivory_coast: ['Abidjan'], cameroon: ['Yaounde', 'Douala'], congo: ['Kinshasa', 'Brazzaville'], madagascar: ['Antananarivo'], morocco: ['Casablanca'], algeria: ['Algiers'], tunisia: ['Tunis'], haiti: ['Port-au-Prince'] },
            bengali: { bangladesh: ['Dhaka', 'Chittagong'], india: ['Kolkata'] },
            portuguese: { brazil: ['São Paulo', 'Rio de Janeiro'], portugal: ['Lisbon', 'Porto'], angola: ['Luanda'], mozambique: ['Maputo'], cape_verde: ['Praia'], guinea_bissau: ['Bissau'], timor_leste: ['Dili'] },
            russian: { russia: ['Moscow', 'Saint Petersburg'], kazakhstan: ['Almaty'], belarus: ['Minsk'], kyrgyzstan: ['Bishkek'] },
            japanese: { japan: ['Tokyo', 'Osaka', 'Kyoto'] },
            german: { germany: ['Berlin', 'Munich'], austria: ['Vienna'], switzerland: ['Zurich'], luxembourg: ['Luxembourg'], liechtenstein: ['Vaduz'] },
            italian: { italy: ['Rome', 'Milan', 'Florence'], switzerland: ['Lugano'], san_marino: ['San Marino'] },
            dutch: { netherlands: ['Amsterdam', 'Rotterdam'], belgium: ['Antwerp'], suriname: ['Paramaribo'] },
            norwegian: { norway: ['Oslo', 'Bergen'] },
            danish: { denmark: ['Copenhagen', 'Aarhus'] },
            polish: { poland: ['Warsaw', 'Krakow'] },
            greek: { greece: ['Athens', 'Thessaloniki'], cyprus: ['Nicosia'] },
            czech: { czech_republic: ['Prague', 'Brno'] },
            swahili: { kenya: ['Nairobi', 'Mombasa'], tanzania: ['Dar es Salaam'], uganda: ['Kampala'], rwanda: ['Kigali'], burundi: ['Bujumbura'], dr_congo: ['Goma'] },
            amharic: { ethiopia: ['Addis Ababa'] },
            yoruba: { nigeria: ['Lagos', 'Ibadan'], benin: ['Porto-Novo'], topo: ['Lome'] },
            zulu: { south_africa: ['Durban', 'Pietermaritzburg'] },
            hausa: { nigeria: ['Kano', 'Kaduna'], niger: ['Niamey'], ghana: ['Accra'], cameroon: ['Maroua'] },
            oromo: { ethiopia: ['Adama', 'Jimma'], kenya: ['Marsabit'] },
            korean: { south_korea: ['Seoul', 'Busan'] },
            turkish: { turkey: ['Istanbul', 'Ankara'], cyprus: ['Kyrenia'] },
            vietnamese: { vietnam: ['Ho Chi Minh City', 'Hanoi'] },
            thai: { thailand: ['Bangkok', 'Chiang Mai'] },
            malay: { malaysia: ['Kuala Lumpur'], indonesia: ['Jakarta'], singapore: ['Singapore'], brunei: ['Bandar Seri Begawan'] },
            punjabi: { pakistan: ['Lahore'], india: ['Amritsar', 'Ludhiana'] }
        };

        const countryNames = {
            english: { usa: 'USA', uk: 'UK', canada: 'Canada', australia: 'Australia', ireland: 'Ireland', new_zealand: 'New Zealand', south_africa: 'South Africa', nigeria: 'Nigeria', kenya: 'Kenya', ghana: 'Ghana', singapore: 'Singapore', philippines: 'Philippines', india: 'India', jamaica: 'Jamaica' },
            mandarin: { china: 'China', taiwan: 'Taiwan', singapore: 'Singapore', malaysia: 'Malaysia' },
            spanish: { spain: 'Spain', mexico: 'Mexico', argentina: 'Argentina', colombia: 'Colombia', chile: 'Chile', peru: 'Peru', venezuela: 'Venezuela', usa: 'USA (ES)', equatorial_guinea: 'Equatorial Guinea', cuba: 'Cuba', dominican_republic: 'Dominican Republic', costa_rica: 'Costa Rica', panama: 'Panama' },
            hindi: { india: 'India', fiji: 'Fiji', mauritius: 'Mauritius' },
            arabic: { saudi_arabia: 'Saudi Arabia', uae: 'UAE', egypt: 'Egypt', morocco: 'Morocco', algeria: 'Algeria', tunisia: 'Tunisia', iraq: 'Iraq', jordan: 'Jordan', lebanon: 'Lebanon', kuwait: 'Kuwait', qatar: 'Qatar', bahrain: 'Bahrain', oman: 'Oman', yemen: 'Yemen', libya: 'Libya', sudan: 'Sudan', palestine: 'Palestine' },
            french: { france: 'France', canada: 'Canada (FR)', belgium: 'Belgium', switzerland: 'Switzerland', senegal: 'Senegal', ivory_coast: 'Ivory Coast', cameroon: 'Cameroon', congo: 'Congo', madagascar: 'Madagascar', morocco: 'Morocco (FR)', algeria: 'Algeria (FR)', tunisia: 'Tunisia (FR)', haiti: 'Haiti' },
            bengali: { bangladesh: 'Bangladesh', india: 'India (WB)' },
            portuguese: { brazil: 'Brazil', portugal: 'Portugal', angola: 'Angola', mozambique: 'Mozambique', cape_verde: 'Cape Verde', guinea_bissau: 'Guinea-Bissau', timor_leste: 'Timor-Leste' },
            russian: { russia: 'Russia', kazakhstan: 'Kazakhstan', belarus: 'Belarus', kyrgyzstan: 'Kyrgyzstan' },
            japanese: { japan: 'Japan' },
            german: { germany: 'Germany', austria: 'Austria', switzerland: 'Switzerland', luxembourg: 'Luxembourg', liechtenstein: 'Liechtenstein' },
            italian: { italy: 'Italy', switzerland: 'Switzerland (IT)', san_marino: 'San Marino' },
            dutch: { netherlands: 'Netherlands', belgium: 'Belgium (NL)', suriname: 'Suriname' },
            norwegian: { norway: 'Norway' },
            danish: { denmark: 'Denmark' },
            polish: { poland: 'Poland' },
            greek: { greece: 'Greece', cyprus: 'Cyprus (GR)' },
            czech: { czech_republic: 'Czech Republic' },
            swahili: { kenya: 'Kenya', tanzania: 'Tanzania', uganda: 'Uganda', rwanda: 'Rwanda', burundi: 'Burundi', dr_congo: 'DR Congo' },
            amharic: { ethiopia: 'Ethiopia' },
            yoruba: { nigeria: 'Nigeria (YO)', benin: 'Benin', topo: 'Togo' },
            zulu: { south_africa: 'South Africa (ZU)' },
            hausa: { nigeria: 'Nigeria (HA)', niger: 'Niger', ghana: 'Ghana (HA)', cameroon: 'Cameroon (HA)' },
            oromo: { ethiopia: 'Ethiopia (OR)', kenya: 'Kenya (OR)' },
            korean: { south_korea: 'South Korea' },
            turkish: { turkey: 'Turkey', cyprus: 'Cyprus (TR)' },
            vietnamese: { vietnam: 'Vietnam' },
            thai: { thailand: 'Thailand' },
            malay: { malaysia: 'Malaysia', indonesia: 'Indonesia', singapore: 'Singapore (ML)', brunei: 'Brunei' },
            punjabi: { pakistan: 'Pakistan (PU)', india: 'India (PU)' }
        };

        function openExtractModal() {
            document.getElementById('extract_language').value = '';
            document.getElementById('extract_country').value = '';
            document.getElementById('extract_city').value = '';
            document.getElementById('extract_industry').value = '';
            document.getElementById('extractModal').classList.remove('hidden');
        }

        document.getElementById('extract_language').addEventListener('change', function() {
            const language = this.value;
            const countrySelect = document.getElementById('extract_country');
            countrySelect.innerHTML = '<option value="">Select Country</option>';
            
            if (language && countryData[language]) {
                Object.keys(countryData[language]).forEach(key => {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = countryNames[language][key];
                    countrySelect.appendChild(option);
                });
            }
            document.getElementById('extract_city').innerHTML = '<option value="">Select City</option>';
        });

        document.getElementById('extract_country').addEventListener('change', function() {
            const language = document.getElementById('extract_language').value;
            const country = this.value;
            const citySelect = document.getElementById('extract_city');
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            if (language && country && countryData[language][country]) {
                countryData[language][country].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            }
        });

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

            // Language code mapping
            const languageCode = {
                'english': 'eng',
                'mandarin': 'cmn',
                'spanish': 'spa',
                'hindi': 'hin',
                'arabic': 'ara',
                'french': 'fra',
                'bengali': 'ben',
                'portuguese': 'por',
                'russian': Russian = 'rus',
                'japanese': 'jpn',
                'german': 'deu',
                'italian': 'ita',
                'dutch': 'nld',
                'norwegian': 'nor',
                'danish': 'dan',
                'polish': 'pol',
                'greek': 'ell',
                'czech': 'ces',
                'swahili': 'swa',
                'amharic': 'amh',
                'yoruba': 'yor',
                'zulu': 'zul',
                'hausa': 'hau',
                'oromo': 'orm',
                'korean': 'kor',
                'turkish': 'tur',
                'vietnamese': 'vie',
                'thai': 'tha',
                'malay': 'msa',
                'punjabi': 'pan'
            };

            // Build the extraction text: lang_code country city industry (in one line)
            const langCode = languageCode[language] || language;
            const extractionText = `${langCode} ${country} ${city} ${industry || ''}`.trim();

            try {
                // Send to n8n webhook
                const n8nResponse = await fetch('https://n8n.srv1137974.hstgr.cloud/webhook/931c675e-6a24-4cd2-8fd6-46d0e787b9b3', {
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
    </script>

    <!-- Email Modal -->
    <div id="emailModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-950 border border-white/10 w-full max-w-4xl rounded-[2.5rem] overflow-hidden shadow-2xl p-8 animate-in fade-in zoom-in duration-300 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 id="emailModalTitle" class="text-2xl font-black text-white italic tracking-tighter mb-0.5 uppercase">Send Email</h2>
                    <p id="emailModalSubtitle" class="text-[8px] font-bold text-slate-500 tracking-widest leading-none uppercase">Send email to client via webhook</p>
                </div>
                <button onclick="document.getElementById('emailModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-500 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="emailForm" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Recipient Email</label>
                        <div class="relative">
                            <select id="emailTo" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">-- Select Email --</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Template</label>
                        <div class="relative">
                            <select id="email_template" onchange="loadTemplate()" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">-- Select Template --</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-3 px-1">Schedule Date & Time</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[8px] font-bold text-slate-500 tracking-widest mb-2 px-1">Date</label>
                                <input type="date" id="email_date" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[8px] font-bold text-slate-500 tracking-widest mb-2 px-1">Time</label>
                                <input type="time" id="email_time" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                            </div>
                        </div>
                        <input type="hidden" id="email_datetime">
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Subject</label>
                    <input type="text" name="subject" id="email_subject" required placeholder="Email subject" 
                           class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all">
                </div>

                <div>
                    <label class="block text-[9px] font-black text-pink-400 tracking-widest mb-2 px-1">Message</label>
                    <textarea name="message" id="email_message" required placeholder="Email message" rows="8"
                              class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-white transition-all resize-none"></textarea>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 rounded-2xl shadow-2xl shadow-emerald-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                        Send Email
                    </button>
                    <button type="button" onclick="document.getElementById('emailModal').classList.add('hidden')" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload CSV Modal -->
    <!-- Envío a Base de Datos Modal -->
    <div id="sendingModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-950 border border-white/10 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex flex-col items-center justify-center space-y-6">
                <div class="relative">
                    <div class="w-16 h-16 rounded-full border-4 border-slate-700 border-t-pink-500 animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white italic tracking-tighter mb-2">Enviando CSV a Base de Datos</h3>
                    <p class="text-[9px] font-bold text-slate-700 dark:text-slate-600 dark:text-slate-400 tracking-widest uppercase">Por favor espera...</p>
                </div>
                <div class="w-full bg-slate-900 rounded-lg h-1 overflow-hidden">
                    <div class="bg-pink-500 h-full w-full animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="uploadCSVModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-950 border border-white/10 w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl p-8 animate-in fade-in zoom-in duration-300 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-0.5 uppercase">Upload Clients CSV</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none uppercase">Import clients from a CSV file</p>
                </div>
                <button onclick="document.getElementById('uploadCSVModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-700 dark:text-slate-600 hover:text-slate-900 dark:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="uploadCSVForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[9px] font-black text-purple-400 tracking-widest mb-3 px-1">Select CSV File</label>
                    <div class="relative">
                        <input type="file" id="csvFile" name="csv_file" accept=".csv" required 
                               class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-purple-500/30 text-xs font-bold text-white transition-all file:mr-3 file:py-2 file:px-4 file:bg-purple-600 file:text-white file:border-0 file:rounded-lg file:font-bold file:cursor-pointer hover:file:bg-purple-500">
                    </div>
                    <p class="text-[8px] text-slate-500 mt-2 px-1">Expected columns: name, email, website, location, phone, industry, email2, address, language, contact_name, facebook, instagram, opening_hours, notes, status</p>
                </div>

                <div class="bg-slate-900 border border-white/10 rounded-xl p-4">
                    <p class="text-[9px] font-bold text-slate-700 dark:text-slate-600 dark:text-slate-400 leading-relaxed">
                        <span class="text-purple-400">Note:</span> The CSV file should have headers matching the client field names. At minimum, include <span class="text-pink-400">name</span> and <span class="text-pink-400">email</span>. Optional fields can be left empty.
                    </p>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-500 text-white font-black py-4 rounded-2xl shadow-2xl shadow-purple-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                        Upload CSV
                    </button>
                    <button type="button" onclick="document.getElementById('uploadCSVModal').classList.add('hidden')" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load templates from database
        async function loadTemplates() {
            try {
                const response = await fetch('{{ route("dashboard.clients.templates") }}');
                const data = await response.json();
                emailTemplates = data;
                populateTemplateSelect();
            } catch (e) {
                console.error('Error loading templates:', e);
            }
        }

        // Load statuses from database
        async function loadStatuses() {
            try {
                const response = await fetch('{{ route("dashboard.clients.statuses") }}');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                clientStatuses = data;
                populateStatusSelect();
                console.log('Statuses loaded:', clientStatuses);
            } catch (e) {
                console.error('Error loading statuses:', e);
                // Fallback statuses if fetch fails
                clientStatuses = [
                    {name: 'created', label: 'CREATED'},
                    {name: 'extracted', label: 'EXTRACTED'},
                    {name: 'queued', label: 'QUEUED'},
                    {name: 'sent', label: 'SENT'},
                    {name: 'cancelled', label: 'CANCELLED'}
                ];
                populateStatusSelect();
            }
        }

        function populateTemplateSelect() {
            const select = document.getElementById('email_template');
            // Keep the default option
            select.innerHTML = '<option value="">-- Select Template --</option>';
            
            emailTemplates.forEach(template => {
                const option = document.createElement('option');
                option.value = template.id;
                option.textContent = `${template.name} - ${template.subject}`;
                select.appendChild(option);
            });
        }

        function loadTemplate() {
            const templateId = document.getElementById('email_template').value;
            
            if (templateId) {
                const template = emailTemplates.find(t => t.id == templateId);
                if (template) {
                    document.getElementById('email_subject').value = template.subject;
                    document.getElementById('email_message').value = template.body;
                }
            }
        }

        // Load templates and statuses when page loads
        document.addEventListener('DOMContentLoaded', () => {
            loadTemplates();
            loadStatuses();
        });

        function openEmailModal(clientId, clientName, clientEmail, clientEmail2) {
            document.getElementById('emailModalTitle').textContent = 'Send Email to ' + clientName;
            document.getElementById('emailModalSubtitle').textContent = 'Client: ' + clientName;
            
            // Populate email dropdown
            const emailSelect = document.getElementById('emailTo');
            emailSelect.innerHTML = '<option value="">-- Select Email --</option>';
            
            // Add primary email
            if (clientEmail) {
                const option1 = document.createElement('option');
                option1.value = clientEmail;
                option1.textContent = clientEmail + ' (Primary)';
                emailSelect.appendChild(option1);
            }
            
            // Add secondary email if it exists
            if (clientEmail2) {
                const option2 = document.createElement('option');
                option2.value = clientEmail2;
                option2.textContent = clientEmail2 + ' (Secondary)';
                emailSelect.appendChild(option2);
            }
            
            // Set primary email as default
            if (clientEmail) {
                emailSelect.value = clientEmail;
            }
            
            document.getElementById('email_template').value = '';
            document.getElementById('email_subject').value = '';
            document.getElementById('email_message').value = '';
            
            // Set current date and time as default (using server time to match application timezone)
            // Get server time to ensure correct timezone
            async function setDefaultDateTime() {
                try {
                    const response = await fetch('{{ route("dashboard.clients.server-time") }}');
                    const data = await response.json();
                    const datetimeValue = data.datetime; // Format: YYYY-MM-DDTHH:mm
                    const [date, time] = datetimeValue.split('T');
                    document.getElementById('email_date').value = date;
                    document.getElementById('email_time').value = time;
                    updateDatetimeHidden();
                } catch (e) {
                    // Fallback to local time if server call fails
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    document.getElementById('email_date').value = `${year}-${month}-${day}`;
                    document.getElementById('email_time').value = `${hours}:${minutes}`;
                    updateDatetimeHidden();
                }
            }
            
            // Combine date and time into hidden datetime field
            function updateDatetimeHidden() {
                const date = document.getElementById('email_date').value;
                const time = document.getElementById('email_time').value;
                if (date && time) {
                    document.getElementById('email_datetime').value = `${date}T${time}`;
                }
            }
            
            // Update hidden field when date or time changes
            document.getElementById('email_date')?.addEventListener('change', updateDatetimeHidden);
            document.getElementById('email_time')?.addEventListener('change', updateDatetimeHidden);
            
            setDefaultDateTime();
            
            document.getElementById('emailForm').onsubmit = async function(e) {
                e.preventDefault();
                const selectedEmail = document.getElementById('emailTo').value;
                const templateId = document.getElementById('email_template').value;
                const subject = document.getElementById('email_subject').value;
                const message = document.getElementById('email_message').value;
                const datetime = document.getElementById('email_datetime').value;
                await sendEmailViaWebhook(clientId, clientName, selectedEmail, templateId, subject, message, datetime);
            };
            document.getElementById('emailModal').classList.remove('hidden');
        }

        async function sendEmailViaWebhook(clientId, clientName, clientEmail, templateId, subject, message, datetime) {
            if (!subject || !message) {
                alert('Please fill in all fields');
                return;
            }

            if (!datetime) {
                alert('Please select a date and time for the email');
                return;
            }

            if (!clientEmail) {
                alert('Please select a valid email address');
                return;
            }

            try {
                // Get the client row to extract all data
                const clientRow = document.querySelector(`tr[data-client-id="${clientId}"]`);
                
                if (!clientRow) {
                    alert('Error: Could not find client data');
                    return;
                }
                
                // Build comprehensive payload with all client fields
                const payload = {
                    client_id: clientId,
                    name: clientRow?.dataset.clientName || clientName,
                    email: clientEmail,
                    email2: clientRow?.dataset.clientEmail2 || '',
                    website: clientRow?.dataset.clientWebsite || '',
                    location: clientRow?.dataset.clientLocation || '',
                    address: clientRow?.dataset.clientAddress || '',
                    phone: clientRow?.dataset.clientPhone || '',
                    language: clientRow?.dataset.clientLanguage || '',
                    industry: clientRow?.dataset.clientIndustry || '',
                    contact_name: clientRow?.dataset.clientContactName || '',
                    status: clientRow?.dataset.clientStatus || '',
                    facebook: clientRow?.dataset.clientFacebook || '',
                    instagram: clientRow?.dataset.clientInstagram || '',
                    opening_hours: clientRow?.dataset.clientOpeningHours || '',
                    notes: clientRow?.dataset.clientNotes || '',
                    template: templateId,
                    subject: subject,
                    message: message,
                    scheduled_datetime: datetime ? new Date(datetime).toISOString().slice(0, 19).replace('T', ' ') : '',
                    user_id: '{{ Auth::id() }}',
                    user_email: '{{ Auth::user()->email }}'
                };

                console.log('Sending payload:', payload);

                const response = await fetch('https://n8n.srv1137974.hstgr.cloud/webhook/direct', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                console.log('Response status:', response.status);

                if (response.ok) {
                    alert('✓ Email sent successfully!\nTo: ' + clientEmail);
                    
                    // Update client status to 'queued' and save last_email_sent_at
                    try {
                        // Convert datetime-local format (YYYY-MM-DDTHH:mm) to database format (YYYY-MM-DD HH:mm:ss)
                        let emailDateTime = '';
                        if (datetime) {
                            const parts = datetime.split('T');
                            emailDateTime = parts[0] + ' ' + parts[1] + ':00';
                        } else {
                            const now = new Date();
                            const year = now.getFullYear();
                            const month = String(now.getMonth() + 1).padStart(2, '0');
                            const day = String(now.getDate()).padStart(2, '0');
                            const hours = String(now.getHours()).padStart(2, '0');
                            const minutes = String(now.getMinutes()).padStart(2, '0');
                            const seconds = String(now.getSeconds()).padStart(2, '0');
                            emailDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                        }
                        
                        const updateResponse = await fetch(`/dashboard/clients/${clientId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ 
                                status: 'queued',
                                last_email_sent_at: emailDateTime
                            })
                        });
                        
                        if (updateResponse.ok) {
                            console.log('Status updated to queued and last_email_sent_at saved');
                        }
                    } catch (e) {
                        console.error('Error updating status:', e);
                    }
                    
                    document.getElementById('emailModal').classList.add('hidden');
                    location.reload();
                } else {
                    // Try to get error message from response
                    let errorMsg = 'Unknown error (HTTP ' + response.status + ')';
                    try {
                        const data = await response.json();
                        errorMsg = data.message || errorMsg;
                    } catch (e) {
                        // Response was not JSON
                    }
                    alert('Error: ' + errorMsg);
                }
            } catch (e) {
                console.error('Error details:', e);
                alert('Error sending email: ' + e.message + '\n\nPlease check the browser console for more details.');
            }
        }

        function openUploadCSVModal() {
            document.getElementById('uploadCSVModal').classList.remove('hidden');
            document.getElementById('uploadCSVForm').reset();
        }

        document.getElementById('uploadCSVForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('csvFile');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('Please select a CSV file');
                return;
            }
            
            if (!file.name.endsWith('.csv')) {
                alert('Please select a valid CSV file');
                return;
            }
            
            // Hide upload modal and show sending modal
            document.getElementById('uploadCSVModal').classList.add('hidden');
            document.getElementById('sendingModal').classList.remove('hidden');
            
            const formData = new FormData();
            formData.append('csv_file', file);
            
            try {
                // Send CSV to n8n webhook for AI parsing/ingestion (production)
                const n8nWebhook = 'https://n8n.srv1137974.hstgr.cloud/webhook/7b53997f-5699-4bfe-9eda-3979856819f0';
                // Append user info to help n8n/AI attribute the import
                formData.append('user_id', '{{ Auth::id() }}');
                formData.append('user_email', '{{ Auth::user()->email }}');

                const response = await fetch(n8nWebhook, {
                    method: 'POST',
                    // Do not set Content-Type so the browser sets the correct multipart/form-data boundary
                    body: formData
                });

                console.log('n8n response status:', response.status);

                if (response.ok) {
                    // Wait 2 seconds before reloading to show completion
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    // Reload the page
                    location.reload();
                } else {
                    const text = await response.text();
                    console.error('n8n error response:', text.substring(0, 1000));
                    document.getElementById('sendingModal').classList.add('hidden');
                    alert('Error: ' + response.status + '. The CSV could not be imported.');
                }
            } catch (e) {
                console.error('CSV upload error:', e);
                document.getElementById('sendingModal').classList.add('hidden');
                alert('Error uploading CSV: ' + e.message);
            }
        });
    </script>

</body>
</html>
