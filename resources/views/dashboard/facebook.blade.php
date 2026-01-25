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
                <span class="text-xl font-bold text-indigo-400 font-inter group-hover/brand:text-white transition-colors">josh dev</span>
                <span class="text-slate-700 font-light text-xl italic font-inter">/</span>
                <span class="text-sm font-medium text-slate-400 tracking-wide uppercase font-inter group-hover/brand:text-indigo-400 transition-colors">Control Panel</span>
            </a>
            <span class="text-slate-800 font-light text-xl italic font-inter">/</span>
            <a href="{{ route('dashboard.facebook') }}" class="text-xs font-black text-indigo-500 tracking-[0.2em] hover:text-white transition-colors uppercase font-inter">Facebook</a>
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
                    <button onclick="document.getElementById('postModal').classList.remove('hidden')" class="h-[58px] px-8 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-indigo-600/20 active:scale-95 flex items-center">
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
                                    default => 'bg-indigo-500',
                                };
                            @endphp
                            <span class="text-[7px] font-black {{ $statusColor }} text-white px-3 py-1 rounded-full uppercase tracking-[0.2em] shadow-lg">
                                {{ $post->status ?? 'scheduled' }}
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
                                <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</span>
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
                                        {{ $post->post_at->format('M d, Y @ H:i') }}
                                    </span>
                                </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <div class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">
                                        Status: <span class="text-indigo-400">{{ $post->status ?? 'pending' }}</span>
                                    </div>
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

            <form action="{{ route('dashboard.facebook.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Post Content</label>
                            <textarea name="content" required rows="6" placeholder="What's happening on the network?" 
                                      class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-medium text-slate-200 resize-none lowercase"></textarea>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Target Account</label>
                            <select name="facebook_account_id" class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all appearance-none">
                                <option value="">Select Account (Optional)</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="relative">
                                <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Schedule Deployment</label>
                                <div class="relative">
                                    <input type="datetime-local" name="post_at" 
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
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em] uppercase">
                        Execute Staging
                    </button>
                </div>
            </form>
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

    <script>
        const accountBtn = document.getElementById('accountBtn');
        if (accountBtn) {
            accountBtn.addEventListener('click', () => {
                window.location.href = "{{ route('dashboard') }}";
            });
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
    </script>
</body>
</html>
