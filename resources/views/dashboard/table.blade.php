<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - {{ $bot_id }}</title>
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
        nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; }
        nav[role="navigation"] span, nav[role="navigation"] a {
            background-color: transparent !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        nav[role="navigation"] a:hover {
            color: #818cf8 !important;
            background-color: #1e293b !important;
        }
        nav[role="navigation"] .bg-white {
            background-color: #4f46e5 !important;
            color: white !important;
            border-color: #4f46e5 !important;
        }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-slate-800/50 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.bots') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <div class="flex items-center space-x-3">
                <span class="text-xl font-bold text-white">{{ str_replace('-', ' ', $bot_id) }}</span>
                <span class="text-slate-600">/</span>
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Interaction History</span>
            </div>
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
            
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20 font-mono font-bold">
                        H
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Transmission Logs</h1>
                        <p class="text-xs text-slate-500 font-medium font-mono">system.db.query(chat_history)</p>
                    </div>
                </div>
                <div class="bg-indigo-600/10 border border-indigo-500/20 px-4 py-2 rounded-xl">
                     <span class="text-indigo-400 text-xs font-black">TOTAL ENTRIES: {{ $chat_history->total() }}</span>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-800/40">
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800">Index</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800">Thread ID</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800">Role</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800 w-1/2">Content</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($chat_history as $row)
                            <tr class="hover:bg-indigo-600/[0.02] transition-colors group">
                                <td class="px-6 py-4 text-xs font-mono text-indigo-500">{{ $row->id }}</td>
                                <td class="px-6 py-4 text-[11px] text-slate-400 font-mono">{{ Str::limit($row->chat_id, 8, '') }}...</td>
                                <td class="px-6 py-4">
                                     <span class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest {{ $row->role == 'user' ? 'bg-blue-600/10 text-blue-400 border border-blue-600/20' : 'bg-indigo-600/10 text-indigo-400 border border-indigo-600/20' }}">
                                        {{ $row->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-300">
                                    <div class="max-w-xl truncate group-hover:whitespace-normal group-hover:overflow-visible transition-all">
                                        {{ $row->message }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[10px] text-slate-500 whitespace-nowrap font-medium">
                                    {{ $row->created_at->format('M d, H:i:s') }}
                                </td>
                            </tr>
                            @endforeach
                            @if($chat_history->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-600 text-[10px] font-black uppercase tracking-widest italic">Data stream empty</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Paginacion -->
                @if($chat_history->hasPages())
                <div class="px-6 py-5 bg-slate-900 border-t border-slate-800">
                    {{ $chat_history->links() }}
                </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                 <p class="text-[9px] font-black text-slate-700 uppercase tracking-[0.4em]">Integrated Telemetry Console</p>
            </div>

        </div>
    </main>

</body>
</html>
