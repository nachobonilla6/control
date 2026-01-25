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
        // Fetch history from DB for the table view with pagination
        $history = ChatHistory::orderBy('id', 'desc')->paginate(11);

        return view('dashboard', [
            'chat_history' => $history
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
     * Send chat message to n8n.
     * Persistence (saving to DB) is handled by n8n.
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $chatId = $request->session()->get('current_chat_id');
        $username = Auth::user()->name;

        try {
            // Send request to n8n webhook - n8n will be responsible for saving to josh_dev_chat_history
            Http::post('https://n8n.srv1137974.hstgr.cloud/webhook-test/2b14440f-2a6b-4898-8cc7-5cb163b1ad2c', [
                'chat_id' => $chatId,
                'message' => $message,
                'user' => $username,
                'email' => Auth::user()->email
            ]);
        } catch (\Exception $e) {
            // Silently fail or log, since n8n handles the logic
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
    /**
     * Switch to a specific chat thread.
     */
    public function showChat(Request $request, $chatId)
    {
        $request->session()->put('current_chat_id', $chatId);
        return redirect()->route('dashboard');
    }
}
