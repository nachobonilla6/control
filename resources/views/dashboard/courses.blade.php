<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos - Control Panel</title>
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
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-200 overflow-hidden uppercase">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-8 sticky top-0 z-40">
        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard') }}" class="text-xs font-black text-slate-500 hover:text-white transition-colors tracking-[0.2em]">Dashboard</a>
            <span class="text-slate-800">/</span>
            <span class="text-xs font-black text-indigo-400 tracking-[0.2em]">Learning Management</span>
        </div>
        <div class="flex items-center space-x-4">
             <button id="accountBtn" class="w-10 h-10 bg-slate-800 rounded-none border border-slate-700 flex items-center justify-center overflow-hidden hover:border-indigo-500/50 transition-all">
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xs font-black text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                @endif
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-8 md:p-12">
        <div class="max-w-7xl mx-auto">
            
            @if(session('success'))
            <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-none flex items-center shadow-lg animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black tracking-widest">{{ session('success') }}</span>
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">Gestión de <span class="text-indigo-500">Cursos</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2">Seguimiento de aprendizaje y objetivos</p>
                </div>
                
                <div class="flex items-center space-x-6 bg-slate-900 border border-slate-800 p-2 rounded-none">
                    <div class="px-6 py-2 text-center border-r border-slate-800">
                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Total</p>
                        <p class="text-xl font-black text-white">{{ count($courses) }}</p>
                    </div>
                    <div class="px-6 py-2 text-center border-r border-slate-800">
                        <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-1">Pending</p>
                        <p class="text-xl font-black text-white">{{ $courses->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="px-6 py-2 text-center">
                        <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest mb-1">Done</p>
                        <p class="text-xl font-black text-white">{{ $courses->where('status', 'done')->count() }}</p>
                    </div>
                </div>

                <button onclick="openCreateModal()" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-none text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-indigo-600/20 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Nuevo Curso
                </button>
            </div>

            <!-- Courses Grid (YouTube Style) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($courses as $course)
                <div class="group relative flex flex-col bg-slate-900 border border-slate-800 rounded-none overflow-hidden transition-all duration-500 hover:scale-[1.02] hover:border-indigo-500/30 hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)] active:scale-95">
                    
                    @php
                        $ytId = '';
                        if ($course->link && preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $course->link, $match)) {
                            $ytId = $match[1];
                        }

                        $statusConfig = [
                            'pending' => ['color' => 'bg-indigo-600', 'text' => 'PENDING'],
                            'done' => ['color' => 'bg-emerald-600', 'text' => 'DONE'],
                            'postponed' => ['color' => 'bg-amber-600', 'text' => 'POSTPONED'],
                            'archived' => ['color' => 'bg-slate-700', 'text' => 'ARCHIVED'],
                        ];
                        $config = $statusConfig[$course->status] ?? $statusConfig['pending'];
                    @endphp

                    <!-- Thumbnail Area -->
                    <div id="thumb-{{ $course->id }}" class="relative aspect-video w-full overflow-hidden bg-slate-950">
                        @if($ytId)
                            <img src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-60 group-hover:opacity-100" />
                            
                            <!-- Play Button (Center) -->
                            <button onclick="playVideo('{{ $course->id }}', '{{ $ytId }}')" class="absolute inset-0 flex items-center justify-center group/play z-10">
                                <div class="w-16 h-16 bg-indigo-600/80 group-hover/play:bg-indigo-500 rounded-none flex items-center justify-center backdrop-blur-md border border-white/20 transition-all scale-90 group-hover/play:scale-110 shadow-2xl">
                                    <svg class="w-6 h-6 text-white translate-x-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5.14v13.72a1 1 0 0 0 1.5.86l10.29-6.86a1 1 0 0 0 0-1.72L9.5 4.28a1 1 0 0 0-1.5.86z"/></svg>
                                </div>
                            </button>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 via-transparent to-slate-950 opacity-40 group-hover:opacity-60 transition-opacity"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-4xl filter blur-sm group-hover:blur-none transition-all duration-700 opacity-20 group-hover:opacity-40 select-none">🎓</span>
                            </div>
                        @endif
                        <form action="{{ route('dashboard.courses.toggle-status', $course->id) }}" method="POST" class="absolute top-4 right-4 z-20">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="Siguiente Estado" class="{{ $config['color'] }} px-3 py-1.5 rounded-none text-[8px] font-black tracking-widest shadow-lg hover:scale-110 transition-transform active:scale-95">
                                {{ $config['text'] }}
                            </button>
                        </form>

                        <!-- Action Overlay (Hover - Top Right) -->
                        <div class="absolute top-4 left-4 flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-300 z-30">
                            <button onclick="openEditModal({{ json_encode($course) }})" class="w-8 h-8 bg-black/60 hover:bg-indigo-600 text-white rounded-none flex items-center justify-center backdrop-blur-md border border-white/10 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <form action="{{ route('dashboard.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('¿Decommission this module?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-black/60 hover:bg-red-600 text-white rounded-none flex items-center justify-center backdrop-blur-md border border-white/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-sm font-black text-white leading-tight mb-2 line-clamp-2 tracking-tight overflow-hidden italic">
                                {{ $course->name }}
                            </h3>
                            @if($course->link)
                            <a href="{{ $course->link }}" target="_blank" class="inline-flex items-center text-[8px] font-black text-indigo-400 hover:text-indigo-300 transition-colors tracking-[0.2em] mb-4">
                                START LEARNING →
                            </a>
                            @else
                            <p class="text-[8px] font-bold text-slate-700 tracking-[0.2em] mb-4 italic">NO LINK ATTACHED</p>
                            @endif
                        </div>
                        
                        <!-- Timeline Info -->
                        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                            <span class="text-[9px] font-bold text-slate-600 tracking-widest">{{ $course->created_at->format('M / Y') }}</span>
                            <div class="flex -space-x-2">
                                <div class="w-5 h-5 rounded-none bg-slate-800 border-2 border-slate-900 flex items-center justify-center text-[7px] font-black text-slate-500">
                                    {{ substr($course->name, 0, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-24 text-center border-2 border-dashed border-slate-800 rounded-none">
                    <p class="text-xs font-black text-slate-700 tracking-[0.3em] italic uppercase text-center">No discovery modules registered in library</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="courseModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-none overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 id="modalTitle" class="text-2xl font-black text-white italic tracking-tighter mb-1">Nuevo Curso</h2>
                    <p id="modalSubtitle" class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Integración de módulo de aprendizaje</p>
                </div>
                <button onclick="document.getElementById('courseModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-none text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="courseForm" action="{{ route('dashboard.courses.store') }}" method="POST" class="space-y-6">
                @csrf
                <div id="methodField"></div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Nombre del Curso</label>
                        <input type="text" name="name" id="form_name" required placeholder="Ej: Mastering Laravel AI" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-none px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Enlace / URL del Recurso</label>
                        <input type="url" name="link" id="form_link" placeholder="https://..." 
                               class="w-full bg-slate-950 border border-slate-800 rounded-none px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all lowercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Estado Escalonado</label>
                        <select name="status" id="form_status" required class="w-full bg-slate-950 border border-slate-800 rounded-none px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                            <option value="pending">PENDING</option>
                            <option value="done">DONE</option>
                            <option value="postponed">POSTPONED</option>
                            <option value="archived">ARCHIVED</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-none shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                    Sincronizar Módulo
                </button>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            const form = document.getElementById('courseForm');
            form.action = "{{ route('dashboard.courses.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('modalTitle').innerText = 'Nuevo Curso';
            document.getElementById('modalSubtitle').innerText = 'Integración de módulo de aprendizaje';
            
            document.getElementById('form_name').value = '';
            document.getElementById('form_link').value = '';
            document.getElementById('form_status').value = 'pending';
            
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function openEditModal(course) {
            const form = document.getElementById('courseForm');
            form.action = `/dashboard/courses/${course.id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            document.getElementById('modalTitle').innerText = 'Editar Curso';
            document.getElementById('modalSubtitle').innerText = 'Actualizando: ' + course.name;
            
            document.getElementById('form_name').value = course.name;
            document.getElementById('form_link').value = course.link || '';
            document.getElementById('form_status').value = course.status || 'pending';
            
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function playVideo(id, ytId) {
            const container = document.getElementById(`thumb-${id}`);
            container.innerHTML = `
                <iframe 
                    src="https://www.youtube.com/embed/${ytId}?autoplay=1&rel=0&modestbranding=1" 
                    class="absolute inset-0 w-full h-full"
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            `;
        }
    </script>
</body>
</html>
