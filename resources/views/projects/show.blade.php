<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} - Narrative</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,900;1,900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .hero-title { font-family: 'Playfair+Display', serif; }
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(20px); }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased overflow-x-hidden">

    <!-- Top Navigation (Floating) -->
    <nav class="fixed top-8 left-1/2 -translate-x-1/2 z-50 px-6 py-4 glass border border-white/5 rounded-full flex items-center space-x-8 shadow-2xl">
        <a href="{{ route('dashboard.projects') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400 hover:text-white transition-colors">Infraestructura</a>
        <div class="h-4 w-[1px] bg-white/10"></div>
        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 italic">{{ $project->type }}</span>
    </nav>

    <!-- Main Narrative Content -->
    <article class="relative">
        
        <!-- Immersive Hero -->
        <header class="relative h-[85vh] w-full overflow-hidden">
            @if(count($project->imgs) > 0)
                <img src="{{ asset($project->imgs[0]) }}" class="w-full h-full object-cover scale-105" alt="{{ $project->name }}">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/40 via-slate-950/20 to-slate-950"></div>
            @else
                <div class="w-full h-full bg-slate-900 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-indigo-900/20 to-slate-950"></div>
            @endif

            <!-- Hero Text Overlay -->
            <div class="absolute inset-0 flex flex-col items-center justify-end pb-32 px-6 text-center">
                <div class="max-w-5xl">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-600/20 text-indigo-400 text-[10px] font-black uppercase border border-indigo-500/20 mb-8 animate-bounce">Deploy Core Active</span>
                    <h1 class="hero-title text-6xl md:text-8xl lg:text-9xl font-black text-white leading-none tracking-tighter mb-10 italic">
                        {{ $project->name }}
                    </h1>
                </div>
            </div>
        </header>

        <!-- Interior Content -->
        <div class="max-w-4xl mx-auto px-6 -mt-20 relative z-10">
            <div class="glass border border-white/5 rounded-[4rem] p-12 md:p-20 shadow-[0_50px_100px_rgba(0,0,0,0.4)]">
                
                <div class="flex flex-wrap items-center gap-6 mb-16 border-b border-white/5 pb-10">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1 leading-none">Status</span>
                        <span class="text-sm font-bold text-emerald-400 flex items-center">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                            Online Infrastructure
                        </span>
                    </div>
                    <div class="w-[1px] h-8 bg-white/5"></div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1 leading-none">Registered</span>
                        <span class="text-sm font-bold text-white">{{ $project->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="prose prose-invert prose-indigo max-w-none">
                    <p class="text-xl md:text-2xl text-slate-300 leading-relaxed font-light first-letter:text-7xl first-letter:font-black first-letter:text-indigo-500 first-letter:mr-4 first-letter:float-left italic opacity-90">
                        {{ $project->description ?: 'This project maintains a silent footprint in the infrastructure logs. No technical narrative has been provided for this specific deployment sequence.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Visual Heritage Gallery -->
        @if(count($project->imgs) > 1)
        <section class="max-w-7xl mx-auto px-6 py-32">
            <div class="text-center mb-20">
                <h2 class="hero-title text-4xl md:text-5xl font-black text-white italic mb-4">Capturas de <span class="text-indigo-500">Sistema</span></h2>
                <div class="w-24 h-1 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">
                @foreach(array_slice($project->imgs, 1) as $img)
                <div class="relative group rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl hover:border-indigo-500/30 transition-all duration-700">
                    <img src="{{ asset($img) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Footer / Call to Action -->
        <footer class="py-32 bg-slate-900/50 border-t border-white/5 text-center">
            <div class="max-w-2xl mx-auto px-6">
                <h3 class="text-2xl font-black text-white uppercase tracking-tighter italic mb-8">Fin de la Transmisión</h3>
                <a href="{{ route('dashboard.projects') }}" class="inline-flex items-center space-x-4 px-10 py-5 bg-white text-slate-950 rounded-2xl text-xs font-black uppercase tracking-[0.3em] hover:bg-indigo-500 hover:text-white transition-all shadow-2xl active:scale-95 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span>Volver a Galería</span>
                </a>
            </div>
        </footer>

    </article>

    <script>
        // Subtle Parallax on scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const heroImg = document.querySelector('header img');
            if (heroImg) {
                heroImg.style.transform = `translateY(${scrolled * 0.1}px) scale(${1.05 + (scrolled * 0.0001)})`;
            }
        });
    </script>
</body>
</html>
