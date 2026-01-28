<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexGen - Automatización de WhatsApp Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              whatsapp: '#25D366',
              brand: {
                dark: '#050a10',
                card: '#0c141d',
                glow: '#6366f1',
              },
            },
            animation: {
              'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
              'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
              float: {
                '0%, 100%': { transform: 'translateY(0)' },
                '50%': { transform: 'translateY(-20px)' },
              }
            }
          },
        },
      };
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #050a10; }
        .glass { background: rgba(12, 20, 29, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.05); }
        .glow-text { text-shadow: 0 0 20px rgba(99, 102, 241, 0.5); }
        .bg-grid { background-image: radial-gradient(rgba(99, 102, 241, 0.1) 1px, transparent 0); background-size: 40px 40px; }
    </style>
</head>
<body class="text-slate-200 selection:bg-indigo-500/30 selection:text-white overflow-x-hidden">

    <!-- Hero Background -->
    <div class="fixed inset-0 bg-grid z-0 shadow-[inset_0_0_100px_rgba(0,0,0,1)]"></div>
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-600/10 blur-[120px] rounded-full"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-600/10 blur-[120px] rounded-full"></div>

    <!-- Nav -->
    <nav class="fixed top-0 w-full z-50 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-2 group cursor-pointer">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform duration-300">
                    <span class="font-black text-white italic text-xl">N</span>
                </div>
                <span class="text-xl font-bold tracking-tighter text-white uppercase">NexGen <span class="text-indigo-400">Labs</span></span>
            </div>
            
            <div class="hidden md:flex items-center space-x-10 text-sm font-medium text-slate-400">
                <a href="#features" class="hover:text-white transition-colors">Características</a>
                <a href="#solutions" class="hover:text-white transition-colors">Soluciones</a>
                <a href="#pricing" class="hover:text-white transition-colors">Planes</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-300 hover:text-white transition-colors px-4">Acceso Staff</a>
                <a href="https://wa.me/your-number" class="bg-white text-brand-dark px-6 py-2.5 rounded-full font-bold text-sm hover:bg-indigo-50 transition-all shadow-xl active:scale-95">Empezar Ahora</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-40 pb-20 px-6 z-10 overflow-hidden">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] mb-8 animate-bounce">
                <span>Versión 2.0 ya disponible</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl font-black text-white leading-none tracking-tighter mb-8">
                TU EMPRESA EN <br>
                <span class="bg-gradient-to-r from-indigo-400 via-emerald-400 to-indigo-500 bg-clip-text text-transparent glow-text">PILOTO AUTOMÁTICO</span>
            </h1>
            
            <p class="max-w-2xl mx-auto text-slate-400 text-lg md:text-xl leading-relaxed mb-12">
                Automatizamos tus ventas y atención al cliente en WhatsApp utilizando Inteligencia Artificial avanzada. Escala tu negocio mientras duermes.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="https://wa.me/your-number" class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-10 py-5 rounded-2xl font-black text-lg shadow-[0_0_30px_rgba(79,70,229,0.3)] hover:shadow-[0_0_50px_rgba(79,70,229,0.5)] transition-all hover:-translate-y-1">
                    ASESORÍA GRATUITA
                </a>
                <button class="w-full sm:w-auto glass text-white px-10 py-5 rounded-2xl font-bold flex items-center justify-center space-x-3 hover:bg-white/5 transition-all">
                    <svg class="w-6 h-6 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 4-8 4z"/></svg>
                    <span>Ver Demo en Vivo</span>
                </button>
            </div>
        </div>

        <!-- Floating UI Mockup -->
        <div class="mt-24 relative max-w-5xl mx-auto animate-float">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-[2.5rem] blur opacity-20 group-hover:opacity-100 transition duration-1000"></div>
            <div class="relative glass rounded-[2.5rem] p-4 shadow-2xl border border-white/10">
                 <!-- Browser Chrome -->
                <div class="flex items-center space-x-2 mb-4 px-4 pt-2">
                    <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500/50"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                    <div class="flex-1 bg-white/5 rounded-lg h-6 mx-4"></div>
                </div>
                <div class="bg-indigo-900/10 rounded-[1.5rem] h-[500px] overflow-hidden relative group">
                    <img src="https://miro.medium.com/v2/resize:fit:1400/1*MtZ0n0nFFWmebZTncI2sqA.jpeg" 
                         class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700" 
                         alt="WhatsApp Automation System">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-transparent opacity-60"></div>
                    <div class="absolute bottom-10 left-10 text-left">
                        <div class="inline-flex items-center space-x-2 bg-indigo-600/20 backdrop-blur-md px-3 py-1 rounded-full border border-indigo-500/30 mb-4">
                            <div class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Live Infrastructure</span>
                        </div>
                        <h3 class="text-3xl font-black text-white leading-none uppercase italic">Neural Link <br> Established</h3>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features -->
    <section id="features" class="relative py-32 px-6 z-10 bg-brand-dark">
        <div class="max-w-7xl mx-auto text-center">
            <div class="mb-20">
                <h2 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.4em] mb-4">Potencia sin límites</h2>
                <p class="text-4xl font-bold text-white uppercase tracking-tighter italic">Características de Elite</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass p-10 rounded-[2.5rem] hover:border-indigo-500/50 transition-all group">
                    <div class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-8 border border-indigo-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 uppercase italic">Chatbots con IA</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Entrenamiento personalizado con tus manuales de venta para asegurar cierres efectivos 24/7.</p>
                </div>

                <div class="glass p-10 rounded-[2.5rem] hover:border-emerald-500/50 transition-all group">
                    <div class="w-14 h-14 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-8 border border-emerald-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 uppercase italic">Dashboard Analítico</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Visualiza el rendimiento de tus bots, tasas de conversión y volumen de mensajes en tiempo real.</p>
                </div>

                <div class="glass p-10 rounded-[2.5rem] hover:border-indigo-500/50 transition-all group">
                    <div class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-8 border border-indigo-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 uppercase italic">Integración Multi-vía</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Conecta tu WhatsApp con CRMs externos, Google Sheets o cualquier sistema vía Webhooks.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions -->
    <section id="solutions" class="relative py-32 px-6 z-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-end justify-between mb-20 gap-8">
                <div class="max-w-xl">
                    <h2 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.4em] mb-4">Soluciones Verticales</h2>
                    <p class="text-5xl font-black text-white leading-none tracking-tighter uppercase italic">Diseñado para <span class="text-indigo-500">crecer</span></p>
                </div>
                <p class="text-slate-500 max-w-sm text-sm">Soluciones personalizadas que se adaptan a la complejidad y el volumen de tu operación comercial.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 text-left">
                <div class="relative overflow-hidden group rounded-[3rem]">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-indigo-900 opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="glass p-12 relative z-10 border-white/5">
                        <span class="inline-block px-4 py-1 rounded-full bg-indigo-600 text-[10px] font-black uppercase mb-6 shadow-lg shadow-indigo-600/20">E-Commerce</span>
                        <h3 class="text-3xl font-black text-white mb-4 uppercase leading-none">Ventas <br> Automatizadas</h3>
                        <p class="text-slate-400 mb-8 leading-relaxed">Carrito de compras integrado en WhatsApp, gestión de pedidos y seguimiento de envíos sin intervención humana.</p>
                        <ul class="space-y-3 mb-10">
                            <li class="flex items-center text-xs font-bold text-slate-300 italic"><svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg> CATÁLOGO EN TIEMPO REAL</li>
                            <li class="flex items-center text-xs font-bold text-slate-300 italic"><svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg> PAGOS SEGUROS</li>
                        </ul>
                        <a href="https://wa.me/your-number" class="text-white text-xs font-black uppercase tracking-widest border-b-2 border-indigo-500 pb-1 hover:border-white transition-all">Saber más</a>
                    </div>
                </div>

                <div class="relative overflow-hidden group rounded-[3rem]">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-emerald-900 opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="glass p-12 relative z-10 border-white/5">
                        <span class="inline-block px-4 py-1 rounded-full bg-emerald-600 text-[10px] font-black uppercase mb-6 shadow-lg shadow-emerald-600/20">Soporte técnico</span>
                        <h3 class="text-3xl font-black text-white mb-4 uppercase leading-none">Atención <br> Inteligente</h3>
                        <p class="text-slate-400 mb-8 leading-relaxed">Resolución de dudas frecuentes, generación de tickets y escalado a humanos solo cuando es estrictamente necesario.</p>
                        <ul class="space-y-3 mb-10">
                            <li class="flex items-center text-xs font-bold text-slate-300 italic"><svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg> MULTI-AGENTE SEGURO</li>
                            <li class="flex items-center text-xs font-bold text-slate-300 italic"><svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg> TRADUCCIÓN AUTOMÁTICA</li>
                        </ul>
                        <a href="https://wa.me/your-number" class="text-white text-xs font-black uppercase tracking-widest border-b-2 border-emerald-500 pb-1 hover:border-white transition-all">Saber más</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio / Projects -->
    <section class="relative py-32 px-6 z-10 overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-600/5 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-[10px] font-black text-white bg-indigo-600 inline-block px-3 py-1 rounded-md uppercase tracking-[0.4em] mb-6">Portafolio</h2>
                <p class="text-5xl font-black text-white uppercase italic tracking-tighter">Proyectos <br> Destacados</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                @forelse($projects ?? [] as $project)
                @php
                    $imgs = is_array($project->images) ? $project->images : json_decode($project->images, true);
                    if (!is_array($imgs)) $imgs = [];
                    $firstImg = count($imgs) > 0 ? $imgs[0] : null;
                @endphp
                    @if($loop->first)
                    <!-- First project - Large featured -->
                    <a href="{{ route('projects.show', $project->id) }}" class="lg:col-span-2 lg:row-span-2 group relative glass rounded-[2.5rem] overflow-hidden hover:border-indigo-500/50 transition-all hover:scale-[1.02]">
                        @if($firstImg)
                        <img src="{{ asset($firstImg) }}" alt="{{ $project->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-600 to-indigo-900 flex items-center justify-center">
                            <svg class="w-20 h-20 text-indigo-400 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-8 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <h3 class="text-2xl font-black text-white mb-2 line-clamp-2">{{ $project->name }}</h3>
                            <p class="text-sm font-medium text-slate-300 line-clamp-3">{{ $project->description ?? 'Proyecto destacado' }}</p>
                            @if($project->integrations)
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach($project->integrations as $integration)
                                <span class="px-2 py-1 bg-indigo-600/30 text-[9px] font-bold text-indigo-300 rounded">{{ $integration }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </a>
                    @else
                    <!-- Other projects - Small grid -->
                    <a href="{{ route('projects.show', $project->id) }}" class="group relative glass rounded-[2.5rem] overflow-hidden hover:border-indigo-500/50 transition-all hover:scale-[1.02]">
                        @if($firstImg)
                        <img src="{{ asset($firstImg) }}" alt="{{ $project->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-indigo-600 to-indigo-900 flex items-center justify-center">
                            <svg class="w-16 h-16 text-indigo-400 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            <h3 class="text-lg font-black text-white mb-1 line-clamp-2">{{ $project->name }}</h3>
                            <p class="text-xs font-medium text-slate-300 line-clamp-1">{{ $project->description ?? 'Proyecto' }}</p>
                            @if($project->integrations)
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($project->integrations as $integration)
                                <span class="px-1.5 py-0.5 bg-indigo-600/30 text-[8px] font-bold text-indigo-300 rounded">{{ $integration }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </a>
                    @endif
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 text-sm">No hay proyectos disponibles en este momento.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="relative py-32 px-6 z-10 bg-brand-dark overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-600/5 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20 text-balance">
                <h2 class="text-[10px] font-black text-white bg-indigo-600 inline-block px-3 py-1 rounded-md uppercase tracking-[0.4em] mb-6">Inversión</h2>
                <p class="text-5xl font-black text-white uppercase italic tracking-tighter">Planes que <br> escalan contigo</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Plan 1 -->
                <div class="glass p-10 rounded-[3rem] border-white/5 hover:bg-white/[0.05] transition-all flex flex-col items-center group">
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-8">Starter</h4>
                    <div class="flex items-baseline space-x-1 mb-8">
                        <span class="text-xl font-bold text-white tracking-tighter italic">$</span>
                        <span class="text-6xl font-black text-white italic tracking-tighter">49</span>
                        <span class="text-xs font-bold text-slate-500 uppercase">/mes</span>
                    </div>
                    <ul class="w-full space-y-4 mb-12 text-sm text-slate-400 font-medium border-y border-white/5 py-8">
                        <li class="flex items-center justify-between"><span>1 Bot Activo</span> <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z"/></svg></li>
                        <li class="flex items-center justify-between"><span>1,000 Mensajes</span> <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z"/></svg></li>
                        <li class="flex items-center justify-between opacity-30 italic"><span>Soporte Prioritario</span> <span>X</span></li>
                    </ul>
                    <a href="https://wa.me/your-number" class="w-full py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-black uppercase text-[10px] tracking-widest hover:bg-white hover:text-brand-dark transition-all text-center">Seleccionar</a>
                </div>

                <!-- Plan 2 (Popular) -->
                <div class="glass p-12 rounded-[3.5rem] border-indigo-500/30 scale-105 bg-indigo-600/5 relative flex flex-col items-center group overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 bg-indigo-600 text-[8px] font-black text-white px-4 py-1.5 rounded-bl-2xl uppercase tracking-widest">Más Popular</div>
                    <h4 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-8">Business Pro</h4>
                    <div class="flex items-baseline space-x-1 mb-8 text-indigo-400">
                        <span class="text-xl font-bold tracking-tighter italic">$</span>
                        <span class="text-6xl font-black italic tracking-tighter">149</span>
                        <span class="text-xs font-bold uppercase">/mes</span>
                    </div>
                    <ul class="w-full space-y-4 mb-12 text-sm text-slate-300 font-medium border-y border-indigo-500/20 py-8 italic">
                        <li class="flex items-center justify-between"><span>5 Bots Activos</span> <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z"/></svg></li>
                        <li class="flex items-center justify-between text-indigo-200"><span>Ilimitado Mensajes</span> <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z"/></svg></li>
                        <li class="flex items-center justify-between"><span>Soporte Prioritario</span> <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z"/></svg></li>
                    </ul>
                    <a href="https://wa.me/your-number" class="w-full py-5 rounded-3xl bg-indigo-600 text-white font-black uppercase text-[11px] tracking-widest hover:bg-indigo-50 transition-all shadow-xl shadow-indigo-600/30 text-center">Adquirir Pro</a>
                </div>

                <!-- Plan 3 -->
                <div class="glass p-10 rounded-[3rem] border-white/5 hover:bg-white/[0.05] transition-all flex flex-col items-center group">
                    <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-8">Enterprise</h4>
                    <div class="flex items-baseline space-y-0 mb-8">
                        <span class="text-4xl font-black text-white italic tracking-tighter">CUSTOM</span>
                    </div>
                    <ul class="w-full space-y-4 mb-12 text-sm text-slate-400 font-medium border-y border-white/5 py-8">
                        <li class="flex items-center justify-between"><span>Bots Personalizados</span> <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z"/></svg></li>
                        <li class="flex items-center justify-between"><span>Infraestructura Dedicada</span> <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5z"/></svg></li>
                    </ul>
                    <a href="https://wa.me/your-number" class="w-full py-4 rounded-2xl bg-white text-brand-dark font-black uppercase text-[10px] tracking-widest hover:bg-indigo-50 transition-all text-center">Consultar</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-24 px-6 z-10">
        <div class="max-w-5xl mx-auto glass rounded-[3rem] p-12 md:p-20 text-center overflow-hidden relative">
            <div class="absolute inset-0 bg-indigo-600/10 blur-[100px]"></div>
            <div class="relative z-10">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 tracking-tighter uppercase italic">¿Listo para transformar <br> tu negocio?</h2>
                <p class="text-slate-400 text-lg mb-12 max-w-2xl mx-auto">Únete a cientos de empresas que ya están ahorrando miles de horas en soporte al cliente.</p>
                <a href="https://wa.me/your-number" class="inline-block bg-white text-brand-dark px-12 py-5 rounded-2xl font-black text-xl hover:bg-slate-100 transition-all hover:scale-105 active:scale-95 shadow-2xl">
                    HABLAR CON UN EXPERTO
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative py-12 px-6 z-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-slate-500 text-xs">
            <p>&copy; 2026 NexGen Labs - Powered by Modern AI.</p>
            <div class="flex space-x-8 mt-6 md:mt-0 uppercase tracking-widest font-black">
                <a href="#" class="hover:text-white transition-colors">Términos</a>
                <a href="#" class="hover:text-white transition-colors">Privacidad</a>
                <a href="#" class="hover:text-white transition-colors">Soporte</a>
            </div>
        </div>
    </footer>

</body>
</html>
