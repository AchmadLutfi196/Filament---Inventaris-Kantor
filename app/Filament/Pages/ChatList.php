<?php

namespace App\Filament\Pages;

use App\Models\Chat as ChatModel;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ChatList extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static string $view = 'filament.pages.chat-list';
    
    protected static ?string $navigationLabel = 'Chat Support';
    
    protected static ?string $title = 'Daftar Chat';
    
    protected static ?int $navigationSort = 4;    public function openChat($chatId)
    {
        return redirect()->route('filament.admin.pages.chat-room', ['chatId' => $chatId]);
    }

    public function startChat()
    {
        $user = Auth::user();        if ($user->role === 'user') {
            $chat = $user->getOrCreateChatWithAdmin();
            return redirect()->route('filament.admin.pages.chat-room', ['chatId' => $chat->id]);
        }
    }

    public function getChats()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return ChatModel::with(['users', 'latestMessage.user'])
                ->where('type', 'admin_user')
                ->orderBy('last_message_at', 'desc')
                ->get()
                ->map(function ($chat) use ($user) {
                    // Untuk admin, ambil user yang bukan admin (yaitu user biasa)
                    $targetUser = $chat->users->where('role', 'user')->first();
                    $lastMessage = $chat->latestMessage->first();
                    
                    return [
                        'id' => $chat->id,
                        'title' => $targetUser ? $targetUser->name : 'Unknown User',
                        'subtitle' => $targetUser ? $targetUser->email : '',
                        'last_message' => $lastMessage?->message ?? 'Belum ada pesan',
                        'last_message_time' => $chat->last_message_at?->format('d/m H:i'),
                        'unread_count' => $chat->unreadMessagesCount($user),
                        'other_user' => $targetUser,
                        'user_role' => $targetUser?->role ?? 'user'
                    ];
                });
        } else {
            $chat = $user->getOrCreateChatWithAdmin();
            $admin = $chat->users->where('role', 'admin')->first();
            $lastMessage = $chat->latestMessage->first();
            
            return collect([
                [
                    'id' => $chat->id,
                    'title' => $admin ? 'Chat dengan ' . $admin->name : 'Chat dengan Admin',
                    'subtitle' => $admin ? 'Administrator' : 'Support Team',
                    'last_message' => $lastMessage?->message ?? 'Belum ada pesan',
                    'last_message_time' => $chat->last_message_at?->format('d/m H:i'),
                    'unread_count' => $chat->unreadMessagesCount($user),
                    'other_user' => $admin,
                    'user_role' => 'admin'
                ]
            ]);
        }
    }

    public function getAllUsers()
    {
        if (Auth::user()->role !== 'admin') {
            return collect();
        }

        // Get users who don't have active chats with any admin
        $usersWithChats = ChatModel::where('type', 'admin_user')
            ->with('users')
            ->get()
            ->flatMap(function ($chat) {
                return $chat->users->where('role', 'user')->pluck('id');
            })
            ->unique();

        return User::where('role', 'user')
            ->where('is_active', true)
            ->whereNotIn('id', $usersWithChats)
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at->format('d/m/Y')
                ];
            });
    }

    public function getAvailableUsers()
    {
        return $this->getAllUsers();
    }

    public function createChatWithUser($userId)
    {
        if (Auth::user()->role !== 'admin') {
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }        $chat = $user->getOrCreateChatWithAdmin();
        
        return redirect()->route('filament.admin.pages.chat-room', ['chatId' => $chat->id]);
    }
}
