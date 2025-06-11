<x-filament-panels::page>
    <div class="w-full space-y-4">
        <!-- Clean Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-4 text-white shadow-sm">
            <div class="flex items-center space-x-4">
                <!-- Back Button -->
                <button 
                    wire:click="backToChatList"
                    class="inline-flex items-center px-3 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200"
                >
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Kembali
                </button>
                
                <!-- Chat Info -->
                {{-- <div class="flex-1 flex items-center space-x-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold">
                            {{ $this->getChatTitle() }}
                        </h1>
                        <p class="text-blue-100 flex items-center text-sm">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                            Real-time aktif
                        </p>
                    </div>
                </div> --}}
                
            </div>
        </div>        <!-- Chat Container -->
        @if($chat)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" style="height: 650px;">
                <div class="h-full flex flex-col">
                    <!-- Chat Header Info -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-750 dark:to-gray-700 border-b border-gray-200 dark:border-gray-600 px-6 py-4 flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                                    <span class="text-sm font-semibold text-white">
                                        {{ strtoupper(substr($this->getChatTitle(), 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $this->getChatTitle() }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                        Aktif sekarang
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-3 py-1.5 rounded-lg text-sm font-medium shadow-sm">
                                    🟢 Online
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-3 py-1.5 rounded-lg text-sm font-medium shadow-sm">
                                    🔒 Terenkripsi
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat Content -->
                    <div class="flex-1 min-h-0 flex flex-col overflow-hidden">
                        @livewire('chat-room', ['chatId' => $chatId], key('chat-room-'.$chatId))
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full p-4 mx-auto mb-4">
                        <svg class="w-8 h-8 text-white mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                        Chat Tidak Ditemukan
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Chat yang Anda cari tidak tersedia atau Anda tidak memiliki akses.
                    </p>
                    
                    <button 
                        wire:click="backToChatList"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-all duration-200"
                    >
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Kembali ke Daftar Chat
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
