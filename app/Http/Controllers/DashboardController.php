<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\ChatHistory;
use App\Models\Webhook;
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
            [
                'id' => 'webhooks',
                'name' => 'Webhooks',
                'description' => 'Configure and link external automation endpoints.',
                'icon' => '🔗',
                'route' => 'dashboard.webhooks',
            ],
        ];

        return view('dashboard.index', compact('categories'));
    }

    /**
     * Webhooks Page: List and Create.
     */
    public function webhooksIndex()
    {
        $webhooks = Webhook::orderBy('created_at', 'desc')->get();
        return view('dashboard.webhooks', compact('webhooks'));
    }

    /**
     * Store new Webhook.
     */
    public function webhooksStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'payload_text' => 'nullable|string',
        ]);

        Webhook::create($request->only(['name', 'url', 'payload_text']));

        return redirect()->route('dashboard.webhooks')->with('success', 'Webhook registered successfully.');
    }

    /**
     * Delete Webhook.
     */
    public function webhooksDestroy($id)
    {
        Webhook::findOrFail($id)->delete();
        return redirect()->route('dashboard.webhooks')->with('success', 'Webhook deleted.');
    }

    /**
     * Trigger/Activate Webhook.
     */
    public function webhooksTrigger(Request $request, $id)
    {
        $webhook = Webhook::findOrFail($id);

        try {
            $payload = $webhook->payload_text;
            
            // Try to detect if it's JSON
            $jsonData = json_decode($payload, true);
            
            if (json_last_error() === JSON_ERROR_NONE && !is_numeric($payload)) {
                $response = Http::post($webhook->url, $jsonData);
            } else {
                $response = Http::post($webhook->url, ['text' => $payload]);
            }

            if ($response->successful()) {
                return redirect()->route('dashboard.webhooks')->with('success', "Webhook '{$webhook->name}' triggered successfully.");
            } else {
                return redirect()->route('dashboard.webhooks')->with('error', "Webhook failed with status: " . $response->status());
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard.webhooks')->with('error', "Connection error: " . $e->getMessage());
        }
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
        ];

        return view('dashboard.bots', compact('bots'));
    }

    /**
     * Show the detailed table (history) for a specific bot.
     */
    public function botHistory(Request $request, $botId)
    {
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
     * Chat endpoint redirection.
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

    /**
     * Update User Profile Photo.
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'profile_photo_url' => 'required|url'
        ]);

        $user = Auth::user();
        /** @var \App\Models\User $user */
        $user->profile_photo_url = $request->profile_photo_url;
        $user->save();

        return back()->with('success', 'Profile photo updated.');
    }
}
