<?php

namespace App\Filament\Resources\FilamentResource\Pages;

use App\Filament\Resources\FilamentResource;
use Filament\Resources\Pages\Page;

class ChatManagement extends Page
{
    protected static string $resource = FilamentResource::class;

    protected static string $view = 'filament.resources.filament-resource.pages.chat-management';
}
