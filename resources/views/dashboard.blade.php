<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat History - josh dev</title>
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
        /* Pagination overrides for dark mode */
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
    <nav class="h-20 bg-slate-950/50 backdrop-blur-md border-b border-slate-800/50 flex items-center justify-between px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-4">
            <!-- Card for Josh Dev Name -->
            <div class="bg-indigo-600/10 border border-indigo-500/20 px-4 py-2 rounded-xl shadow-lg shadow-indigo-500/5 transition-transform hover:scale-105 select-none">
                <span class="text-xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-indigo-600">
                    josh dev
                </span>
            </div>
            <span class="text-slate-700 font-light text-xl">/</span>
            <span class="text-sm font-medium text-slate-400 tracking-wide uppercase">Console History</span>
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
                <h1 class="text-3xl font-bold text-white">josh_dev_chat_history</h1>
                <div class="bg-indigo-500/10 border border-indigo-500/20 px-4 py-2 rounded-lg">
                    <span class="text-indigo-400 text-sm font-mono">Total: {{ $chat_history->total() }} | Page {{ $chat_history->currentPage() }} of {{ $chat_history->lastPage() }}</span>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-800/50">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800">ID</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800">Chat ID</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800">Username</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800">Role</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800">Message</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-800">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($chat_history as $row)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono text-indigo-400">{{ $row->id }}</td>
                                <td class="px-6 py-4 text-sm text-slate-400 font-mono">{{ Str::limit($row->chat_id, 13) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center text-[10px] uppercase">
                                            {{ substr($row->username ?? '?', 0, 1) }}
                                        </div>
                                        <span>{{ $row->username ?? 'NULL' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $row->role == 'user' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                        {{ $row->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-400 max-w-md">
                                    <div class="truncate italic" title="{{ $row->message }}">
                                        "{{ $row->message }}"
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                    {{ $row->created_at->format('Y-m-d H:i:s') }}
                                </td>
                            </tr>
                            @endforeach
                            @if(count($chat_history) == 0)
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-slate-500 italic">
                                    No data available in table
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Footer -->
                @if($chat_history->hasPages())
                <div class="bg-slate-800/30 px-6 py-4 border-t border-slate-800 flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $chat_history->links('pagination::simple-tailwind') }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-slate-500">
                                Showing <span class="font-medium text-slate-300">{{ $chat_history->firstItem() }}</span> to <span class="font-medium text-slate-300">{{ $chat_history->lastItem() }}</span> of <span class="font-medium text-slate-300">{{ $chat_history->total() }}</span> results
                            </p>
                        </div>
                        <div class="pagination-custom">
                            {{ $chat_history->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <p class="mt-6 text-center text-xs text-slate-600 uppercase tracking-widest font-semibold">
                Control System Data Viewer v1.1
            </p>
        </div>
    </main>

</body>
</html>
