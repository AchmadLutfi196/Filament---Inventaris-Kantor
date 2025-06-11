<?php

namespace App\Filament\Widgets;

use App\Models\Chat;
use App\Models\ChatMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ChatNotifications extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            return [];
        }

        // Total active chats
        $totalChats = Chat::where('is_active', true)
            ->where('type', 'admin_user')
            ->count();

        // Unread messages count
        $unreadCount = 0;
        $chats = Chat::where('is_active', true)
            ->where('type', 'admin_user')
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        foreach ($chats as $chat) {
            $unreadCount += $chat->unreadMessagesCount($user);
        }

        // Recent messages today
        $todayMessages = ChatMessage::whereHas('chat', function ($query) use ($user) {
            $query->where('type', 'admin_user')
                ->whereHas('users', function ($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id);
                });
        })
        ->whereDate('created_at', today())
        ->count();

        return [
            Stat::make('Total Chat Aktif', $totalChats)
                ->description('Chat dengan pengguna')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary')
                ->url(route('filament.admin.pages.chat')),

            Stat::make('Pesan Belum Dibaca', $unreadCount)
                ->description($unreadCount > 0 ? 'Memerlukan perhatian' : 'Semua terbaca')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadCount > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.pages.chat')),

            Stat::make('Pesan Hari Ini', $todayMessages)
                ->description('Total pesan masuk hari ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->url(route('filament.admin.pages.chat')),
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return '10s'; // Poll every 10 seconds for real-time updates
    }
}
