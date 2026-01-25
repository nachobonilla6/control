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
                
                <button onclick="document.getElementById('postModal').classList.remove('hidden')" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-indigo-600/20 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Create Post
                </button>
            </div>

            <!-- Posts Grid (2 per row) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                @forelse($posts as $post)
                <div class="group bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:border-indigo-500/30 hover:shadow-2xl flex flex-col md:flex-row h-full">
                    <!-- Image Carousel Area -->
                    <div class="md:w-1/2 relative bg-slate-950 aspect-square md:aspect-auto">
                        @if($post->image1)
                            <img src="{{ $post->image1 }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                        @else
                            <div class="w-full h-full flex items-center justify-center opacity-20">
                                <span class="text-6xl">📱</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 flex space-x-1">
                            @if($post->image1) <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div> @endif
                            @if($post->image2) <div class="w-1.5 h-1.5 rounded-full bg-indigo-500/50"></div> @endif
                            @if($post->image3) <div class="w-1.5 h-1.5 rounded-full bg-indigo-500/50"></div> @endif
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="md:w-1/2 p-8 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[8px] font-black text-indigo-400 tracking-widest uppercase bg-indigo-600/10 px-3 py-1 rounded-full border border-indigo-500/10">Facebook Post</span>
                                <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed line-clamp-6 font-medium lowercase">
                                {{ $post->content }}
                            </p>
                        </div>

                        <div class="pt-6 border-t border-white/5 space-y-4">
                            @if($post->post_at)
                            <div class="flex items-center space-x-2">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span class="text-[9px] font-black text-emerald-500 tracking-widest uppercase">Scheduled: {{ $post->post_at->format('M d, H:i') }}</span>
                            </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    @if($post->image1) <img src="{{ $post->image1 }}" class="w-7 h-7 rounded-lg border-2 border-slate-900 object-cover"> @endif
                                    @if($post->image2) <img src="{{ $post->image2 }}" class="w-7 h-7 rounded-lg border-2 border-slate-900 object-cover"> @endif
                                    @if($post->image3) <img src="{{ $post->image3 }}" class="w-7 h-7 rounded-lg border-2 border-slate-900 object-cover"> @endif
                                </div>
                                <form action="{{ route('dashboard.facebook.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Abort this post deployment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all border border-red-500/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </form>
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

            <form action="{{ route('dashboard.facebook.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Post Content</label>
                            <textarea name="content" required rows="6" placeholder="What's happening on the network?" 
                                      class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-medium text-slate-200 resize-none lowercase"></textarea>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Schedule Deployment</label>
                            <input type="datetime-local" name="post_at" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all">
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 01 (URL)</label>
                            <input type="url" name="image1" placeholder="https://image-server.com/photo1.jpg" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all lowercase">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 02 (URL)</label>
                            <input type="url" name="image2" placeholder="https://image-server.com/photo2.jpg" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all lowercase">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2 uppercase">Media Asset 03 (URL)</label>
                            <input type="url" name="image3" placeholder="https://image-server.com/photo3.jpg" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all lowercase">
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

    <script>
        const accountBtn = document.getElementById('accountBtn');
        if (accountBtn) {
            accountBtn.addEventListener('click', () => {
                window.location.href = "{{ route('dashboard') }}";
            });
        }
    </script>
</body>
</html>
