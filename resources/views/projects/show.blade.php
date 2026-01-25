<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} | Control Proyectos</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .heading-font { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(24px); }
        .gradient-border { position: relative; }
        .gradient-border::after {
            content: ''; position: absolute; inset: 0; border-radius: inherit;
            padding: 1px; background: linear-gradient(to bottom right, rgba(255,255,255,0.1), transparent, rgba(99,102,241,0.2));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
        }
    </style>
</head>
<body class="bg-black text-slate-300 antialiased overflow-x-hidden">

    <!-- Premium Nav -->
    <nav class="fixed top-0 inset-x-0 z-50 px-6 py-6 transition-all duration-500" id="mainNav">
        <div class="max-w-7xl mx-auto flex items-center justify-between pointer-events-none">
            <a href="{{ route('dashboard.projects') }}" class="pointer-events-auto group flex items-center space-x-4 bg-white/5 hover:bg-white/10 px-5 py-2.5 rounded-full border border-white/10 backdrop-blur-md transition-all">
                <svg class="w-4 h-4 text-indigo-400 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-white">Volver</span>
            </a>
            
            <div class="pointer-events-auto hidden md:flex items-center space-x-2 bg-indigo-600/10 px-4 py-2 rounded-full border border-indigo-500/20 backdrop-blur-md">
                <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">ID: CTR-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <main>
        
        <!-- Interactive Hero Section -->
        <section class="relative h-[90vh] min-h-[700px] flex items-center justify-center overflow-hidden">
            <!-- Background Image with dynamic zoom -->
            <div class="absolute inset-0 z-0">
                @if(count($project->imgs) > 0)
                    <img src="{{ asset($project->imgs[0]) }}" class="w-full h-full object-cover scale-110 opacity-60" id="heroImage" alt="{{ $project->name }}">
                @else
                    <div class="w-full h-full bg-slate-900 bg-[radial-gradient(circle_at_50%_50%,_rgba(67,56,202,0.2),_rgba(0,0,0,1))]"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black to-transparent"></div>
            </div>

            <!-- Title Overlay -->
            <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">
                <div class="inline-flex items-center space-x-3 bg-white/5 border border-white/10 px-4 py-2 rounded-full mb-8 backdrop-blur-sm animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">{{ $project->type }}</span>
                </div>
                <h1 class="heading-font text-6xl md:text-8xl lg:text-9xl font-bold text-white leading-none tracking-tighter mb-10 select-none animate-in fade-in slide-in-from-bottom-8 duration-1000">
                    {{ $project->name }}
                </h1>
                <div class="h-24 flex items-center justify-center">
                    <div class="w-[1px] h-full bg-gradient-to-b from-indigo-500 to-transparent"></div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center">
                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-500 mb-4">Descubrir</span>
                <div class="w-5 h-8 border-2 border-white/10 rounded-full flex justify-center p-1">
                    <div class="w-1 h-2 bg-indigo-500 rounded-full animate-bounce"></div>
                </div>
            </div>
        </section>

        <!-- Informative Narrative Section -->
        <section class="max-w-7xl mx-auto px-6 py-24 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                
                <!-- Left: Content Meta -->
                <div class="lg:col-span-4 space-y-12">
                    <div class="glass gradient-border rounded-[2.5rem] p-10 space-y-10">
                        <div>
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6">Especificaciones</h3>
                            <div class="space-y-6">
                                <div class="flex items-center justify-between border-b border-white/5 pb-4">
                                    <span class="text-xs font-medium text-slate-400">Vertical</span>
                                    <span class="text-xs font-bold text-white">{{ $project->type }}</span>
                                </div>
                                <div class="flex items-center justify-between border-b border-white/5 pb-4">
                                    <span class="text-xs font-medium text-slate-400">Estado</span>
                                    <span class="flex items-center text-xs font-bold {{ $project->active ? 'text-emerald-400' : 'text-slate-500' }}">
                                        @if($project->active)
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Sistema Vivo 
                                        @else 
                                            Desconectado 
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between border-b border-white/5 pb-4">
                                    <span class="text-xs font-medium text-slate-400">Lanzamiento</span>
                                    <span class="text-xs font-bold text-white">{{ $project->created_at->format('d / m / Y') }}</span>
                                </div>
                            </div>
                        </div>

                        @php
                            $integrations = is_array($project->integrations) ? $project->integrations : json_decode($project->integrations, true);
                            if (!is_array($integrations)) $integrations = [];
                        @endphp

                        @if(count($integrations) > 0)
                        <div>
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6">Integraciones Core</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($integrations as $item)
                                    <span class="px-3 py-1.5 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-[9px] font-bold text-indigo-400 uppercase tracking-wider">#{{ $item }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div>
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6">Stack Estándar</h3>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $tags = ['Next.js', 'Tailwind', 'AI Core', 'Cloud Infrastructure', 'Automation'];
                                    if(str_contains(strtolower($project->type), 'mobile')) $tags = ['Flutter', 'Firebase', 'Native UI'];
                                    if(str_contains(strtolower($project->type), 'web')) $tags = ['React', 'Laravel', 'Vercel'];
                                @endphp
                                @foreach($tags as $tag)
                                    <span class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-[9px] font-bold text-slate-400 uppercase tracking-wider">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Detailed Narrative -->
                <div class="lg:col-span-8">
                    <h2 class="heading-font text-4xl md:text-5xl font-bold text-white mb-10 leading-tight">
                        Transformando conceptos en <span class="text-indigo-400 italic">realidades digitales</span> de alto impacto.
                    </h2>
                    <div class="prose prose-invert prose-indigo max-w-none">
                        <p class="text-xl text-slate-400 leading-relaxed font-light mb-10 whitespace-pre-wrap">
                            {{ $project->description ?: 'Este despliegue representa un hito técnico en nuestra infraestructura actual. Aunque la narrativa técnica detallada está pendiente de registro, este activo digital ya está plenamente operativo y cumpliendo con los estándares de calidad del sistema Control.' }}
                        </p>

                        @if($project->video_url)
                            @php
                                $videoId = '';
                                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $project->video_url, $match)) {
                                    $videoId = $match[1];
                                }
                            @endphp

                            @if($videoId)
                            <div class="mt-16 pt-16 border-t border-white/10">
                                <h3 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.5em] mb-8 text-center">Video Demonstration</h3>
                                <div class="relative w-full aspect-video rounded-[2.5rem] overflow-hidden gradient-border shadow-2xl">
                                    <iframe 
                                        src="https://www.youtube.com/embed/{{ $videoId }}" 
                                        class="absolute inset-0 w-full h-full"
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                            @endif
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16 pt-16 border-t border-white/10">
                            <div class="group p-8 rounded-3xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                                <div class="w-10 h-10 bg-indigo-600/20 rounded-xl flex items-center justify-center mb-6 text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <h4 class="text-white font-bold mb-3 uppercase tracking-wider text-xs">Rendimiento Core</h4>
                                <p class="text-[11px] text-slate-500 leading-relaxed">Arquitectura optimizada para baja latencia y alta escalabilidad en despliegues productivos.</p>
                            </div>
                            <div class="group p-8 rounded-3xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                                <div class="w-10 h-10 bg-indigo-600/20 rounded-xl flex items-center justify-center mb-6 text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 011-1V4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <h4 class="text-white font-bold mb-3 uppercase tracking-wider text-xs">Integración Modular</h4>
                                <p class="text-[11px] text-slate-500 leading-relaxed">Diseño basado en componentes reutilizables que aceleran el ciclo de vida del desarrollo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Immersive Media System -->
        @if(count($project->imgs) > 1)
        <section class="bg-white/[0.01] py-32 border-y border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-end justify-between mb-20 gap-8">
                    <div class="max-w-xl">
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.5em] mb-4 block">Visual Evidence</span>
                        <h2 class="heading-font text-5xl font-bold text-white italic">Inmersión <span class="text-indigo-600">Visual</span></h2>
                    </div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest hidden md:block select-none">Archivo Digital Rev 0.2</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10">
                    @foreach(array_slice($project->imgs, 1) as $index => $img)
                        @php
                            $span = ($index % 3 == 0) ? 'lg:col-span-8' : 'lg:col-span-4';
                            $height = ($index % 3 == 0) ? 'h-[500px]' : 'h-[400px]';
                        @endphp
                        <div class="{{ $span }} group relative {{ $height }} rounded-[3rem] overflow-hidden gradient-border">
                            <img src="{{ asset($img) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Detalle del proyecto">
                            <div class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-md">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-black shadow-2xl scale-75 group-hover:scale-100 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Footer Call to Action -->
        <footer class="py-40 text-center relative overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto px-6">
                <h3 class="heading-font text-3xl font-bold text-white mb-6 uppercase tracking-tighter italic">¿Interesado en esta tecnología?</h3>
                <p class="text-slate-500 text-sm mb-12 font-medium">Este proyecto está disponible para auditoría técnica o demostración funcional bajo demanda.</p>
                <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                    <a href="{{ route('dashboard.projects') }}" class="w-full md:w-auto px-10 py-5 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-indigo-500 transition-all shadow-2xl active:scale-95">
                        Volver al Sistema
                    </a>
                    <button onclick="window.print()" class="w-full md:w-auto px-10 py-5 bg-white/5 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border border-white/10 hover:bg-white/10 transition-all">
                        Generar Reporte PDF
                    </button>
                </div>
            </div>
        </footer>

    </main>

    <script>
        // Scroll Intelligence
        const nav = document.getElementById('mainNav');
        const heroImg = document.getElementById('heroImage');
        
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            
            // Zoom effect on hero
            if (heroImg && scrolled < window.innerHeight) {
                heroImg.style.transform = `scale(${1.1 + (scrolled * 0.0002)})`;
                heroImg.style.opacity = (0.6 - (scrolled * 0.0005));
            }

            // Nav backdrop
            if (scrolled > 100) {
                nav.classList.add('bg-black/60', 'backdrop-blur-xl', 'border-b', 'border-white/5', 'py-4');
            } else {
                nav.classList.remove('bg-black/60', 'backdrop-blur-xl', 'border-b', 'border-white/5', 'py-4');
            }
        });
    </script>
</body>
</html>
