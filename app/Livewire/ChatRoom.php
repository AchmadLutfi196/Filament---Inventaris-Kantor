<?php

namespace App\Livewire;

use App\Events\NewChatMessage;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatRoom extends Component
{
    use WithFileUploads;

    public $chatId;
    public $chat;
    public $messages = [];
    public $newMessage = '';
    public $file;
    public $isTyping = false;
    public $onlineUsers = [];
    public $isFrontend = false;

    protected $rules = [
        'newMessage' => 'required|string|max:1000',
        'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt'
    ];

    public function mount($chatId = null, $isFrontend = false)
    {
        $this->isFrontend = $isFrontend;
        $user = Auth::user();
        
        if ($chatId) {
            $this->chat = Chat::find($chatId);
            
            // Verify user has access to this chat
            if ($this->chat && !$this->chat->users->contains($user->id)) {
                // If admin, try to join the chat
                if ($user->role === 'admin') {
                    $this->chat->users()->syncWithoutDetaching([
                        $user->id => ['joined_at' => now()]
                    ]);
                } else {
                    // Regular user doesn't have access
                    $this->chat = null;
                }
            }
        } else {
            // For regular users, get or create chat with admin
            if ($user->role === 'user') {
                $this->chat = $user->getOrCreateChatWithAdmin();
            } else {
                // For admin, get the latest chat or first available
                $this->chat = Chat::whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->latest('last_message_at')->first();
            }
        }

        if ($this->chat) {
            $this->chatId = $this->chat->id;
            $this->loadMessages();
            $this->markChatAsRead();
        }
    }

    public function loadMessages()
    {
        if (!$this->chat) return;

        $currentUser = Auth::user();

        $this->messages = $this->chat->messages()
            ->with('user')
            ->get()
            ->map(function ($message) use ($currentUser) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'type' => $message->type,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'user_role' => $message->user->role,
                    'created_at' => $message->created_at->format('H:i'),
                    'created_at_full' => $message->created_at->format('d/m/Y H:i'),
                    'file_path' => $message->file_path,
                    'file_name' => $message->file_name,
                    'is_mine' => $message->user_id === $currentUser->id,
                    'is_admin_message' => $message->user->role === 'admin',
                    'bubble_class' => $this->getBubbleClass($message, $currentUser)
                ];
            })->toArray();
    }

    private function getBubbleClass($message, $currentUser)
    {
        if ($message->user_id === $currentUser->id) {
            // My message (current user sending)
            return $currentUser->role === 'admin' 
                ? 'bg-green-600 text-white rounded-l-lg rounded-tr-lg shadow-md' 
                : 'bg-blue-600 text-white rounded-l-lg rounded-tr-lg shadow-md';
        } else {
            // Received message
            if ($message->user->role === 'admin') {
                return 'bg-green-600 text-white rounded-r-lg rounded-tl-lg shadow-md';
            } else {
                return 'bg-white text-gray-900 rounded-r-lg rounded-tl-lg shadow-md border border-gray-200';
            }
        }
    }

    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string|max:1000']);

        if (!$this->chat) {
            $this->addError('newMessage', 'Chat tidak tersedia');
            return;
        }

        try {
            $message = ChatMessage::create([
                'chat_id' => $this->chat->id,
                'user_id' => Auth::id(),
                'message' => $this->newMessage,
                'type' => 'text'
            ]);

            // Update chat last_message_at
            $this->chat->update([
                'last_message_at' => now()
            ]);

            // Broadcast the new message
            broadcast(new NewChatMessage($message))->toOthers();

            // Reset input and reload
            $this->reset('newMessage');
            $this->loadMessages();
            $this->dispatch('messageAdded');
            $this->dispatch('clearInput'); // Additional event for input clearing
            
        } catch (\Exception $e) {
            $this->addError('newMessage', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    public function sendFile()
    {
        $this->validate(['file' => 'required|file|max:10240']);

        if (!$this->chat || !$this->file) return;

        $filename = time() . '_' . $this->file->getClientOriginalName();
        $path = $this->file->storeAs('chat-files', $filename, 'public');

        $fileType = in_array($this->file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']) ? 'image' : 'file';

        $message = ChatMessage::create([
            'chat_id' => $this->chat->id,
            'user_id' => Auth::id(),
            'message' => 'File: ' . $this->file->getClientOriginalName(),
            'type' => $fileType,
            'file_path' => $path,
            'file_name' => $this->file->getClientOriginalName()
        ]);

        // Update chat last_message_at
        $this->chat->update([
            'last_message_at' => now()
        ]);

        // Broadcast the new message
        broadcast(new NewChatMessage($message))->toOthers();

        $this->file = null;
        $this->loadMessages();
        $this->dispatch('messageAdded');
    }

    #[On('echo-private:chat.{chatId},new-message')]
    public function onNewMessage($data)
    {
        // Only reload if message is not from current user
        if ($data['user']['id'] !== Auth::id()) {
            $this->loadMessages();
            $this->dispatch('messageReceived');
        }
    }

    public function markChatAsRead()
    {
        if ($this->chat) {
            $this->chat->markAsRead(Auth::user());
        }
    }

    public function updatedNewMessage()
    {
        // You can implement typing indicators here
        $this->isTyping = !empty($this->newMessage);
    }

    public function render()
    {
        return view('livewire.chat-room', [
            'currentUser' => Auth::user()
        ]);
    }
}
