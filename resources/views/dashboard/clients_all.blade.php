<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Clients - Control</title>
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
    <div class="flex-1 overflow-y-auto">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">All <span class="text-indigo-500">Clients</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2">Complete client database</p>
                </div>
                <a href="{{ route('dashboard.clients') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all border border-slate-700 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back
                </a>
            </div>

            <!-- Clients Table -->
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-950">
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Name / Company</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Email</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Phone</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Status</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="px-8 py-6 text-[13px] text-white font-bold">{{ $client->name }}</td>
                                    <td class="px-8 py-6 text-[13px] text-slate-300">
                                        @if($client->email)
                                            <a href="mailto:{{ $client->email }}" class="text-indigo-400 hover:text-indigo-300">{{ $client->email }}</a>
                                        @else
                                            <span class="text-slate-500 italic">No email</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-[13px] text-slate-400">{{ $client->phone ?? '-' }}</td>
                                    <td class="px-8 py-6">
                                        @if(!$client->email)
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-slate-300">No Email</span>
                                        @elseif($client->status === 'sent')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white">Sent</span>
                                        @elseif($client->status === 'queued')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white">Queued</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-slate-300">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-[13px] text-slate-400">{{ $client->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-slate-500">
                                        <p class="text-sm font-medium">No clients found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-slate-800 bg-slate-900/50">
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
