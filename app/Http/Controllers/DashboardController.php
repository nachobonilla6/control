<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Main Dashboard: Shows category cards.
     */
    public function index(Request $request)
    {
        $categories = [
            [
                'id' => 'bots',
                'name' => 'AI Bots',
                'description' => 'Manage and create intelligent assistants.',
                'icon' => '🤖',
                'route' => 'dashboard.bots',
            ],
            // You can add more categories here later (e.g. Analytics, Users)
        ];

        return view('dashboard.index', compact('categories'));
    }

    /**
     * Bots Page: Shows a list of specific bots and create option.
     */
    public function botsIndex()
    {
        $bots = [
            [
                'id' => 'josh-dev',
                'name' => 'josh dev',
                'description' => 'Main Assistant System',
                'icon' => 'JD',
                'count' => ChatHistory::count(),
            ],
            // New bots will appear here
        ];

        return view('dashboard.bots', compact('bots'));
    }

    /**
     * Show the detailed table (history) for a specific bot.
     */
    public function botHistory(Request $request, $botId)
    {
        // Fetch history for the table view
        $history = ChatHistory::orderBy('id', 'desc')->paginate(11);

        return view('dashboard.table', [
            'chat_history' => $history,
            'bot_id' => $botId
        ]);
    }

    /**
     * Notifications JSON.
     */
    public function notifications()
    {
        $notifications = Notificacion::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($notifications);
    }

    /**
     * Keep chat method if needed by n8n or other interactions.
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $chatId = $request->session()->get('current_chat_id', (string) Str::uuid());
        
        try {
            Http::post('https://n8n.srv1137974.hstgr.cloud/webhook-test/2b14440f-2a6b-4898-8cc7-5cb163b1ad2c', [
                'chat_id' => $chatId,
                'message' => $message,
                'user' => Auth::user()->name,
                'email' => Auth::user()->email
            ]);
        } catch (\Exception $e) {}

        return back();
    }
}
