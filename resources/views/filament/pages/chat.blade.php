<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[600px]">
        <!-- Chat List -->
        <div class="lg:col-span-1 bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/>
                    </svg>
                    {{ auth()->user()->role === 'admin' ? 'Daftar Chat' : 'Chat Anda' }}
                </h3>
            </div>
            
            <div class="overflow-y-auto h-full">
                @php
                    $chats = $this->getChats();
                @endphp
                
                @forelse($chats as $chat)
                    <div 
                        class="p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer chat-item
                               {{ $selectedChatId == $chat['id'] ? 'bg-blue-50 dark:bg-blue-900/50 border-l-4 border-blue-500 dark:border-blue-400' : '' }}"
                        wire:click="selectChat({{ $chat['id'] }})"
                        data-chat-id="{{ $chat['id'] }}"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $chat['title'] }}
                                        </p>
                                        @if(isset($chat['user_role']) && $chat['user_role'] === 'admin')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Admin
                                            </span>
                                        @elseif(isset($chat['user_role']) && $chat['user_role'] === 'user')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                                User
                                            </span>
                                        @endif
                                    </div>
                                    @if($chat['unread_count'] > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                            {{ $chat['unread_count'] }}
                                        </span>
                                    @endif
                                </div>
                                @if(isset($chat['subtitle']) && $chat['subtitle'])
                                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate">
                                        {{ $chat['subtitle'] }}
                                    </p>
                                @endif
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate mt-1">
                                    {{ Str::limit($chat['last_message'], 40) }}
                                </p>
                                @if($chat['last_message_time'])
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        {{ $chat['last_message_time'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                        </svg>
                        <p>Belum ada chat tersedia</p>
                        
                        @if(auth()->user()->role === 'user')
                            <button 
                                wire:click="startChat"
                                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                            >
                                Mulai Chat dengan Admin
                            </button>
                        @endif
                    </div>
                @endforelse
                
                <!-- Available Users for Admin -->
                @if(auth()->user()->role === 'admin')
                    @php
                        $availableUsers = $this->getAllUsers();
                    @endphp
                    
                    @if($availableUsers->count() > 0)
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                User Belum Ada Chat ({{ $availableUsers->count() }})
                            </h4>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($availableUsers as $user)
                                    <div 
                                        class="flex items-center justify-between py-2 px-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors border border-transparent hover:border-blue-200 dark:hover:border-blue-600"
                                        wire:click="createChatWithUser({{ $user['id'] }})"
                                    >
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user['name'] }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user['email'] }}</p>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                Bergabung: {{ $user['created_at'] }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">Mulai Chat</span>
                                            <svg class="w-4 h-4 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm">Semua user sudah memiliki chat</p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Chat Room -->
        <div class="lg:col-span-2" id="chat-container">
            @if($selectedChatId)
                <div wire:key="chat-room-{{ $selectedChatId }}">
                    @livewire('chat-room', ['chatId' => $selectedChatId], key('chat-room-'.$selectedChatId))
                </div>
            @else
                <div class="h-full flex items-center justify-center bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-24 h-24 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                        </svg>
                        <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Pilih chat untuk memulai</h3>
                        <p>Pilih percakapan dari daftar di sebelah kiri</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
