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
     * Show the main dashboard with bot cards.
     */
    public function index(Request $request)
    {
        $bots = [
            [
                'id' => 'josh-dev',
                'name' => 'josh dev',
                'description' => 'Main Assistant System',
                'icon' => 'JD',
                'count' => ChatHistory::count(),
            ]
        ];

        return view('dashboard.index', compact('bots'));
    }

    /**
     * Show the chat interface for a specific bot based on chat_id.
     */
    public function botHistory(Request $request, $botId, $chatId = null)
    {
        // Use chatId from URL or session or generate a new one
        if (!$chatId) {
            $chatId = $request->session()->get('current_chat_id');
        }

        if (!$chatId) {
            $chatId = (string) Str::uuid();
        }

        // Always sync the session with the current chatId
        $request->session()->put('current_chat_id', $chatId);

        // Fetch history from DB for the specific conversation
        $history = ChatHistory::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Fetch all conversations for the sidebar (grouped by chat_id)
        $threads = ChatHistory::where('username', Auth::user()->name)
            ->where('role', 'user')
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                    ->from('josh_dev_chat_history')
                    ->where('role', 'user')
                    ->groupBy('chat_id');
            })
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        return view('dashboard.chat', [
            'chat_history' => $history,
            'threads' => $threads,
            'bot_id' => $botId,
            'current_chat_id' => $chatId
        ]);
    }

    /**
     * Return latest notifications as JSON.
     */
    public function notifications()
    {
        $notifications = Notificacion::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($notifications);
    }

    /**
     * Send chat message to n8n.
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $chatId = $request->session()->get('current_chat_id');
        $username = Auth::user()->name;

        try {
            Http::post('https://n8n.srv1137974.hstgr.cloud/webhook-test/2b14440f-2a6b-4898-8cc7-5cb163b1ad2c', [
                'chat_id' => $chatId,
                'message' => $message,
                'user' => $username,
                'email' => Auth::user()->email
            ]);
        } catch (\Exception $e) {
            // Log error if needed
        }

        return redirect()->route('dashboard.history', ['bot' => $request->input('bot_id', 'josh-dev'), 'chatId' => $chatId]);
    }

    /**
     * Start a new chat thread.
     */
    public function newChat(Request $request)
    {
        $newChatId = (string) Str::uuid();
        $request->session()->put('current_chat_id', $newChatId);
        return redirect()->route('dashboard.history', ['bot' => $request->input('bot_id', 'josh-dev'), 'chatId' => $newChatId]);
    }
}
