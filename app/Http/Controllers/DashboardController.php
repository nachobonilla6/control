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
     * Simple chat handler – just echoes back the message for demo purposes.
     */
    public function chat(Request $request)
    {
        $message = $request->input('message');
        // In a real app you would store the message and generate a response.
        // Here we just return the same view with the message appended.
        $chatHistory = $request->session()->get('chat_history', []);
        $chatHistory[] = ['role' => 'user', 'content' => $message];
        $chatHistory[] = ['role' => 'assistant', 'content' => "Echo: $message"];
        $request->session()->put('chat_history', $chatHistory);
        return redirect()->route('dashboard');
    }
}
