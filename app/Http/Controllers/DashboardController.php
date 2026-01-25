<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\ChatHistory;
use App\Models\Webhook;
use App\Models\Project;
use App\Models\Client;
use App\Models\Course;
use App\Models\FacebookPost;
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
            [
                'id' => 'clients',
                'name' => 'Clients',
                'description' => 'Manage your business relationships and contacts.',
                'icon' => '👥',
                'route' => 'dashboard.clients',
            ],
            [
                'id' => 'courses',
                'name' => 'Courses',
                'description' => 'Track your learning journey and academic goals.',
                'icon' => '🎓',
                'route' => 'dashboard.courses',
            ],
            [
                'id' => 'facebook',
                'name' => 'Facebook',
                'description' => 'Manage and automate your Facebook posts and interactions.',
                'icon' => '📱',
                'route' => 'dashboard.facebook',
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
            'video_url' => 'nullable|string|max:255',
            'integrations' => 'nullable|string',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array|max:7',
        ]);

        try {
            $imagePaths = [];
            if ($request->hasFile('images')) {
                // ... (detect root logic)
                $webRoot = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
                $uploadPath = $webRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'projects';
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    $imagePaths[] = 'uploads/projects/' . $filename;
                }
            }

            Project::create([
                'name' => $request->name,
                'type' => $request->type,
                'video_url' => $request->video_url,
                'integrations' => $request->integrations ? array_map('trim', explode(',', $request->integrations)) : [],
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
        
        // Delete images from folder
        if ($project->images) {
            $imgs = is_array($project->images) ? $project->images : json_decode($project->images, true);
            if (is_array($imgs)) {
                $webRoot = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
                foreach ($imgs as $path) {
                    $fullPath = $webRoot . DIRECTORY_SEPARATOR . $path;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
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
            'video_url' => 'nullable|string|max:255',
            'integrations' => 'nullable|string',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images' => 'nullable|array|max:7',
        ]);

        try {
            $project = Project::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'type' => $request->type,
                'video_url' => $request->video_url,
                'integrations' => $request->integrations ? array_map('trim', explode(',', $request->integrations)) : [],
                'description' => $request->description,
                'active' => $request->has('active'),
            ];

            if ($request->hasFile('images')) {
                // Delete old images
                if ($project->images) {
                    $oldImgs = is_array($project->images) ? $project->images : json_decode($project->images, true);
                    if (is_array($oldImgs)) {
                        $webRoot = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
                        foreach ($oldImgs as $path) {
                            $fullPath = $webRoot . DIRECTORY_SEPARATOR . $path;
                            if (file_exists($fullPath)) {
                                unlink($fullPath);
                            }
                        }
                    }
                }

                $imagePaths = [];
                $uploadPath = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
                $uploadPath .= DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'projects';
                
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    $imagePaths[] = 'uploads/projects/' . $filename;
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
     * Show single project - Blog Style
     */
    public function projectsShow($id)
    {
        $project = Project::findOrFail($id);
        $project->imgs = is_array($project->images) ? $project->images : json_decode($project->images, true);
        if (!is_array($project->imgs)) $project->imgs = [];
        
        return view('projects.show', compact('project'));
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

    /**
     * Clients Page: List.
     */
    public function clientsIndex()
    {
        $totalClients = Client::count();
        $extractedCount = Client::where('status', 'extracted')->count();
        $sentCount = Client::where('status', 'sent')->count();
        $clients = Client::orderByRaw("FIELD(status, 'extracted', 'sent') ASC")
            ->orderBy('created_at', 'asc')
            ->paginate(5);
        
        return view('dashboard.clients', compact('clients', 'totalClients', 'extractedCount', 'sentCount'));
    }

    /**
     * Store new Client.
     */
    public function clientsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:100',
            'status' => 'required|string|in:extracted,sent',
        ]);

        try {
            Client::create($request->all());
            return redirect()->route('dashboard.clients')->with('success', 'Cliente registrado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    /**
     * Update existing Client.
     */
    public function clientsUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:100',
            'status' => 'required|string|in:extracted,sent',
        ]);

        try {
            $client = Client::findOrFail($id);
            $client->update($request->all());
            return redirect()->route('dashboard.clients')->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    /**
     * Quick Toggle Client Status.
     */
    public function clientsToggleStatus($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->status = ($client->status === 'extracted') ? 'sent' : 'extracted';
            $client->save();
            return back()->with('success', 'Status actualizado a ' . strtoupper($client->status));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * AI Parsing of raw text to client data.
     */
    public function clientsParse(Request $request)
    {
        $text = $request->input('text');
        
        $data = [
            'name' => '',
            'email' => '',
            'phone' => '',
            'location' => '',
            'industry' => '',
        ];

        // 1. Extract Email
        if (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}/i', $text, $matches)) {
            $data['email'] = $matches[0];
        }

        // 2. Extract Phone
        if (preg_match('/(?:\+|00)?([0-9]{1,4})[- .]?([0-9]{3,4}[- .]?[0-9]{3,4}[- .]?[0-9]{0,4})/', $text, $matches)) {
            $data['phone'] = $matches[0];
            $countryCode = $matches[1];

            // 3. Location from Phone (Country Codes)
            $countries = [
                '506' => 'Costa Rica', '1' => 'USA/Canada', '52' => 'México', '34' => 'España',
                '57' => 'Colombia', '54' => 'Argentina', '56' => 'Chile', '507' => 'Panamá',
                '502' => 'Guatemala', '503' => 'El Salvador', '504' => 'Honduras', '505' => 'Nicaragua',
            ];
            if (empty($data['location']) && isset($countries[$countryCode])) {
                $data['location'] = $countries[$countryCode];
            }
        }

        // 4. Industry from Context (Keyword Search)
        $industries = [
            'Real Estate' => ['bienes raices', 'house', 'apartment', 'realty', 'inmobiliaria', 'propiedad'],
            'Tech' => ['software', 'ai', 'desarrollo', 'web', 'tecnologia', 'it', 'digital'],
            'Automotive' => ['car', 'motor', 'auto', 'taller', 'repuestos', 'vehiculo'],
            'E-commerce' => ['tienda', 'shop', 'venta', 'retail', 'comercio'],
            'Health' => ['medico', 'salud', 'clinica', 'doctor', 'hospital'],
        ];

        $lowerText = strtolower($text);
        foreach ($industries as $name => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($lowerText, $kw)) {
                    $data['industry'] = $name;
                    break 2;
                }
            }
        }

        // 5. Lines Processing (Explicit mapping)
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            if (strpos($line, '@') === false && strlen($line) < 40 && empty($data['name'])) {
                $data['name'] = $line;
            }
            
            if (preg_match('/(?:Location|Ubicación|Ciudad|City|Pais|Country):\s*(.*)/i', $line, $matches)) {
                $data['location'] = trim($matches[1]);
            }
            if (preg_match('/(?:Industry|Sector|Industria):\s*(.*)/i', $line, $matches)) {
                $data['industry'] = trim($matches[1]);
            }
        }

        // 6. Name from Email (Fallback)
        if (empty($data['name']) && !empty($data['email'])) {
            $parts = explode('@', $data['email']);
            $namePart = $parts[0];
            $namePart = str_replace(['.', '_', '-'], ' ', $namePart);
            $data['name'] = ucwords($namePart);
        }

        return response()->json($data);
    }

    /**
     * Delete Client.
     */
    public function clientsDestroy($id)
    {
        try {
            Client::findOrFail($id)->delete();
            return redirect()->route('dashboard.clients')->with('success', 'Cliente eliminado del sistema.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }
    /**
     * Courses Page: List.
     */
    public function coursesIndex()
    {
        $courses = Course::orderByRaw("FIELD(status, 'pending', 'done', 'postponed', 'archived') ASC")
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.courses', compact('courses'));
    }

    /**
     * Store new Course.
     */
    public function coursesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:255',
            'status' => 'required|string|in:pending,done,postponed,archived',
        ]);

        try {
            Course::create($request->all());
            return redirect()->route('dashboard.courses')->with('success', 'Curso registrado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    /**
     * Update existing Course.
     */
    public function coursesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:255',
            'status' => 'required|string|in:pending,done,postponed,archived',
        ]);

        try {
            $course = Course::findOrFail($id);
            $course->update($request->all());
            return redirect()->route('dashboard.courses')->with('success', 'Curso actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    /**
     * Quick Toggle Course Status (Cycling).
     */
    public function coursesToggleStatus(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);
            
            if ($request->has('status')) {
                $request->validate(['status' => 'required|string|in:pending,done,postponed,archived']);
                $course->status = $request->status;
            } else {
                $cycle = [
                    'pending' => 'postponed',
                    'postponed' => 'done',
                    'done' => 'archived',
                    'archived' => 'pending'
                ];
                $course->status = $cycle[$course->status] ?? 'pending';
            }
            
            $course->save();
            return back()->with('success', 'Estatus: ' . strtoupper($course->status));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * AI/Auto-Parsing for Course Links (Metadata extraction).
     */
    public function coursesParse(Request $request)
    {
        $link = $request->input('link');
        $title = '';

        try {
            // Check if it's YouTube for better extraction
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $match)) {
                $id = $match[1];
                $response = Http::get("https://www.youtube.com/watch?v={$id}");
                if ($response->successful()) {
                    if (preg_match('/<title>(.*?) - YouTube<\/title>/', $response->body(), $matches)) {
                        $title = html_entity_decode($matches[1]);
                    } elseif (preg_match('/<title>(.*?)<\/title>/', $response->body(), $matches)) {
                        $title = html_entity_decode($matches[1]);
                    }
                }
            } else {
                // General website title extraction
                $response = Http::get($link);
                if ($response->successful()) {
                    if (preg_match('/<title>(.*?)<\/title>/', $response->body(), $matches)) {
                        $title = html_entity_decode($matches[1]);
                    }
                }
            }
            
            return response()->json([
                'name' => $title ?: 'Nuevo Curso de ' . parse_url($link, PHP_URL_HOST)
            ]);
        } catch (\Exception $e) {
            return response()->json(['name' => '']);
        }
    }

    /**
     * Delete Course.
     */
    public function coursesDestroy($id)
    {
        try {
            Course::findOrFail($id)->delete();
            return redirect()->route('dashboard.courses')->with('success', 'Curso eliminado del sistema.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Facebook Page - List posts.
     */
    public function facebookIndex()
    {
        $posts = FacebookPost::orderBy('created_at', 'desc')->get();
        return view('dashboard.facebook', compact('posts'));
    }

    /**
     * Store new Facebook Post.
     */
    public function facebookStore(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'post_at' => 'nullable|date',
        ]);

        try {
            $data = $request->only(['content', 'post_at']);
            
            // Handle image uploads
            $webRoot = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
            $uploadPath = $webRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'facebook';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            for ($i = 1; $i <= 3; $i++) {
                $fieldName = 'image' . $i;
                if ($request->hasFile($fieldName)) {
                    $image = $request->file($fieldName);
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    $data[$fieldName] = 'uploads/facebook/' . $filename;
                }
            }

            FacebookPost::create($data);
            return redirect()->route('dashboard.facebook')->with('success', 'Facebook post scheduled successfully with uploads.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error saving post: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete Facebook Post.
     */
    public function facebookDestroy($id)
    {
        try {
            $post = FacebookPost::findOrFail($id);
            
            // Delete images from disk
            $webRoot = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
            for ($i = 1; $i <= 3; $i++) {
                $fieldName = 'image' . $i;
                if ($post->$fieldName) {
                    $fullPath = $webRoot . DIRECTORY_SEPARATOR . $post->$fieldName;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }

            $post->delete();
            return redirect()->route('dashboard.facebook')->with('success', 'Post and associated images removed.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error deleting post: ' . $e->getMessage()]);
        }
    }
}
