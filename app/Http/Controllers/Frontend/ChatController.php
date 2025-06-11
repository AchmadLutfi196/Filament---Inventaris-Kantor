<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{    public function index()
    {
        $user = Auth::user();
        
        // Cari admin untuk chat
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            return redirect()->route('frontend.dashboard')->with('error', 'Admin tidak tersedia untuk saat ini.');
        }
        
        // Cari chat yang sudah ada antara user dan admin
        $chat = Chat::whereHas('users', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('users', function($query) use ($admin) {
            $query->where('user_id', $admin->id);
        })->where('type', 'admin_user')->first();
        
        if (!$chat) {
            // Buat chat baru
            $chat = Chat::create([
                'title' => 'Chat dengan ' . $admin->name,
                'type' => 'admin_user',
                'is_active' => true
            ]);
            
            // Attach users ke chat
            $chat->users()->attach([
                $user->id => ['joined_at' => now()],
                $admin->id => ['joined_at' => now()]
            ]);
        }
        
        return view('frontend.chat.index', [
            'chat' => $chat,
            'adminName' => $admin->name,
            'chatId' => $chat->id
        ]);
    }
}
