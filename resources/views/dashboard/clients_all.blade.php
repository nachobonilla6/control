@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-4xl font-black text-white italic tracking-tighter mb-8">Todos los Clientes</h1>
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950">
                        <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Name / Company</th>
                        <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Email</th>
                        <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr class="border-b border-slate-800">
                            <td class="px-8 py-6 text-[13px] text-white font-bold">{{ $client->name }}</td>
                            <td class="px-8 py-6 text-[13px] text-slate-300">{{ $client->email }}</td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $client->status === 'sent' ? 'bg-emerald-600 text-white' : ($client->status === 'queued' ? 'bg-amber-500 text-white' : 'bg-slate-700 text-slate-300') }}">
                                    {{ $client->status ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-[13px] text-slate-400">{{ $client->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $clients->links() }}
        </div>
    </div>
</div>
@endsection
