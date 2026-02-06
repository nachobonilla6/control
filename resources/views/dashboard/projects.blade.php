<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Control</title>
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
<body class="h-full flex flex-col bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-950 backdrop-blur-md border-b border-white/10 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
                <span class="text-xl font-bold text-pink-600 font-inter group-hover/brand:text-pink-500 transition-colors">Mini Walee</span>
                <span class="text-slate-600 font-light text-xl italic font-inter">/</span>
                <span class="text-sm font-medium text-white tracking-wide uppercase font-inter transition-colors">Control Panel</span>
            </a>
            <span class="text-slate-600 font-light text-xl italic font-inter">/</span>
            <a href="{{ route('dashboard.projects') }}" class="text-xs font-black text-pink-500 tracking-[0.2em] hover:text-white transition-colors uppercase font-inter">Projects</a>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-pink-600/10 hover:bg-pink-600/20 border border-pink-500/20 rounded-xl transition-all group">
                <svg class="w-4 h-4 text-pink-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black text-pink-400 uppercase tracking-widest">Copilot</span>
            </a>
            <!-- Account Dropdown -->
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
                <div id="accountDropdown" class="absolute right-0 mt-3 w-56 bg-slate-950 border border-white/10 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2">
                    <div class="px-4 py-3 border-b border-white/10 mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->email }}</p>
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
                <button id="notifBtn" class="relative p-2.5 text-white hover:text-pink-400 transition-colors focus:outline-none bg-white/10 rounded-full border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-0 right-0 bg-pink-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>
                <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-slate-950 border border-white/10 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2 overflow-hidden">
                    <div class="p-4 border-b border-white/10 flex items-center justify-between">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest text-pink-500">Broadcasts</h3>
                        <span class="text-[9px] font-bold text-slate-500 uppercase">Live Feed</span>
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

            @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-sm font-bold uppercase tracking-widest">{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-2xl shadow-lg">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-sm font-black uppercase tracking-widest">Deployment Errors Detected</span>
                </div>
                <ul class="list-disc list-inside text-xs font-bold opacity-80 space-y-1 ml-8">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Projects <span class="text-pink-500">Infrastructure</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.3em] mt-1">Digital asset management and deployments</p>
                </div>
                <button id="openModalBtn" onclick="event.stopPropagation(); document.getElementById('newProjectModal').classList.remove('hidden')" class="flex items-center space-x-3 px-8 py-4 bg-pink-600 hover:bg-pink-500 text-slate-900 dark:text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] transition-all shadow-2xl shadow-pink-600/20 active:scale-95 group">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span>New Deployment</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($projects as $project)
                @php
                    $imgs = is_array($project->images) ? $project->images : json_decode($project->images, true);
                    if (!is_array($imgs)) $imgs = [];
                @endphp
                <div class="bg-slate-950 border border-white/10 rounded-3xl overflow-hidden group hover:border-pink-500/30 transition-all flex flex-col shadow-2xl relative">
                    @if(count($imgs) > 0)
                    <a href="{{ route('projects.show', $project->id) }}" class="block h-56 relative overflow-hidden bg-white dark:bg-slate-950 group/img">
                        <img src="{{ asset($imgs[0]) }}" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-700 opacity-80 group-hover/img:opacity-100">
                        <div class="absolute inset-0 bg-pink-900/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                            <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-[0.4em] border border-white/20 px-4 py-2 rounded-lg">View Details</span>
                        </div>
                        <div class="absolute top-4 right-4 bg-white dark:bg-slate-950/80 backdrop-blur-md px-3 py-1 rounded-full border border-white/10 text-[9px] font-black text-slate-900 dark:text-white uppercase tracking-widest flex items-center shadow-2xl">
                            <svg class="w-3 h-3 mr-1.5 text-pink-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>
                            {{ count($imgs) }} Visuals
                        </div>
                    </a>
                    @else
                    <div class="h-56 bg-white dark:bg-slate-950 flex flex-col items-center justify-center text-slate-800 space-y-2 border-b border-pink-200 dark:border-slate-800">
                        <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="italic text-[9px] uppercase tracking-[0.2em]">Awaiting Media</span>
                    </div>
                    @endif

                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-5">
                            <span class="px-4 py-1.5 rounded-full bg-pink-600/10 text-pink-400 text-[9px] font-black uppercase border border-pink-500/10">
                                {{ $project->type }}
                            </span>
                            <div class="flex items-center space-x-2.5">
                                <div class="w-2 h-2 rounded-full {{ $project->active ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.3)] animate-pulse' : 'bg-slate-700' }}"></div>
                                <span class="text-[9px] font-black uppercase text-slate-500 tracking-wider">{{ $project->active ? 'Live' : 'Off' }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('projects.show', $project->id) }}" class="block group/title">
                            <h3 class="text-xl font-bold text-white mb-3 group-hover/title:text-pink-400 transition-colors">{{ $project->name }}</h3>
                        </a>
                        <p class="text-xs text-slate-400 leading-relaxed line-clamp-3 mb-8 font-medium italic opacity-80">"{{ $project->description ?: 'No narrative provided for this infrastructure.' }}"</p>
                        
                        <div class="mt-auto flex items-center justify-between pt-6 border-t border-white/5">
                            <div class="flex -space-x-2.5">
                                @foreach(array_slice($imgs, 1, 4) as $subImg)
                                <img src="{{ asset($subImg) }}" class="w-9 h-9 rounded-xl border-2 border-slate-900 object-cover shadow-2xl hover:scale-110 transition-transform cursor-pointer">
                                @endforeach
                            </div>
                            
                            <div class="flex items-center space-x-2.5">
                                    @php
                                        $integStr = is_array($project->integrations) ? implode(', ', $project->integrations) : '';
                                    @endphp
                                    <button 
                                        class="edit-btn w-10 h-10 flex items-center justify-center bg-pink-600/10 text-pink-400 rounded-xl hover:bg-pink-600 hover:text-slate-900 dark:text-white transition-all border border-pink-500/20 active:scale-95"
                                        data-id="{{ $project->id }}"
                                        data-name="{{ $project->name }}"
                                        data-type="{{ $project->type }}"
                                        data-video="{{ $project->video_url }}"
                                        data-integrations="{{ $integStr }}"
                                        data-description="{{ $project->description }}"
                                        data-active="{{ $project->active ? '1' : '0' }}"
                                        onclick="event.stopPropagation(); window.setupEditModal(this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>

                                <form action="{{ route('dashboard.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Initiate project decommissioning?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-slate-900 dark:text-white transition-all border border-red-500/20 active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($projects->isEmpty())
            <div class="bg-slate-100 dark:bg-slate-900/50 border-2 border-dashed border-pink-200 dark:border-slate-800 rounded-[3rem] p-24 text-center opacity-30">
                <p class="text-xs font-black uppercase tracking-[0.4em] text-slate-500">Waitng for deployment</p>
            </div>
            @endif
        </div>
    </main>

    <!-- Dynamic Project Modal (Create/Edit) -->
    <div id="newProjectModal" class="fixed inset-0 z-50 hidden bg-white dark:bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-100 dark:bg-slate-900 border border-pink-200 dark:border-slate-800 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-[0_0_100px_rgba(0,0,0,0.8)] p-8 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 id="modalTitle" class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight leading-none mb-1 italic">Deploy Asset</h2>
                    <p id="modalSubtitle" class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">Digital heritage infrastructure</p>
                </div>
                <button onclick="document.getElementById('newProjectModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-500 hover:text-slate-900 dark:text-white transition-all transform hover:rotate-90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            
            <form id="projectForm" action="{{ route('dashboard.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div id="methodField"></div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-[9px] font-black text-pink-400 uppercase tracking-widest mb-2">Project Title</label>
                            <input type="text" name="name" id="form_name" value="{{ old('name') }}" required placeholder="e.g. Nexus OS" 
                                   class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-slate-900 dark:text-white placeholder:text-slate-800 transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[9px] font-black text-pink-400 uppercase tracking-widest mb-2">Service</label>
                            <select name="type" id="form_type" required class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-slate-900 dark:text-white appearance-none transition-all">
                                <option value="Mobile App">Mobile App</option>
                                <option value="Web Platform">Web Platform</option>
                                <option value="AI Automation">AI Automation</option>
                                <option value="CRM System">CRM System</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[9px] font-black text-pink-400 uppercase tracking-widest mb-2">Status</label>
                            <div class="h-[50px] flex items-center px-4 bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl">
                                <label class="relative inline-flex items-center cursor-pointer w-full justify-between">
                                    <span class="text-[9px] font-black text-slate-700 dark:text-slate-600 uppercase">Live</span>
                                    <input type="checkbox" name="active" id="form_active" checked class="sr-only peer">
                                    <div class="w-8 h-4 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[18px] after:bg-white dark:bg-slate-950 after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-emerald-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-black text-pink-400 uppercase tracking-widest mb-2">Video / URL (Optional)</label>
                        <input type="url" name="video_url" id="form_video" placeholder="YouTube link..." 
                               class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-slate-900 dark:text-white placeholder:text-slate-800 transition-all">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-pink-400 uppercase tracking-widest mb-2">Integrations (Comma separated)</label>
                        <input type="text" name="integrations" id="form_integrations" placeholder="Zapier, Shopify, Stripe..." 
                               class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-slate-900 dark:text-white placeholder:text-slate-800 transition-all">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-pink-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" id="form_description" rows="2" placeholder="Technical summary..." 
                                  class="w-full bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-pink-500/30 text-xs font-bold text-slate-900 dark:text-white resize-none placeholder:text-slate-800 leading-relaxed transition-all">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-pink-400 uppercase tracking-widest mb-2">Media Assets</label>
                        <div id="imagePreview" class="flex flex-wrap gap-2 mb-3"></div>
                        <label class="flex items-center justify-between w-full p-4 border border-pink-200 dark:border-slate-800 border-dashed rounded-xl cursor-pointer bg-white dark:bg-slate-950 hover:bg-slate-100 dark:bg-slate-900 transition-all group/file">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-slate-700 dark:text-slate-600 group-hover/file:text-pink-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <p id="fileLabel" class="text-[9px] font-black text-slate-700 dark:text-slate-600 uppercase tracking-widest group-hover/file:text-slate-900 dark:text-white transition-colors">Select Photos</p>
                            </div>
                            <span class="text-[8px] font-bold text-slate-700 uppercase">Max 7</span>
                            <input type="file" name="images[]" id="fileInput" multiple accept="image/*" class="hidden" onchange="previewImages(this)" />
                        </label>
                        <p id="editNotice" class="text-[8px] text-slate-700 dark:text-slate-600 mt-2 hidden italic uppercase tracking-wider text-center">* Media will be replaced</p>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-slate-900 dark:text-white font-black py-4 rounded-xl shadow-2xl shadow-pink-600/20 transition-all active:scale-95 text-[10px] uppercase tracking-[0.2em] flex items-center justify-center space-x-2">
                    <span id="submitBtnText">Initialize Load</span>
                </button>
            </form>
        </div>
    </div>

<script>
    if ({{ $errors->any() ? 'true' : 'false' }}) {
        window.onload = () => {
            document.getElementById('newProjectModal').classList.remove('hidden');
        }
    }
</script>

    <!-- Profile Modal (Same as other pages) -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-950 border border-white/10 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white uppercase tracking-tight">Identity Config</h2>
                <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Avatar URL</label>
                    <input type="url" name="profile_photo_url" value="{{ Auth::user()->profile_photo_url }}" required class="w-full bg-slate-950 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-pink-600/50">
                </div>
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-white py-3 rounded-xl font-bold transition-all shadow-lg text-xs uppercase tracking-widest">Sync Identity</button>
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
            document.getElementById('newProjectModal').classList.add('hidden');
        }

        // Logic for Create/Edit Modal
        function openCreateModal() {
            const form = document.getElementById('projectForm');
            form.action = "{{ route('dashboard.projects.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('modalTitle').innerText = 'New Deployment';
            document.getElementById('modalSubtitle').innerText = 'Registering new development heritage';
            document.getElementById('submitBtnText').innerText = 'Execute Project Load';
            document.getElementById('editNotice').classList.add('hidden');
            
            // Clear fields
            document.getElementById('form_name').value = '';
            document.getElementById('form_type').value = 'Mobile App';
            document.getElementById('form_video').value = '';
            document.getElementById('form_integrations').value = '';
            document.getElementById('form_description').value = '';
            document.getElementById('form_active').checked = true;
            document.getElementById('fileLabel').innerText = 'Select Photos';
            document.getElementById('imagePreview').innerHTML = '';
            
            document.getElementById('newProjectModal').classList.remove('hidden');
        }

        window.setupEditModal = function(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const type = btn.getAttribute('data-type');
            const video = btn.getAttribute('data-video') || '';
            const integrations = btn.getAttribute('data-integrations') || '';
            const description = btn.getAttribute('data-description');
            const active = btn.getAttribute('data-active') === '1';

            const form = document.getElementById('projectForm');
            form.action = `/dashboard/projects/${id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            document.getElementById('modalTitle').innerText = 'Modify Entity';
            document.getElementById('modalSubtitle').innerText = 'Updating project architecture: ' + name;
            document.getElementById('submitBtnText').innerText = 'Sync Changes';
            document.getElementById('editNotice').classList.remove('hidden');
            
            // Populate fields
            document.getElementById('form_name').value = name;
            document.getElementById('form_type').value = type;
            document.getElementById('form_video').value = video;
            document.getElementById('form_integrations').value = integrations;
            document.getElementById('form_description').value = description || '';
            document.getElementById('form_active').checked = active;
            document.getElementById('fileLabel').innerText = 'Visual Assets Attached';
            document.getElementById('imagePreview').innerHTML = '';
            
            document.getElementById('newProjectModal').classList.remove('hidden');
        }

        // Connect the openModalBtn to openCreateModal
        const openBtn = document.getElementById('openModalBtn');
        if (openBtn) {
            openBtn.onclick = (e) => {
                e.stopPropagation();
                openCreateModal();
            };
        }

        async function fetchNotifs() {
            try {
                const res = await fetch('{{ route('notifications') }}');
                const data = await res.json();
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.classList.remove('hidden');
                    list.innerHTML = data.map(n => `
                        <div class="p-3 bg-white dark:bg-slate-950 border border-pink-200 dark:border-slate-800 rounded-xl">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-[11px] font-bold text-slate-200 leading-tight">${n.titulo}</h3>
                                <span class="text-[9px] font-medium text-slate-700 dark:text-slate-600 ml-2">${n.fecha_format}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 leading-relaxed">${n.texto}</p>
                        </div>
                    `).join('');
                }
            } catch (e) {}
        }

        // Image Preview Logic
        function previewImages(input) {
            const container = document.getElementById('imagePreview');
            const label = document.getElementById('fileLabel');
            container.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                label.innerText = `${input.files.length} ASSETS STAGED`;
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'w-12 h-12 rounded-lg overflow-hidden border border-white/10 shadow-lg';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                label.innerText = 'Select Photos';
            }
        }

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = !notifDropdown.classList.contains('invisible');
            
            // Close others manually to avoid hiding everything if unnecessary
            accountDropdown.classList.add('opacity-0', 'invisible');
            
            if (!isVisible) {
                notifDropdown.classList.remove('opacity-0', 'invisible');
                fetchNotifs();
            } else {
                notifDropdown.classList.add('opacity-0', 'invisible');
            }
        });

        accountBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isVisible = !accountDropdown.classList.contains('invisible');
            
            notifDropdown.classList.add('opacity-0', 'invisible');
            
            if (!isVisible) {
                accountDropdown.classList.remove('opacity-0', 'invisible');
            } else {
                accountDropdown.classList.add('opacity-0', 'invisible');
            }
        });

        document.addEventListener('click', (e) => {
            const newProjectModal = document.getElementById('newProjectModal');
            const openModalBtn = document.getElementById('openModalBtn');
            if (!notifDropdown.contains(e.target) && 
                !accountDropdown.contains(e.target) && 
                !newProjectModal.contains(e.target) &&
                !notifBtn.contains(e.target) &&
                !accountBtn.contains(e.target) &&
                (!openModalBtn || !openModalBtn.contains(e.target))) {
                closeAllDropdowns();
            }
        });
        fetchNotifs();
    </script>
</body>
</html>
