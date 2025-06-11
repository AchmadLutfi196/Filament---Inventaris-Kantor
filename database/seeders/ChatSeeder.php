<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'is_active' => true
            ]
        );

        // Create test user if not exists
        $user = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'user',
                'is_active' => true
            ]
        );

        // Create chat between admin and user
        $chat = Chat::firstOrCreate(
            ['type' => 'admin_user'],
            [
                'title' => 'Chat dengan Admin - ' . $user->name,
                'is_active' => true,
                'last_message_at' => now()
            ]
        );

        // Attach users to chat
        $chat->users()->syncWithoutDetaching([
            $admin->id => ['joined_at' => now()],
            $user->id => ['joined_at' => now()]
        ]);

        // Create sample messages
        $messages = [
            [
                'user_id' => $user->id,
                'message' => 'Halo Admin, saya ingin bertanya tentang peminjaman barang.',
                'type' => 'text'
            ],
            [
                'user_id' => $admin->id,
                'message' => 'Halo! Silakan, ada yang bisa saya bantu?',
                'type' => 'text'
            ],
            [
                'user_id' => $user->id,
                'message' => 'Bagaimana cara meminjam laptop untuk keperluan presentasi?',
                'type' => 'text'
            ],
            [
                'user_id' => $admin->id,
                'message' => 'Anda bisa mengajukan peminjaman melalui menu "Peminjaman Saya" di dashboard. Pilih barang yang ingin dipinjam, isi formulir, dan tunggu persetujuan dari admin.',
                'type' => 'text'
            ],
            [
                'user_id' => $user->id,
                'message' => 'Berapa lama biasanya proses persetujuan?',
                'type' => 'text'
            ],
            [
                'user_id' => $admin->id,
                'message' => 'Biasanya 1-2 hari kerja. Jika urgent, bisa mention di chat ini.',
                'type' => 'text'
            ]
        ];

        foreach ($messages as $index => $messageData) {
            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => $messageData['user_id'],
                'message' => $messageData['message'],
                'type' => $messageData['type'],
                'created_at' => now()->subMinutes(count($messages) - $index),
                'updated_at' => now()->subMinutes(count($messages) - $index)
            ]);
        }

        // Update chat last message time
        $chat->update(['last_message_at' => now()]);
    }
}
