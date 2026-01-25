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
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.4em] mb-4">¿Por qué NexGen?</h2>
                <p class="text-4xl font-bold text-white uppercase tracking-tighter italic">Revolución en Mensajería</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass p-10 rounded-[2.5rem] hover:border-indigo-500/50 transition-all group">
                    <div class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-8 border border-indigo-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 uppercase italic">Chatbots con IA</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Nuestros bots no solo responden, entienden el contexto y cierran ventas utilizando tecnología GPT-4.</p>
                </div>

                <!-- Card 2 -->
                <div class="glass p-10 rounded-[2.5rem] hover:border-emerald-500/50 transition-all group">
                    <div class="w-14 h-14 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-8 border border-emerald-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 uppercase italic">CRM Integrado</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Gestiona todos tus leads de WhatsApp en un solo lugar. Organiza por etiquetas, etapas y recordatorios.</p>
                </div>

                <!-- Card 3 -->
                <div class="glass p-10 rounded-[2.5rem] hover:border-indigo-500/50 transition-all group">
                    <div class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-8 border border-indigo-500/20 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 uppercase italic">Envío Masivo Seguro</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Comunícate con miles de clientes en segundos con nuestro sistema anti-bloqueo avanzado.</p>
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
