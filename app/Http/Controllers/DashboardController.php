<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\ChatHistory;
use App\Models\Webhook;
use App\Models\Project;
use App\Models\Client;
use App\Models\Course;
use App\Models\FacebookPost;
use App\Models\Setting;
use App\Models\FacebookAccount;
use App\Models\Template;
use App\Models\ClientStatus;
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
     * Show the Chat Interface for Copilot.
     */
    public function chatIndex(Request $request, $botId = 'josh-dev', $chatId = null)
    {
        if ($chatId) {
            $request->session()->put('current_chat_id', $chatId);
        }

        $currentChatId = $request->session()->get('current_chat_id');
        
        if (!$currentChatId) {
            $currentChatId = (string) Str::uuid();
            $request->session()->put('current_chat_id', $currentChatId);
        }

        $chatHistory = ChatHistory::where('chat_id', $currentChatId)->orderBy('created_at', 'asc')->get();
        
        // Fetch unique threads using the schema info (role='user' usually starts the thread)
        $threads = ChatHistory::where('role', 'user')
            ->select('chat_id', 'message', 'created_at')
            ->whereIn('id', function($query) {
                $query->selectRaw('MIN(id)')
                    ->from('josh_dev_chat_history')
                    ->where('role', 'user')
                    ->groupBy('chat_id');
            })
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        return view('dashboard.chat', [
            'bot_id' => $botId,
            'chat_history' => $chatHistory,
            'threads' => $threads,
            'current_chat_id' => $currentChatId
        ]);
    }

    /**
     * Reset Chat and start a new session.
     */
    public function chatNew(Request $request)
    {
        $request->session()->put('current_chat_id', (string) Str::uuid());
        return redirect()->route('dashboard.chat');
    }

    /**
     * Chat endpoint redirection.
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');
        $chatId = $request->session()->get('current_chat_id', (string) Str::uuid());
        
        try {
            $response = Http::post('https://n8n.srv1137974.hstgr.cloud/webhook/776f766b-f300-4727-a778-c3be64254f8f', [
                'chat_id' => $chatId,
                'message' => $message,
                'user' => Auth::user()->name,
                'email' => Auth::user()->email
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'content' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }

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
    public function clientsIndex(Request $request)
    {
        $filter = $request->query('filter');
        
        $totalClients = Client::count();
        $queuedCount = Client::where('status', 'queued')->count();
        $sentCount = Client::where('status', 'sent')->count();
        $noEmailCount = Client::whereNull('email')->orWhere('email', '')->count();
        
        $query = Client::query();
        if ($filter === 'no_email') {
            $query->whereNull('email')->orWhere('email', '');
        } else {
            $query->whereIn('status', ['queued', 'sent']);
        }
        $clients = $query->orderByRaw("FIELD(status, 'queued', 'sent') ASC")
            ->orderByRaw("CASE WHEN status = 'queued' THEN created_at END ASC")
            ->orderByRaw("CASE WHEN status = 'sent' THEN created_at END DESC")
            ->paginate(5);
        return view('dashboard.clients', compact('clients', 'totalClients', 'queuedCount', 'sentCount', 'noEmailCount', 'filter'));
    }

    /**
     * Show all Clients without filters.
     */
    public function clientsAll()
    {
        $clients = Client::orderBy('status', 'desc')->orderBy('created_at', 'desc')->paginate(5);
        $totalClients = Client::count();
        $queuedCount = Client::where('status', 'queued')->count();
        $sentCount = Client::where('status', 'sent')->count();
        return view('dashboard.clients_all', compact('clients', 'totalClients', 'queuedCount', 'sentCount'));
    }

    /**
     * Store new Client.
     */
    public function clientsStore(Request $request)
    {
        // Get all valid status names from database
        $validStatuses = ClientStatus::pluck('name')->toArray();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:100',
            'status' => 'required|string|in:' . implode(',', $validStatuses),
            'alpha' => 'nullable|boolean',
        ]);

        try {
            $data = $request->all();
            $data['alpha'] = $request->has('alpha') ? 1 : 0;
            Client::create($data);
            return redirect()->route('dashboard.clients.all')->with('success', 'Client successfully registered.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Registration error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update existing Client.
     */
    public function clientsUpdate(Request $request, $id)
    {
        // Get all valid status names from database
        $validStatuses = ClientStatus::pluck('name')->toArray();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:100',
            'status' => 'required|string|in:' . implode(',', $validStatuses),
            'alpha' => 'nullable|boolean',
        ]);

        try {
            $client = Client::findOrFail($id);
            $data = $request->all();
            $data['alpha'] = $request->has('alpha') ? 1 : 0;
            $client->update($data);
            return redirect()->route('dashboard.clients.all')->with('success', 'Client successfully updated.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update error: ' . $e->getMessage()]);
        }
    }

    /**
     * Quick Toggle Client Status.
     */
    public function clientsToggleStatus($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->status = ($client->status === 'queued') ? 'sent' : 'queued';
            $client->save();
            return back()->with('success', 'Status switched to ' . strtoupper($client->status));
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
     * Extract clients by language, country, city, and industry.
     */
    public function clientsExtract(Request $request)
    {
        $request->validate([
            'language' => 'required|string|in:english,spanish,french,portuguese',
            'country' => 'required|string',
            'city' => 'required|string',
            'industry' => 'nullable|string|max:100',
        ]);

        try {
            // This is where you would call your n8n webhook or external API
            // to extract clients based on the criteria
            
            $language = $request->input('language');
            $country = $request->input('country');
            $city = $request->input('city');
            $industry = $request->input('industry');

            // Send extraction request to n8n webhook
            $response = Http::post('https://n8n.srv1137974.hstgr.cloud/webhook/4a7d5a5b-20fd-4a4f-ba69-08ebcb0c715a', [
                'action' => 'extract_clients',
                'language' => $language,
                'country' => $country,
                'city' => $city,
                'industry' => $industry,
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
            ]);

            $result = json_decode($response->body(), true);

            return response()->json([
                'success' => true,
                'message' => 'Extraction initiated successfully',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Extraction error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Templates Page: List and Create.
     */
    public function templatesIndex()
    {
        $templates = Template::orderBy('created_at', 'desc')->get();
        return view('dashboard.templates', compact('templates'));
    }

    /**
     * AI Generation for Template.
     */
    public function templatesGenerate(Request $request)
    {
        $prompt = $request->input('prompt');
        
        try {
            $response = Http::post('https://n8n.srv1137974.hstgr.cloud/webhook/4a7d5a5b-20fd-4a4f-ba69-08ebcb0c715a', [
                'chat_id' => 'template-gen-' . Auth::id(),
                'message' => $prompt,
                'user' => Auth::user()->name,
                'email' => Auth::user()->email,
                'is_template_request' => true
            ]);

            // The response might be raw text or JSON depending on n8n config.
            // Let's assume n8n returns the generated content.
            $content = $response->body();
            $dataAry = json_decode($content, true);
            $finalSubject = '';
            $finalBody = '';

            // 1. Try to find the output content (n8n usually wraps in an array)
            $textToParse = $content;
            if (is_array($dataAry)) {
                if (isset($dataAry[0]['output'])) {
                    $textToParse = $dataAry[0]['output'];
                } elseif (isset($dataAry['output'])) {
                    $textToParse = $dataAry['output'];
                }
            }

            // 2. Extract JSON from within the text (handles markdown code blocks etc)
            if (preg_match('/\{.*\}/s', $textToParse, $matches)) {
                $jsonData = json_decode($matches[0], true);
                if (is_array($jsonData)) {
                    // Collect subject (case insensitive)
                    foreach ($jsonData as $key => $val) {
                        if (strtolower($key) === 'subject') $finalSubject = $val;
                        if (strtolower($key) === 'body') $finalBody = $val;
                    }
                }
            }

            // 3. If we found both, return them clean
            if ($finalSubject && $finalBody) {
                return response()->json([
                    'subject' => trim($finalSubject),
                    'body' => trim($finalBody)
                ]);
            }

            // Fallback: If parsing failed to find specific fields, but we have text
            return response()->json([
                'subject' => 'Generated Template: ' . Str::limit($prompt, 30),
                'body' => $textToParse ?: $content
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store new Template.
     */
    public function templatesStore(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            Template::create($request->all());
            return redirect()->route('dashboard.templates')->with('success', 'Template successfully registered.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Registration error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update existing Template.
     */
    public function templatesUpdate(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            $template = Template::findOrFail($id);
            $template->update($request->all());
            return redirect()->route('dashboard.templates')->with('success', 'Template successfully updated.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Update error: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete Template.
     */
    public function templatesDestroy($id)
    {
        try {
            Template::findOrFail($id)->delete();
            return redirect()->route('dashboard.templates')->with('success', 'Template removed.');
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
        $posts = FacebookPost::with('account')
            ->orderByRaw('COALESCE(post_at, created_at) DESC')
            ->get();
        $accounts = FacebookAccount::all();
        try {
            $webhookUrl = Setting::get('facebook_webhook_url', 'https://n8n.srv1137974.hstgr.cloud/webhook-test/76497ea0-bfd0-46fa-8ea3-6512ff450b55');
        } catch (\Exception $e) {
            $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/76497ea0-bfd0-46fa-8ea3-6512ff450b55';
        }
        return view('dashboard.facebook', compact('posts', 'webhookUrl', 'accounts'));
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
            'status' => 'nullable|string|in:scheduled,posted,cancelled',
            'facebook_account_id' => 'nullable|exists:facebook_accounts,id',
        ]);

        try {
            $data = $request->only(['content', 'post_at', 'status', 'facebook_account_id']);
            
            // Handle image uploads
            $webRoot = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
            $uploadPath = $webRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'facebook';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageUrls = [];
            for ($i = 1; $i <= 3; $i++) {
                $fieldName = 'image' . $i;
                if ($request->hasFile($fieldName)) {
                    $image = $request->file($fieldName);
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    $relativePath = 'uploads/facebook/' . $filename;
                    $data[$fieldName] = $relativePath;
                    $imageUrls[$fieldName] = url($relativePath);
                } else {
                    $imageUrls[$fieldName] = null;
                }
            }

            // Get account info if provided
            $account = null;
            if ($request->facebook_account_id) {
                $account = FacebookAccount::find($request->facebook_account_id);
            }

            // Send to n8n Webhook (N8N will create the post in DB)
            try {
                $webhookUrl = Setting::get('facebook_webhook_url', 'https://n8n.srv1137974.hstgr.cloud/webhook-test/76497ea0-bfd0-46fa-8ea3-6512ff450b55');
            } catch (\Exception $e) {
                $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/76497ea0-bfd0-46fa-8ea3-6512ff450b55';
            }
            
            $response = Http::post($webhookUrl, [
                'content' => $data['content'],
                'image1' => $imageUrls['image1'],
                'image2' => $imageUrls['image2'],
                'image3' => $imageUrls['image3'],
                'post_at' => $data['post_at'] ?? null,
                'status' => $data['status'] ?? 'scheduled',
                'facebook_account_id' => $data['facebook_account_id'] ?? null,
                'page_id' => $account ? $account->page_id : null,
                'access_token' => $account ? $account->access_token : null,
            ]);

            if ($response->successful()) {
                return redirect()->route('dashboard.facebook')->with('success', 'Post sent to pipeline successfully. N8N will handle the posting.');
            } else {
                return back()->withInput()->withErrors(['error' => 'Failed to send to webhook: ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error processing post: ' . $e->getMessage()]);
        }
    }

    /**
     * Update existing Facebook Post.
     */
    public function facebookUpdate(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'post_at' => 'nullable|date',
            'status' => 'required|string|in:scheduled,posted,cancelled',
            'facebook_account_id' => 'nullable|exists:facebook_accounts,id',
        ]);

        try {
            $post = FacebookPost::findOrFail($id);
            $data = $request->only(['content', 'post_at', 'status', 'facebook_account_id']);
            
            $webRoot = is_dir(base_path('public_html')) ? base_path('public_html') : public_path();
            $uploadPath = $webRoot . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'facebook';

            for ($i = 1; $i <= 3; $i++) {
                $fieldName = 'image' . $i;
                if ($request->hasFile($fieldName)) {
                    // Delete old image if exists
                    if ($post->$fieldName) {
                        $fullPath = $webRoot . DIRECTORY_SEPARATOR . $post->$fieldName;
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                    
                    $image = $request->file($fieldName);
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $filename);
                    $data[$fieldName] = 'uploads/facebook/' . $filename;
                }
            }

            $post->update($data);
            $post->refresh()->load('account');

            // Send to n8n Webhook on Update as well
            try {
                try {
                    $webhookUrl = Setting::get('facebook_webhook_url', 'https://n8n.srv1137974.hstgr.cloud/webhook-test/76497ea0-bfd0-46fa-8ea3-6512ff450b55');
                } catch (\Exception $e) {
                    $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/76497ea0-bfd0-46fa-8ea3-6512ff450b55';
                }
                Http::post($webhookUrl, [
                    'id' => $post->id,
                    'content' => $post->content,
                    'image1' => $post->image1 ? asset($post->image1) : null,
                    'image2' => $post->image2 ? asset($post->image2) : null,
                    'image3' => $post->image3 ? asset($post->image3) : null,
                    'post_at' => $post->post_at ? $post->post_at->toIso8601String() : null,
                    'created_at' => $post->created_at->toIso8601String(),
                    'page_id' => $post->account ? $post->account->page_id : null, 
                    'access_token' => $post->account ? $post->account->access_token : null, 
                    'updated_at' => now()->toIso8601String(),
                ]);
            } catch (\Exception $e) {
                // Ignore webhook errors
            }

            return redirect()->route('dashboard.facebook')->with('success', 'Post updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error updating post: ' . $e->getMessage()]);
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

    /**
     * Update Facebook settings.
     */
    public function facebookSettingsUpdate(Request $request)
    {
        $request->validate([
            'facebook_webhook_url' => 'required|url',
        ]);

        Setting::set('facebook_webhook_url', $request->facebook_webhook_url);

        return back()->with('success', 'Facebook settings updated successfully.');
    }

    /**
     * Facebook Accounts – List all accounts.
     */
    public function facebookAccountsIndex()
    {
        $accounts = FacebookAccount::orderBy('created_at', 'desc')->get();
        return view('dashboard.facebook_accounts', compact('accounts'));
    }

    /**
     * Store new Facebook Account.
     */
    public function facebookAccountsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url',
            'page_id' => 'required|string|max:100',
            'access_token' => 'required|string',
        ]);

        try {
            FacebookAccount::create($request->all());
            return redirect()->route('dashboard.facebook.accounts')->with('success', 'Facebook account registered successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error saving account: ' . $e->getMessage()]);
        }
    }

    /**
     * Update existing Facebook Account.
     */
    public function facebookAccountsUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url',
            'page_id' => 'required|string|max:100',
            'access_token' => 'required|string',
        ]);

        try {
            $account = FacebookAccount::findOrFail($id);
            $account->update($request->all());
            return redirect()->route('dashboard.facebook.accounts')->with('success', 'Facebook account updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Error updating account: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete Facebook Account.
     */
    public function facebookAccountsDestroy($id)
    {
        try {
            FacebookAccount::findOrFail($id)->delete();
            return redirect()->route('dashboard.facebook.accounts')->with('success', 'Account removed.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error deleting account: ' . $e->getMessage()]);
        }
    }

    /**
     * Get all templates for email selector
     */
    public function clientsTemplates()
    {
        $templates = \App\Models\Template::all(['id', 'name', 'subject', 'body'])->toArray();
        return response()->json($templates);
    }

    /**
     * Get all client statuses
     */
    public function clientsStatuses()
    {
        $statuses = ClientStatus::all(['id', 'name', 'label', 'color'])->toArray();
        return response()->json($statuses);
    }
}
