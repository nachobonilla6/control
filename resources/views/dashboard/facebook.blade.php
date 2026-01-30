<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Posts - Control Panel</title>
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
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-200 overflow-hidden uppercase">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-slate-800/50 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
                <span class="text-xl font-bold text-indigo-400 font-inter group-hover/brand:text-white transition-colors">Mini Walee</span>
                <span class="text-slate-700 font-light text-xl italic font-inter">/</span>
                <span class="text-sm font-medium text-slate-400 tracking-wide uppercase font-inter group-hover/brand:text-indigo-400 transition-colors">Control Panel</span>
            </a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <a href="{{ route('dashboard.facebook') }}" class="text-xs font-black text-indigo-500 tracking-[0.2em] hover:text-white transition-colors uppercase font-inter">Facebook</a>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-indigo-600/10 hover:bg-indigo-600/20 border border-indigo-500/20 rounded-xl transition-all group">
                <svg class="w-4 h-4 text-indigo-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Copilot</span>
            </a>
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
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-[9px] font-bold tracking-wider">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">Facebook <span class="text-indigo-500">Post Manager</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2 italic">Automated social media orchestration</p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard.facebook.accounts') }}" class="h-[58px] px-6 flex items-center justify-center bg-slate-900 border border-slate-800 rounded-2xl hover:border-indigo-500/30 transition-all text-slate-400 hover:text-white shadow-2xl active:scale-95 group space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <span class="text-[9px] font-black tracking-[0.2em] uppercase">Accounts</span>
                    </a>
                    <button onclick="document.getElementById('settingsModal').classList.remove('hidden')" class="w-[58px] h-[58px] flex items-center justify-center bg-slate-900 border border-slate-800 rounded-2xl hover:border-indigo-500/30 transition-all text-slate-400 hover:text-white shadow-2xl active:scale-95 group">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.543-.426-1.543-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                    <button onclick="openCreatePostModal()" class="h-[58px] px-8 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-indigo-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Create Post
                    </button>
                </div>
            </div>

            <!-- Posts Grid (3 per row) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($posts as $post)
                @php
                    $images = array_filter([$post->image1, $post->image2, $post->image3]);
                    $imgCount = count($images);
                @endphp
                <div class="group bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:border-indigo-500/30 hover:shadow-2xl flex flex-col h-full">
                    <!-- Image Grid Area -->
                    <div class="relative bg-slate-950 aspect-video overflow-hidden" id="carousel-{{ $post->id }}" data-current="0">
                        @if($imgCount > 0)
                            <div class="flex h-full transition-transform duration-500 ease-out" id="track-{{ $post->id }}">
                                @foreach($images as $img)
                                    <div class="w-full h-full flex-shrink-0">
                                        <img src="{{ asset($img) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <img src="https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?auto=format&fit=crop&w=800&q=80" 
                                 class="w-full h-full object-cover opacity-60 grayscale group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500" 
                                 alt="Default Post Image">
                        @endif

                        @if($imgCount > 1)
                            <!-- Controls -->
                            <button onclick="moveSlide(event, '{{ $post->id }}', -1, {{ $imgCount }})" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity hover:bg-black/70 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <button onclick="moveSlide(event, '{{ $post->id }}', 1, {{ $imgCount }})" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity hover:bg-black/70 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            <!-- Dots -->
                            <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-1.5 z-10">
                                @foreach($images as $idx => $img)
                                    <div class="w-1.5 h-1.5 rounded-full transition-all {{ $idx == 0 ? 'bg-white scale-125' : 'bg-white/40' }}" id="dot-{{ $post->id }}-{{ $idx }}"></div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Status Overlay -->
                        <div class="absolute top-4 right-4">
                            @php
                                $statusColor = match($post->status) {
                                    'posted' => 'bg-emerald-500',
                                    'sent' => 'bg-emerald-500',
                                    'cancelled' => 'bg-red-500',
                                    'queued' => 'bg-yellow-500',
                                    'scheduled' => 'bg-yellow-500',
                                    default => 'bg-indigo-500',
                                };
                            @endphp
                            <span class="text-[7px] font-black {{ $statusColor }} text-white px-3 py-1 rounded-full uppercase tracking-[0.2em] shadow-lg">
                                {{ in_array($post->status, ['scheduled', 'queued']) ? 'QUEUED' : ($post->status ?? 'QUEUED') }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-indigo-400 tracking-widest uppercase bg-indigo-600/10 px-3 py-1 rounded-full border border-indigo-500/10">Facebook Post</span>
                                    @if($post->account)
                                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest border border-slate-700/50 px-2 py-0.5 rounded-full">
                                            {{ $post->account->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-300 leading-relaxed line-clamp-4 font-medium lowercase mb-6">
                                {{ $post->content }}
                            </p>
                        </div>

                        <div class="pt-6 border-t border-white/5 space-y-4">
                            <div class="flex flex-col space-y-2">
                                @if($post->post_at)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <span class="text-[9px] font-black text-emerald-500 tracking-widest uppercase">
                                        {{ $post->post_at }}
                                    </span>
                                </div>
                                @endif


                                
                                <div class="flex items-center justify-end">
                                <div class="flex items-center space-x-2">
                                    @if($post->status !== 'posted' && $post->status !== 'sent')
                                        <button onclick="editPost({{ json_encode($post) }})" class="w-8 h-8 flex items-center justify-center bg-indigo-500/10 text-indigo-500 rounded-lg hover:bg-indigo-500 hover:text-white transition-all border border-indigo-500/10">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    @endif
                                    <form action="{{ route('dashboard.facebook.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Abort this post deployment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all border border-red-500/10">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-24 text-center border-2 border-dashed border-slate-800 rounded-[3rem] opacity-30">
                    <p class="text-xs font-black text-slate-500 tracking-[0.4em] uppercase italic">No posts staged for deployment</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Post Modal -->
    <div id="postModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-[3rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-1 uppercase">Initialize Deployment</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Social media content synchronization</p>
                </div>
                <button onclick="document.getElementById('postModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="createPostForm" action="{{ route('dashboard.facebook.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="handleFormSubmit(event, 'createPostBtn')">
                @csrf
                <div class="mb-6 animate-in fade-in slide-in-from-top-4">
                    <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">AI Deployment Strategy</label>
                    <div class="relative group">
                        <textarea id="ai_prompt" rows="2" placeholder="Describe your objective (e.g., 'Generate an engaging promotional post for a new AI course focusing on business automation')" 
                                  class="w-full bg-slate-950/50 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-medium text-slate-300 resize-none transition-all placeholder:text-slate-700"></textarea>
                        <button type="button" onclick="generateAIContent()" id="aiBtn" class="absolute right-3 bottom-3 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-xl text-[9px] font-black tracking-widest uppercase transition-all shadow-lg active:scale-95 flex items-center space-x-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Synthesize</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Post Content</label>
                            <textarea name="content" id="create_content" required rows="6" placeholder="What's happening on the network?" 
                                      class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-medium text-slate-200 resize-none lowercase"></textarea>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Target Account</label>
                            <select name="facebook_account_id" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all appearance-none">
                                <option value="">Select Account (Optional)</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ $account->id == 1 ? 'selected' : '' }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="relative">
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Schedule Deployment</label>
                                <div class="relative">
                                    <input type="datetime-local" name="post_at" id="post_at_input"
                                           class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all appearance-none">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-indigo-500/50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Initial Status</label>
                                <select name="status" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all appearance-none">
                                    <option value="scheduled">Scheduled</option>
                                    <option value="posted">Posted</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 01</label>
                            <input type="file" name="image1" accept="image/*"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-[10px] font-bold text-slate-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 02</label>
                            <input type="file" name="image2" accept="image/*"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-[10px] font-bold text-slate-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 03</label>
                            <input type="file" name="image3" accept="image/*"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-[10px] font-bold text-slate-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button id="createPostBtn" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em] uppercase">
                        Execute Staging
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- AI Generating Modal -->
    <div id="aiGeneratingModal" class="fixed inset-0 z-60 hidden bg-slate-950/95 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl p-8">
            <div class="flex flex-col items-center justify-center space-y-6">
                <div class="relative">
                    <div class="w-16 h-16 rounded-full border-4 border-slate-700 border-t-indigo-500 animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-black text-white italic tracking-tighter mb-2">Generando contenido con AI</h3>
                    <p class="text-[9px] font-bold text-slate-400 tracking-widest uppercase">AI Deployment Strategy — Por favor espera...</p>
                </div>
                <div class="w-full bg-slate-950 rounded-lg h-1 overflow-hidden">
                    <div class="bg-indigo-500 h-full w-full animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editPostModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-[3rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-1 uppercase">Modify Deployment</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Social media content optimization</p>
                </div>
                <button onclick="document.getElementById('editPostModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="editPostForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Post Content</label>
                            <textarea name="content" id="edit_content" required rows="6" placeholder="What's happening on the network?" 
                                      class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-medium text-slate-200 resize-none lowercase"></textarea>
                        </div>
                        <div class="relative">
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Schedule Deployment</label>
                            <div class="relative">
                                <input type="datetime-local" name="post_at" id="edit_post_at"
                                       class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all appearance-none">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-indigo-500/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Target Account</label>
                            <select name="facebook_account_id" id="edit_facebook_account_id" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all appearance-none">
                                <option value="">Select Account (Optional)</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Deployment Status</label>
                            <select name="status" id="edit_status" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all appearance-none">
                                <option value="scheduled">Scheduled</option>
                                <option value="posted">Posted</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mb-2">Overwrite Media Assets</p>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 01</label>
                            <input type="file" name="image1" accept="image/*"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-[10px] font-bold text-slate-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 02</label>
                            <input type="file" name="image2" accept="image/*"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-[10px] font-bold text-slate-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 03</label>
                            <input type="file" name="image3" accept="image/*"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-[10px] font-bold text-slate-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em] uppercase">
                        Confirm Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="settingsModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-1 uppercase">Configuration</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">External pipeline orchestration</p>
                </div>
                <button onclick="document.getElementById('settingsModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form action="{{ route('dashboard.facebook.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Facebook Webhook URL</label>
                    <input type="url" name="facebook_webhook_url" required value="{{ $webhookUrl }}" 
                           class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all">
                    <p class="text-[7px] text-slate-500 mt-2 tracking-widest leading-relaxed">This endpoint will receive post metadata including image assets when staging is executed.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em] uppercase">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white uppercase tracking-tight">User Profile</h2>
                <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex flex-col items-center mb-4">
                    <div class="w-24 h-24 rounded-full border-2 border-indigo-500/20 overflow-hidden bg-slate-950 mb-4 shadow-2xl">
                        <img id="profile_preview" src="{{ Auth::user()->profile_photo_url ?: 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4f46e5&color=fff' }}" class="w-full h-full object-cover">
                    </div>
                    <p class="text-[8px] font-bold text-slate-600 uppercase tracking-widest">Active Identity Asset</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Profile Photo URL</label>
                    <input type="url" name="profile_photo_url" id="profile_url_input" value="{{ Auth::user()->profile_photo_url }}" required placeholder="https://..." 
                           oninput="document.getElementById('profile_preview').src = this.value || 'https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff'"
                           class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-600/50">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20 active:scale-95 text-xs uppercase tracking-widest">Update Profile Asset</button>
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
                        <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl hover:border-indigo-500/30 transition-all text-left">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-[11px] font-bold text-slate-200 leading-tight normal-case">${n.titulo}</h3>
                                <span class="text-[9px] font-medium text-slate-600 whitespace-nowrap ml-2">${n.fecha_format}</span>
                            </div>
                            <p class="text-[10px] text-slate-500 leading-relaxed normal-case">${n.texto}</p>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="text-center py-10 text-slate-600 text-[10px] uppercase font-bold">Clear Records</div>';
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

        function openCreatePostModal() {
            // Set current datetime as default
            const now = new Date();
            const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            document.getElementById('post_at_input').value = localDateTime;
            
            // Open modal
            document.getElementById('postModal').classList.remove('hidden');
            // Attempt to auto-generate content if there's a prompt
            try { generateAIContent(true); } catch(e) { console.warn('Auto AI generation skipped', e); }
        }

        function editPost(post) {
            const modal = document.getElementById('editPostModal');
            const form = document.getElementById('editPostForm');
            
            // Set form action
            form.action = `/dashboard/facebook/${post.id}`;
            
            // Fill fields
            document.getElementById('edit_content').value = post.content;
            document.getElementById('edit_status').value = post.status || 'scheduled';
            document.getElementById('edit_facebook_account_id').value = post.facebook_account_id || '';
            
            if (post.post_at) {
                // Format date for datetime-local input (YYYY-MM-DDTHH:mm)
                const date = new Date(post.post_at);
                const localDate = new Date(date.getTime() - date.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
                document.getElementById('edit_post_at').value = localDate;
            } else {
                document.getElementById('edit_post_at').value = '';
            }
            
            
            modal.classList.remove('hidden');
        }

        function moveSlide(event, postId, direction, total) {
            event.preventDefault();
            event.stopPropagation();
            
            const container = document.getElementById('carousel-' + postId);
            const track = document.getElementById('track-' + postId);
            let current = parseInt(container.dataset.current || 0);
            
            let next = current + direction;
            if (next < 0) next = total - 1;
            if (next >= total) next = 0;
            
            container.dataset.current = next;
            track.style.transform = `translateX(-${next * 100}%)`;
            
            // Update dots
            for(let i=0; i<total; i++) {
                const dot = document.getElementById(`dot-${postId}-${i}`);
                if (dot) {
                    if(i === next) {
                        dot.classList.remove('bg-white/40');
                        dot.classList.add('bg-white', 'scale-125');
                    } else {
                        dot.classList.add('bg-white/40');
                        dot.classList.remove('bg-white', 'scale-125');
                    }
                }
            }
        }

        async function generateAIContent(auto = false) {
            const prompt = document.getElementById('ai_prompt').value;
            if (!prompt) {
                if (!auto) alert('Please add an AI Deployment Strategy prompt before synthesizing.');
                return;
            }

            const btn = document.getElementById('aiBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span class="ml-2">Synthesizing</span>';

            // show AI generating modal
            const aiModal = document.getElementById('aiGeneratingModal');
            aiModal.classList.remove('hidden');

            try {
                const n8nWebhook = 'https://n8n.srv1137974.hstgr.cloud/webhook/poster';
                const payload = {
                    prompt: prompt,
                    strategy: 'AI Deployment Strategy',
                    user_id: '{{ Auth::id() }}',
                    user_email: '{{ Auth::user()->email }}'
                };

                const response = await fetch(n8nWebhook, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const text = await response.text();
                    console.error('n8n webhook error:', response.status, text);
                    alert('AI webhook returned an error: ' + response.status);
                    return;
                }

                const contentType = response.headers.get('content-type') || '';
                let data;
                if (contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    // fallback to plain text
                    const text = await response.text();
                    data = { content: text };
                }

                // Handle array responses (e.g., [{ "facebook_post": "..." }])
                let generated = '';
                if (Array.isArray(data) && data.length > 0) {
                    const first = data[0];
                    generated = first.facebook_post || first.facebookPost || first.content || first.generated || first.text || first.message || (typeof first === 'string' ? first : '');
                } else {
                    generated = data.facebook_post || data.facebookPost || data.content || data.generated || data.text || data.message || (typeof data === 'string' ? data : '');
                }

                if (generated) {
                    const text = (typeof generated === 'object') ? JSON.stringify(generated) : String(generated);
                    const cleaned = text.trim();
                    const createEl = document.getElementById('create_content');
                    const editEl = document.getElementById('edit_content');
                    if (createEl) createEl.value = cleaned;
                    if (editEl) editEl.value = cleaned;
                } else {
                    console.warn('No generated content found in webhook response', data);
                }
            } catch (error) {
                console.error('AI Generation failed:', error);
                if (!auto) alert('AI Generation failed: ' + (error.message || error));
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
                aiModal.classList.add('hidden');
            }
        }

        function handleFormSubmit(event, btnId) {
            const btn = document.getElementById(btnId);
            if (btn.disabled) {
                event.preventDefault();
                return false;
            }
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            return true;
        }
    </script>
</body>
</html>
