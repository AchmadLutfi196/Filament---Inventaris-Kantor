<div class="h-full flex flex-col bg-gray-50 chat-container">
    @if($chat)
        <!-- Messages Container -->
        <div 
            class="flex-1 overflow-y-auto p-6 space-y-4 scroll-smooth"
            id="messages-container"
            wire:poll.3s="loadMessages"
            style="min-height: 400px; max-height: 500px;"
        >
            @forelse($messages as $message)
                <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }} group">
                    <div class="max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-2xl message-bubble relative">
                        <!-- Avatar for received messages -->
                        @if(!$message['is_mine'])
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br {{ $message['is_admin_message'] ? 'from-emerald-500 to-green-600' : 'from-blue-500 to-indigo-600' }} rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                    <span class="text-xs font-semibold text-white">
                                        {{ strtoupper(substr($message['user_name'], 0, 1)) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <!-- User Info -->
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $message['user_name'] }}
                                        </span>
                                        {{-- @if($message['is_admin_message'])
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                User
                                            </span>
                                        @endif --}}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $message['created_at'] }}
                                        </span>
                                    </div>
                                    
                                    <!-- Message Content -->
                                    <div class="bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 shadow-sm">
                                        @if($message['type'] === 'image')
                                            <img src="{{ Storage::url($message['file_path']) }}" alt="Image" class="max-w-full h-auto rounded-lg mb-2"/>
                                        @elseif($message['type'] === 'file')
                                            <div class="flex items-center space-x-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $message['file_name'] }}</p>
                                                    <a href="{{ Storage::url($message['file_path']) }}" download class="text-xs text-blue-500 hover:text-blue-700">Download</a>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($message['message'] !== 'File: ' . ($message['file_name'] ?? ''))
                                            <p class="text-gray-800 dark:text-gray-200 leading-relaxed">{{ $message['message'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Own message -->
                            <div class="flex items-end justify-end space-x-2">
                                <div class="text-right">
                                    <!-- Message Content -->
                                    <div class="inline-block bg-gradient-to-r {{ $currentUser->role === 'admin' ? 'from-emerald-500 to-green-600' : 'from-blue-500 to-indigo-600' }} text-white rounded-lg px-3 py-2 shadow-sm">
                                        @if($message['type'] === 'image')
                                            <img src="{{ Storage::url($message['file_path']) }}" alt="Image" class="max-w-full h-auto rounded-lg mb-2"/>
                                        @elseif($message['type'] === 'file')
                                            <div class="flex items-center space-x-2 p-3 bg-white/20 rounded-lg">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-white truncate">{{ $message['file_name'] }}</p>
                                                    <a href="{{ Storage::url($message['file_path']) }}" download class="text-xs text-blue-100 hover:text-white">Download</a>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($message['message'] !== 'File: ' . ($message['file_name'] ?? ''))
                                            <p class="leading-relaxed">{{ $message['message'] }}</p>
                                        @endif
                                    </div>
                                    
                                    <!-- Timestamp -->
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $message['created_at'] }}
                                        @if($currentUser->role === 'admin')
                                            <span class="inline-flex items-center ml-2">
                                                <span class="w-1 h-1 bg-emerald-500 rounded-full mr-1"></span>
                                                Admin
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="w-10 h-10 bg-gradient-to-br {{ $currentUser->role === 'admin' ? 'from-emerald-500 to-green-600' : 'from-blue-500 to-purple-600' }} rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                                    <span class="text-sm font-bold text-white">
                                        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="relative mx-auto w-24 h-24 mb-6">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full opacity-20 animate-pulse"></div>
                        <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-6 shadow-xl">
                            <svg class="w-12 h-12 text-white mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                        Mulai Percakapan Baru
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg">
                        Belum ada pesan. Ketik pesan pertama Anda di bawah!
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Enhanced File Upload Preview Area -->
        @if($file)
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border-t border-blue-200 dark:border-blue-700">
                <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-700 rounded-2xl border-2 border-blue-200 dark:border-blue-600 shadow-lg">
                    <div class="flex items-center space-x-4">
                        <!-- File Icon -->
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            @php
                                $extension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            @if($isImage)
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        
                        <!-- File Info -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $file->getClientOriginalName() }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ strtoupper($extension) }} • {{ number_format($file->getSize() / 1024, 1) }} KB
                            </p>
                        </div>
                        
                        <!-- File Status -->
                        <div class="flex items-center space-x-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1 rounded-full">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs font-bold">Siap dikirim</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button 
                            wire:click="sendFile"
                            class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200"
                        >
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                            </svg>
                            Kirim File
                        </button>
                        <button 
                            wire:click="$set('file', null)"
                            class="group inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200"
                        >
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Compact Input Area -->
        <div class="flex-shrink-0 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            @error('newMessage')
                <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium text-sm">{{ $message }}</span>
                    </div>
                </div>
            @enderror
            
            <form wire:submit.prevent="sendMessage" class="flex items-center space-x-3">
                <!-- File Upload Button -->
                <label class="cursor-pointer">
                    <input 
                        type="file" 
                        wire:model="file" 
                        class="hidden"
                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt"
                    >
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </label>

                <!-- Message Input -->
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model="newMessage"
                        wire:keydown.enter.prevent="sendMessage"
                        placeholder="Ketik pesan Anda..."
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                        autocomplete="off"
                    >
                </div>

                <!-- Send Button -->
                <button 
                    type="submit"
                    class="flex items-center justify-center w-10 h-10 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white rounded-lg transition-colors duration-200 disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <div wire:loading.remove wire:target="sendMessage">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                        </svg>
                    </div>
                    <div wire:loading wire:target="sendMessage">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </form>

            <!-- Simple Status -->
            <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>Tekan Enter untuk mengirim</span>
                <span class="flex items-center space-x-1">
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                    <span>Online</span>
                </span>
            </div>
        </div>
    @else
        <!-- Enhanced No Chat Available State -->
        <div class="flex items-center justify-center h-full relative overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-50 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-400/5 to-purple-400/5 animate-pulse"></div>
            </div>
            
            <div class="relative text-center z-10 p-8">
                <div class="relative mx-auto w-32 h-32 mb-8">
                    <!-- Animated chat bubble icon -->
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full opacity-20 animate-ping"></div>
                    <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-8 shadow-2xl transform hover:scale-110 transition-transform duration-300">
                        <svg class="w-16 h-16 text-white mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
                    Belum Ada Percakapan
                </h3>
                <p class="text-gray-600 dark:text-gray-300 text-lg mb-8 max-w-md mx-auto leading-relaxed">
                    Mulai percakapan baru dengan mengirim pesan pertama Anda
                </p>
                
                <!-- Decorative elements -->
                <div class="flex justify-center space-x-6 mb-8">
                    <div class="w-3 h-3 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                    <div class="w-3 h-3 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                    <div class="w-3 h-3 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                </div>
                
                <div class="inline-flex items-center space-x-2 bg-white dark:bg-gray-700 px-6 py-3 rounded-full shadow-lg border border-gray-200 dark:border-gray-600">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Siap untuk memulai chat</span>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    // Auto scroll to bottom when new message added
    Livewire.on('messageAdded', () => {
        setTimeout(() => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
                // Add a subtle flash animation to new messages
                const messages = container.querySelectorAll('.message-bubble');
                if (messages.length > 0) {
                    const lastMessage = messages[messages.length - 1];
                    lastMessage.classList.add('animate-pulse');
                    setTimeout(() => {
                        lastMessage.classList.remove('animate-pulse');
                    }, 1000);
                }
            }
        }, 100);
    });

    // Auto scroll to bottom when message received
    Livewire.on('messageReceived', () => {
        setTimeout(() => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
                // Show notification for new message
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300';
                notification.textContent = 'Pesan baru diterima';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.remove('translate-x-full', 'opacity-0');
                }, 100);
                
                setTimeout(() => {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }
        }, 100);
    });

    // Initial scroll to bottom
    setTimeout(() => {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }, 500);

    // Enhanced input focus animation
    const messageInput = document.querySelector('.message-input');
    if (messageInput) {
        messageInput.addEventListener('focus', () => {
            messageInput.parentElement.classList.add('ring-4', 'ring-blue-500/20');
        });
        
        messageInput.addEventListener('blur', () => {
            messageInput.parentElement.classList.remove('ring-4', 'ring-blue-500/20');
        });
    }

    // Add loading state for file upload
    Livewire.on('fileUploading', () => {
        const uploadButton = document.querySelector('.file-upload-button');
        if (uploadButton) {
            uploadButton.innerHTML = '<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';
        }
    });

    // Reset file upload button after upload
    Livewire.on('fileUploaded', () => {
        const uploadButton = document.querySelector('.file-upload-button');
        if (uploadButton) {
            uploadButton.innerHTML = '<svg class="w-6 h-6 group-hover:rotate-12 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/></svg>';
        }
    });
});
</script>

<style>
/* Container styling */
.chat-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Messages container */
#messages-container {
    flex: 1;
    overflow-y: auto;
}

/* Enhanced scrollbar styling */
#messages-container::-webkit-scrollbar {
    width: 6px;
}

#messages-container::-webkit-scrollbar-track {
    background: transparent;
}

#messages-container::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
    border-radius: 3px;
}

#messages-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #2563eb, #7c3aed);
}

/* Message bubble hover effects */
.message-bubble {
    transition: all 0.2s ease-in-out;
}

.message-bubble:hover {
    transform: translateY(-1px);
}

/* Smooth entrance animation for new messages */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-bubble {
    animation: fadeInUp 0.3s ease-out;
}
</style>
@endpush
