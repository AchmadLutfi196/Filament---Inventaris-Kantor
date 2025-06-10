<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Chat extends Page
{
    protected static ?string $navigationIcon = null; // Hide from navigation
    protected static string $view = 'filament.pages.chat-redirect';
    protected static bool $shouldRegisterNavigation = false; // Hide from navigation

    public function mount()
    {
        // Redirect to new ChatList page
        return redirect()->route('filament.admin.pages.chat-list');
    }
}
