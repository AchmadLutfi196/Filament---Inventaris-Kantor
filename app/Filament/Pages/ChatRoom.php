<?php

namespace App\Filament\Pages;

use App\Models\Chat as ChatModel;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Page
{
    protected static ?string $navigationIcon = null; // Hide from navigation
    
    protected static string $view = 'filament.pages.chat-room';
    
    protected static ?string $title = 'Chat Room';
    
    protected static bool $shouldRegisterNavigation = false; // Hide from navigation
    
    protected static string $routePath = '/chat-room/{chatId}';

    public $chatId;
    public $chat;    public function mount($chatId = null)
    {
        // Get chatId from query parameter if not passed directly
        $this->chatId = $chatId ?? request()->query('chatId');
          if ($this->chatId) {
            $this->chat = ChatModel::with(['users'])->find($this->chatId);
            
            // Verify user has access to this chat
            $user = Auth::user();
            if ($this->chat && !$this->chat->users->contains($user->id)) {
                // If admin, try to join the chat
                if ($user->role === 'admin') {
                    $this->chat->users()->syncWithoutDetaching([
                        $user->id => ['joined_at' => now()]
                    ]);
                } else {
                    // Regular user doesn't have access
                    return redirect()->route('filament.admin.pages.chat-list');
                }
            }
        }
        
        if (!$this->chat) {
            return redirect()->route('filament.admin.pages.chat-list');
        }
    }

    public function getChatTitle()
    {
        if (!$this->chat) {
            return 'Chat Room';
        }

        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $targetUser = $this->chat->users->where('role', 'user')->first();
            return $targetUser ? 'Chat dengan ' . $targetUser->name : 'Chat Room';
        } else {
            $admin = $this->chat->users->where('role', 'admin')->first();
            return $admin ? 'Chat dengan ' . $admin->name : 'Chat dengan Admin';
        }
    }

    public function getTitle(): string
    {
        return $this->getChatTitle();
    }    public function backToChatList()
    {
        return redirect()->route('filament.admin.pages.chat-list');
    }
}
