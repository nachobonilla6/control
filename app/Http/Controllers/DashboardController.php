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
     * Show the dashboard.
     */
    public function index(Request $request)
    {
        // Use a chat_id from session or generate a new one
        $chatId = $request->session()->get('current_chat_id');
        if (!$chatId) {
            $chatId = Str::uuid();
            $request->session()->put('current_chat_id', $chatId);
        }

        // Fetch history from DB
        $history = ChatHistory::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Also fetch threads/history for sidebar (distinct chat_ids)
        $threads = ChatHistory::where('username', Auth::user()->name)
            ->select('chat_id', 'message')
            ->where('role', 'user')
            ->groupBy('chat_id')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        return view('dashboard', [
            'chat_history' => $history,
            'threads' => $threads
        ]);
    }

    /**
     * Return latest notifications as JSON (for the modal).
     */
    public function notifications()
    {
        $notifications = Notificacion::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($notifications);
    }

    /**
     * Send chat message to n8n and store history in DB.
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $chatId = $request->session()->get('current_chat_id');
        $username = Auth::user()->name;

        // 1. Save User Message to DB
        ChatHistory::create([
            'chat_id' => $chatId,
            'username' => $username,
            'role' => 'user',
            'message' => $message
        ]);

        try {
            // 2. Send request to n8n webhook
            $response = Http::post('https://n8n.srv1137974.hstgr.cloud/webhook-test/2b14440f-2a6b-4898-8cc7-5cb163b1ad2c', [
                'chat_id' => $chatId,
                'message' => $message,
                'user' => $username,
                'email' => Auth::user()->email
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $n8nResponse = $responseData['output'] ?? $responseData['response'] ?? $responseData['message'] ?? 'Message received by n8n.';
                
                // 3. Save Assistant Message to DB
                ChatHistory::create([
                    'chat_id' => $chatId,
                    'username' => 'n8n_assistant',
                    'role' => 'assistant',
                    'message' => $n8nResponse
                ]);
            } else {
                ChatHistory::create([
                    'chat_id' => $chatId,
                    'role' => 'assistant',
                    'message' => "Error from n8n: " . $response->status()
                ]);
            }
        } catch (\Exception $e) {
            ChatHistory::create([
                'chat_id' => $chatId,
                'role' => 'assistant',
                'message' => "Connection Error: " . $e->getMessage()
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Start a new chat thread.
     */
    public function newChat(Request $request)
    {
        $request->session()->put('current_chat_id', (string) Str::uuid());
        return redirect()->route('dashboard');
    }
}
