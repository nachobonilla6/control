<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhooks - Control</title>
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
            <a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <span class="text-xl font-bold text-white uppercase tracking-tighter">Connection Hub</span>
        </div>
        <div class="flex items-center space-x-4">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors border border-slate-700">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-6 md:p-10">
        <div class="max-w-7xl mx-auto">

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-sm font-bold uppercase tracking-widest">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-6 py-4 rounded-2xl flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-sm font-bold uppercase tracking-widest">{{ session('error') }}</span>
            </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- Form Column -->
                <div class="lg:col-span-1">
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 sticky top-0 shadow-2xl">
                        <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-tight flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-xs mr-3">+</span>
                            New Webhook
                        </h2>
                        
                        <form action="{{ route('dashboard.webhooks.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Friendly Name</label>
                                <input type="text" name="name" required placeholder="e.g. n8n Production" 
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm text-slate-200">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Endpoint URL</label>
                                <input type="url" name="url" required placeholder="https://..." 
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm text-slate-200">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Payload (Text to Send)</label>
                                <textarea name="payload_text" rows="4" placeholder="Standard text or JSON structure..." 
                                          class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-sm text-slate-200 resize-none"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-600/20 transition-all active:scale-95">
                                Register Pipeline
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List Column -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-white uppercase tracking-tight">Active Pipelines</h2>
                        <span class="text-[10px] font-black text-indigo-500 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/20">
                            {{ count($webhooks) }} REGISTERED
                        </span>
                    </div>

                    <div class="space-y-6">
                        @foreach($webhooks as $webhook)
                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6 group hover:border-indigo-500/30 transition-all shadow-lg relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-600/5 blur-[40px] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-bold text-white">{{ $webhook->name }}</h3>
                                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[8px] font-black uppercase rounded border border-emerald-500/20">Operational</span>
                                    </div>
                                    <p class="text-xs font-mono text-slate-500 break-all mb-3">{{ $webhook->url }}</p>
                                    
                                    @if($webhook->payload_text)
                                    <div class="bg-slate-950/50 rounded-lg p-3 border border-slate-800/50">
                                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Payload Content</p>
                                        <p class="text-[11px] text-slate-400 italic line-clamp-2">"{{ $webhook->payload_text }}"</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2 shrink-0 self-end md:self-center">
                                    <!-- Trigger Webhook -->
                                    <form action="{{ route('dashboard.webhooks.trigger', $webhook->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Activate Webhook" class="p-2.5 rounded-lg bg-indigo-600/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-600 hover:text-white transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>

                                    <!-- Delete Webhook -->
                                    <form action="{{ route('dashboard.webhooks.destroy', $webhook->id) }}" method="POST" onsubmit="return confirm('Disconnect this pipeline?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove Pipeline" class="p-2.5 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all border border-red-500/20">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if($webhooks->isEmpty())
                        <div class="border-2 border-dashed border-slate-800 rounded-3xl p-20 flex flex-col items-center justify-center opacity-20">
                            <svg class="w-12 h-12 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Waitng for active links</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="mt-20 border-t border-slate-800/50 pt-8 flex items-center justify-center space-x-10 opacity-30">
                <span class="text-[9px] font-black uppercase tracking-[0.3em]">AES-256 Link</span>
                <span class="text-[9px] font-black uppercase tracking-[0.3em]">Realtime Sync</span>
                <span class="text-[9px] font-black uppercase tracking-[0.3em]">Orchestration v1.3</span>
            </div>
        </div>
    </main>

</body>
</html>
