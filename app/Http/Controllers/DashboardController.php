<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        // You could pass recent notifications here if you want server‑side rendering
        $notifications = Notificacion::orderBy('created_at', 'desc')->take(5)->get();
        return view('dashboard', compact('notifications'));
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
     * Send chat message to n8n and store history.
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $chatHistory = $request->session()->get('chat_history', []);
        
        // Add user message to history
        $chatHistory[] = ['role' => 'user', 'content' => $message];

        try {
            // Send request to n8n webhook
            $response = \Illuminate\Support\Facades\Http::post('https://n8n.srv1137974.hstgr.cloud/webhook-test/2b14440f-2a6b-4898-8cc7-5cb163b1ad2c', [
                'message' => $message,
                'user' => Auth::user()->name,
                'email' => Auth::user()->email,
                'history' => $chatHistory // Optional: send context
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                // We expect a "response" or "output" key from n8n. If not found, use a default fallback or the raw body.
                $n8nResponse = $responseData['output'] ?? $responseData['response'] ?? $responseData['message'] ?? 'Message received by n8n (no specific response provided).';
                $chatHistory[] = ['role' => 'assistant', 'content' => $n8nResponse];
            } else {
                $chatHistory[] = ['role' => 'assistant', 'content' => "Error from n8n: " . $response->status()];
            }
        } catch (\Exception $e) {
            $chatHistory[] = ['role' => 'assistant', 'content' => "Could not connect to n8n: " . $e->getMessage()];
        }

        $request->session()->put('chat_history', $chatHistory);
        return redirect()->route('dashboard');
    }
}
