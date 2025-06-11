<x-filament-panels::page>
    <div class="max-w-4xl mx-auto space-y-4">
        <!-- Clean Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-4 text-white shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold">
                            {{ auth()->user()->role === 'admin' ? 'Chat Support Center' : 'Support Chat' }}
                        </h1>
                        <p class="text-blue-100 text-sm">
                            {{ auth()->user()->role === 'admin' ? 'Kelola semua percakapan support' : 'Hubungi tim support kami' }}
                        </p>
                    </div>
                </div>
                
                @if(auth()->user()->role === 'user')
                    <button 
                        wire:click="startChat"
                        class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Mulai Chat
                    </button>
                @endif
            </div>
        </div>        <!-- Simple Search Section -->
        @if(count($this->getChats()) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search Bar -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            placeholder="Cari percakapan..."
                            class="w-full pl-10 pr-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                        >
                    </div>
                    
                    <!-- Filter Buttons -->
                    <div class="flex items-center space-x-2">
                        <button class="inline-flex items-center px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/>
                            </svg>
                            Semua
                        </button>
                        
                        @if(collect($this->getChats())->where('unread_count', '>', 0)->count() > 0)
                            <button class="inline-flex items-center px-3 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors text-sm">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z"/>
                                </svg>
                                Belum Dibaca
                                <span class="ml-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                                    {{ collect($this->getChats())->where('unread_count', '>', 0)->count() }}
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif        <!-- Clean Chat List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            @php
                $chats = $this->getChats();
            @endphp
            
            @forelse($chats as $chat)
                <div 
                    class="group p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition-all duration-200"
                    wire:click="openChat({{ $chat['id'] }})"
                >
                    <div class="flex items-center space-x-3">
                        <!-- Avatar -->
                        <div class="relative">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-semibold text-white">
                                    {{ strtoupper(substr($chat['title'], 0, 1)) }}
                                </span>
                            </div>
                            @if(isset($chat['is_online']) && $chat['is_online'])
                                <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                            @endif
                        </div>
                        
                        <!-- Chat Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $chat['title'] }}
                                    </h3>
                                    
                                    <!-- Role Badge -->
                                    @if(isset($chat['user_role']) && $chat['user_role'] === 'admin')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                            Admin
                                        </span>
                                    @elseif(isset($chat['user_role']) && $chat['user_role'] === 'user')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            User
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Right side info -->
                                <div class="flex items-center space-x-2">
                                    @if($chat['unread_count'] > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                            {{ $chat['unread_count'] }}
                                        </span>
                                    @endif
                                    
                                    @if($chat['last_message_time'])
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $chat['last_message_time'] }}
                                        </span>
                                    @endif
                                    
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Last Message -->
                            <div class="mt-1">
                                @if(isset($chat['subtitle']) && $chat['subtitle'])
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                        {{ $chat['subtitle'] }}
                                    </p>
                                @endif
                                
                                <p class="text-sm text-gray-600 dark:text-gray-300 truncate">
                                    {{ Str::limit($chat['last_message'], 60) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full p-4 mx-auto mb-4">
                        <svg class="w-8 h-8 text-white mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                        {{ auth()->user()->role === 'admin' ? 'Belum Ada Chat Masuk' : 'Belum Ada Percakapan' }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ auth()->user()->role === 'admin' ? 'Belum ada pengguna yang memulai percakapan support' : 'Mulai percakapan dengan tim support kami' }}
                    </p>
                    
                    @if(auth()->user()->role === 'user')
                        <button 
                            wire:click="startChat"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-all duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Mulai Chat dengan Admin
                        </button>
                    @endif
                </div>
            @endforelse
        </div>        <!-- Available Users for Admin -->
        @if(auth()->user()->role === 'admin')
            @php
                $availableUsers = $this->getAllUsers();
            @endphp
            
            @if($availableUsers->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                        <div class="flex items-center space-x-2">
                            <div class="p-1.5 bg-white/20 rounded-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">
                                    User Baru ({{ $availableUsers->count() }})
                                </h3>
                                <p class="text-indigo-100 text-xs">
                                    User yang belum memiliki percakapan
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($availableUsers as $user)
                            <div 
                                class="group p-4 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition-all duration-200"
                                wire:click="createChatWithUser({{ $user['id'] }})"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="relative">
                                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                <span class="text-sm font-semibold text-white">
                                                    {{ strtoupper(substr($user['name'], 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $user['name'] }}
                                            </h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                                {{ $user['email'] }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Bergabung: {{ $user['created_at'] }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-green-500 text-white px-3 py-1.5 rounded-lg font-medium text-xs">
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Mulai Chat
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif        
        <!-- Clean Statistics Section -->
        @if(count($this->getChats()) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Chats -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-xs font-medium">Total Percakapan</p>
                            <p class="text-2xl font-bold">{{ count($this->getChats()) }}</p>
                        </div>
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Unread Messages -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-xs font-medium">Pesan Belum Dibaca</p>
                            <p class="text-2xl font-bold">{{ collect($this->getChats())->sum('unread_count') }}</p>
                        </div>
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-xs font-medium">Pengguna Aktif</p>
                            <p class="text-2xl font-bold">{{ count($this->getAvailableUsers()) }}</p>
                        </div>
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Simple Footer Section -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="flex justify-center space-x-4 mb-2">
                    <div class="flex items-center space-x-1 text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium">24/7 Support</span>
                    </div>
                    
                    <div class="flex items-center space-x-1 text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium">Respon Cepat</span>
                    </div>
                    
                    <div class="flex items-center space-x-1 text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium">Aman</span>
                    </div>
                </div>
                
                <div class="flex justify-center items-center space-x-1 text-gray-500 dark:text-gray-400">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs">Sistem Chat Inventaris Kantor</span>
                </div>
            </div>
        </div>
    </div>    <!-- Simplified JavaScript -->
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add simple hover effects
        const chatItems = document.querySelectorAll('.group');
        chatItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(2px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Simple loading state for buttons
        const buttons = document.querySelectorAll('button[wire\\:click]');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="flex items-center justify-center"><svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle><path fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path></svg>Loading...</span>';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1000);
            });
        });

        // Auto-refresh every 30 seconds
        setInterval(() => {
            if (typeof Livewire !== 'undefined') {
                Livewire.emit('refreshChatList');
            }
        }, 30000);
    });
    </script>
    @endpush
</x-filament-panels::page>
