<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Control Panel</title>
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
            <span class="text-xs font-black text-indigo-400 tracking-[0.2em]">Clients Database</span>
        </div>
        <div class="flex items-center space-x-4">
             <button id="accountBtn" class="w-10 h-10 bg-slate-800 rounded-full border border-slate-700 flex items-center justify-center overflow-hidden hover:border-indigo-500/50 transition-all">
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
            <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center shadow-lg animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black tracking-widest">{{ session('success') }}</span>
            </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">Gestión de <span class="text-indigo-500">Clientes</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2">Base de datos de relaciones comerciales</p>
                </div>
                <button onclick="openCreateModal()" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-indigo-600/20 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Nuevo Cliente
                </button>
            </div>

            <!-- Clients Table -->
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-950">
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Nombre / Empresa</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Contacto</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Ubicación</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Industria</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Status</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em] text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($clients as $client)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 font-black text-xs border border-indigo-500/10">
                                            {{ substr($client->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white leading-none mb-1">{{ $client->name }}</p>
                                            <p class="text-[9px] font-medium text-slate-500 lowercase">{{ $client->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-slate-400">{{ $client->phone ?: '---' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-slate-400">{{ $client->location ?: '---' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    @if($client->industry)
                                    <span class="px-3 py-1 bg-slate-950 border border-white/5 rounded-lg text-[9px] font-black text-slate-500">{{ $client->industry }}</span>
                                    @else
                                    <span class="text-slate-800 italic text-[10px]">Sin tag</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    @if($client->status === 'sent')
                                        <span class="flex items-center text-[9px] font-black text-emerald-400 tracking-widest">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                            SENT
                                        </span>
                                    @else
                                        <span class="flex items-center text-[9px] font-black text-indigo-400 tracking-widest">
                                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></span>
                                            EXTRACTED
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button 
                                            onclick="openEditModal({{ json_encode($client) }})"
                                            class="w-9 h-9 flex items-center justify-center bg-indigo-600/10 text-indigo-400 rounded-xl hover:bg-indigo-600 hover:text-white transition-all border border-indigo-500/10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                        <form action="{{ route('dashboard.clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('¿Eliminar cliente del sistema?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all border border-red-500/10">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <p class="text-xs font-black text-slate-700 tracking-[0.3em] italic">No active client records found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="clientModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 id="modalTitle" class="text-2xl font-black text-white italic tracking-tighter mb-1">Nuevo Registro</h2>
                    <p id="modalSubtitle" class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Alta de entidad comercial</p>
                </div>
                <button onclick="document.getElementById('clientModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="clientForm" action="{{ route('dashboard.clients.store') }}" method="POST" class="space-y-6">
                @csrf
                <div id="methodField"></div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Nombre Completo / Empresa</label>
                        <input type="text" name="name" id="form_name" required placeholder="Ej: Tech Solutions S.A." 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all uppercase">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Email de Contacto</label>
                        <input type="email" name="email" id="form_email" required placeholder="client@example.com" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all lowercase">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Ubicación</label>
                            <input type="text" name="location" id="form_location" placeholder="Ciudad, País" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all uppercase">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Teléfono</label>
                            <input type="text" name="phone" id="form_phone" placeholder="+00 0000-0000" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Industria / Sector</label>
                            <input type="text" name="industry" id="form_industry" placeholder="Ej: Automotriz" 
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white transition-all uppercase">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-indigo-400 tracking-widest mb-2">Status Operativo</label>
                            <select name="status" id="form_status" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="extracted">EXTRACTED</option>
                                <option value="sent">SENT</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-5 rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                    Procesar Registro
                </button>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            const form = document.getElementById('clientForm');
            form.action = "{{ route('dashboard.clients.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('modalTitle').innerText = 'Nuevo Registro';
            document.getElementById('modalSubtitle').innerText = 'Alta de entidad comercial';
            
            document.getElementById('form_name').value = '';
            document.getElementById('form_email').value = '';
            document.getElementById('form_location').value = '';
            document.getElementById('form_phone').value = '';
            document.getElementById('form_industry').value = '';
            document.getElementById('form_status').value = 'extracted';
            
            document.getElementById('clientModal').classList.remove('hidden');
        }

        function openEditModal(client) {
            const form = document.getElementById('clientForm');
            form.action = `/dashboard/clients/${client.id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            document.getElementById('modalTitle').innerText = 'Editar Cliente';
            document.getElementById('modalSubtitle').innerText = 'Actualizando: ' + client.name;
            
            document.getElementById('form_name').value = client.name;
            document.getElementById('form_email').value = client.email;
            document.getElementById('form_location').value = client.location || '';
            document.getElementById('form_phone').value = client.phone || '';
            document.getElementById('form_industry').value = client.industry || '';
            document.getElementById('form_status').value = client.status || 'extracted';
            
            document.getElementById('clientModal').classList.remove('hidden');
        }
    </script>
</body>
</html>
