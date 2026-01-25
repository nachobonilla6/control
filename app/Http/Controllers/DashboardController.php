<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\ChatHistory;
use App\Models\Webhook;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
            [
                'id' => 'projects',
                'name' => 'Projects',
                'description' => 'Showcase and manage your latest development works.',
                'icon' => '📂',
                'route' => 'dashboard.projects',
            ],
        ];

        return view('dashboard.index', compact('categories'));
    }

    /**
     * Projects Page: List and Create.
     */
    public function projectsIndex()
    {
        try {
            $projects = Project::orderBy('created_at', 'desc')->get();
            return view('dashboard.projects', compact('projects'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle missing table error (common on first deploy to Hostinger)
            $projects = [];
            $error_type = ($e->getCode() === '42S02') ? 'missing_table' : 'generic';
            return view('dashboard.projects', compact('projects', 'error_type'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store new Project.
     */
    public function projectsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array|max:7',
        ]);

        try {
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('projects', 'public');
                    // Store as /storage/projects/name.ext for easier web access
                    $imagePaths[] = Storage::url($path);
                }
            }

            Project::create([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'active' => $request->has('active'),
                'images' => $imagePaths
            ]);

            return redirect()->route('dashboard.projects')->with('success', 'Project registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete Project.
     */
    public function projectsDestroy($id)
    {
        $project = Project::findOrFail($id);
        
        // Delete images from storage
        if ($project->images) {
            foreach ($project->images as $url) {
                $path = str_replace('/storage/', '', $url);
                Storage::disk('public')->delete($path);
            }
        }

        $project->delete();
        return redirect()->route('dashboard.projects')->with('success', 'Project removed.');
    }

    /**
     * Update existing Project.
     */
    public function projectsUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array|max:7',
        ]);

        try {
            $project = Project::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'active' => $request->has('active'),
            ];

            if ($request->hasFile('images')) {
                // Delete old images
                if ($project->images) {
                    foreach ($project->images as $url) {
                        $path = str_replace('/storage/', '', $url);
                        Storage::disk('public')->delete($path);
                    }
                }

                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('projects', 'public');
                    $imagePaths[] = Storage::url($path);
                }
                $data['images'] = $imagePaths;
            }

            $project->update($data);

            return redirect()->route('dashboard.projects')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update error: ' . $e->getMessage()]);
        }
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
     * Update existing Webhook.
     */
    public function webhooksUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'payload_text' => 'nullable|string',
        ]);

        $webhook = Webhook::findOrFail($id);
        $webhook->update($request->only(['name', 'url', 'payload_text']));

        return redirect()->route('dashboard.webhooks')->with('success', 'Webhook updated successfully.');
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
        
        $data = $notifications->map(function($n) {
            return [
                'id' => $n->id,
                'titulo' => $n->titulo,
                'texto' => $n->texto,
                'fecha_format' => $n->created_at->format('M d, H:i')
            ];
        });

        return response()->json($data);
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
