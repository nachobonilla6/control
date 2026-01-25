<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Control</title>
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
            <a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors border border-slate-800 p-2 rounded-xl bg-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <span class="text-xl font-bold text-white uppercase tracking-tighter">Project Gallery</span>
            <button id="openModalBtn" onclick="event.stopPropagation(); document.getElementById('newProjectModal').classList.remove('hidden')" class="ml-6 flex items-center space-x-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span>New Project</span>
            </button>
        </div>
        <div class="flex items-center space-x-4">
            <!-- Account Dropdown -->
            <div class="relative">
                <button id="accountBtn" class="flex items-center justify-center w-10 h-10 bg-slate-800/50 border border-slate-800 rounded-full hover:border-indigo-500/30 transition-all focus:outline-none overflow-hidden group">
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    @else
                        <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-xs font-black">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>
                <div id="accountDropdown" class="absolute right-0 mt-3 w-56 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2">
                    <div class="px-4 py-3 border-b border-slate-800 mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->email }}</p>
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
                <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2 overflow-hidden">
                    <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest text-indigo-400">Broadcasts</h3>
                        <span class="text-[9px] font-bold text-slate-600 uppercase">Live Feed</span>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto p-2 space-y-2">
                        <div class="text-center py-6 text-slate-600 text-[10px] italic uppercase tracking-widest">Scanning...</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-6 md:p-10">
        <div class="max-w-7xl mx-auto">

            <!-- Healing Mode / Migration Help -->
            @if(isset($error_type) && $error_type === 'missing_table')
            <div class="mb-12 bg-indigo-600/10 border-2 border-dashed border-indigo-500/30 rounded-[3rem] p-12 text-center relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-10 flex space-x-2">
                    <svg class="w-16 h-16 text-indigo-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <h2 class="text-2xl font-black text-white uppercase tracking-tighter mb-4 italic text-indigo-400">Database Link Required</h2>
                <p class="text-slate-400 max-w-xl mx-auto mb-8 font-medium">The projects table does not exist on your server infrastructure. Run the following command in your Hostinger terminal to heal the database:</p>
                
                <div class="bg-slate-950 rounded-2xl p-6 mb-8 inline-block border border-white/5 shadow-2xl relative group">
                    <code id="migrateCmd" class="text-emerald-400 font-mono text-sm leading-relaxed">php artisan migrate --path=/database/migrations/2026_01_24_210927_create_projects_table.php</code>
                    <button onclick="navigator.clipboard.writeText(document.getElementById('migrateCmd').innerText); this.innerText='COPIED!'" class="ml-4 text-[10px] font-black text-slate-500 uppercase hover:text-white transition-colors">Copy</button>
                </div>

                <div class="flex justify-center items-center space-x-6">
                    <div class="flex items-center text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Hostinger Optimized
                    </div>
                </div>
            </div>
            @endif

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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($projects as $project)
                <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden group hover:border-indigo-500/30 transition-all flex flex-col shadow-2xl">
                    @if($project->images && count($project->images) > 0)
                    <div class="h-48 relative overflow-hidden bg-slate-950">
                        <img src="{{ $project->images[0] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 right-4 bg-slate-950/80 backdrop-blur-md px-2 py-1 rounded-lg border border-white/10 text-[8px] font-black text-white uppercase tracking-widest">
                            +{{ count($project->images) }} Photos
                        </div>
                    </div>
                    @else
                    <div class="h-48 bg-slate-950 flex items-center justify-center text-slate-800 italic text-xs uppercase tracking-widest">
                        No Visuals Uploaded
                    </div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-indigo-600/10 text-indigo-400 text-[9px] font-black uppercase border border-indigo-500/20">
                                {{ $project->type }}
                            </span>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 rounded-full {{ $project->active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-600' }}"></div>
                                <span class="text-[8px] font-black uppercase text-slate-500">{{ $project->active ? 'Live' : 'Inactive' }}</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $project->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-6">{{ $project->description }}</p>
                        
                        <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-800/50">
                            <div class="flex -space-x-2">
                                @foreach(array_slice($project->images ?? [], 0, 3) as $img)
                                <img src="{{ $img }}" class="w-8 h-8 rounded-lg border-2 border-slate-900 object-cover shadow-lg">
                                @endforeach
                            </div>
                            
                            <form action="{{ route('dashboard.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Archive project?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all border border-red-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($projects->isEmpty())
            <div class="bg-slate-900/50 border-2 border-dashed border-slate-800 rounded-[3rem] p-24 text-center opacity-30">
                <p class="text-xs font-black uppercase tracking-[0.4em] text-slate-500">Waitng for deployment</p>
            </div>
            @endif
        </div>
    </main>

    <!-- New Project Modal -->
    <div id="newProjectModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-[2.5rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-white uppercase tracking-tight">Deploy New Project</h2>
                <button onclick="document.getElementById('newProjectModal').classList.add('hidden')" class="p-2 hover:bg-slate-800 rounded-xl text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Project Identity</label>
                        <input type="text" name="name" required placeholder="e.g. Phoenix Portal" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm text-slate-200">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Vertical</label>
                        <select name="type" required class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm text-slate-200">
                            <option value="Mobile App">Mobile App</option>
                            <option value="Web Platform">Web Platform</option>
                            <option value="AI Automation">AI Automation</option>
                            <option value="CRM System">CRM System</option>
                        </select>
                    </div>

                    <div class="flex items-center mt-6">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="active" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-3 text-[10px] font-black text-slate-500 uppercase">Live Now</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Narrative</label>
                    <textarea name="description" rows="3" placeholder="Core features and goals..." 
                              class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm text-slate-200 resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">Visual Media (Max 7)</label>
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-800 border-dashed rounded-[2rem] cursor-pointer bg-slate-950 hover:bg-slate-900/50 transition-all group/file">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 mb-2 text-slate-700 group-hover/file:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <p id="fileLabel" class="text-[10px] font-black text-slate-600 uppercase tracking-widest group-hover/file:text-white transition-colors">Select Visual Assets</p>
                        </div>
                        <input type="file" name="images[]" id="fileInput" multiple accept="image/*" class="hidden" onchange="document.getElementById('fileLabel').innerText = this.files.length + ' FILES STAGED'" />
                    </label>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-6 rounded-3xl shadow-2xl shadow-indigo-600/30 transition-all active:scale-[0.98] text-xs uppercase tracking-[0.3em] flex items-center justify-center space-x-3">
                <svg class="w-4 h-4 animate-spin hidden" id="deploySpinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span>Execute Project Load</span>
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
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white uppercase tracking-tight">Identity Config</h2>
                <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Avatar URL</label>
                    <input type="url" name="profile_photo_url" value="{{ Auth::user()->profile_photo_url }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl font-bold transition-all shadow-lg">Sync Identity</button>
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

        async function fetchNotifs() {
            try {
                const res = await fetch('{{ route('notifications') }}');
                const data = await res.json();
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.classList.remove('hidden');
                    list.innerHTML = data.map(n => `
                        <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-[11px] font-bold text-slate-200 leading-tight">${n.titulo}</h3>
                                <span class="text-[9px] font-medium text-slate-600 ml-2">${n.fecha_format}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 leading-relaxed">${n.texto}</p>
                        </div>
                    `).join('');
                }
            } catch (e) {}
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
