# 💬 Fitur Chatroom - Dokumentasi

## Deskripsi
Fitur chatroom memungkinkan komunikasi real-time antara user dan admin dalam sistem inventaris kantor. Dibangun menggunakan Laravel Reverb dan Livewire untuk pengalaman real-time yang optimal.

## Fitur Utama

### 🔥 Real-time Messaging
- Pesan real-time tanpa perlu refresh
- Broadcasting menggunakan Laravel Reverb
- Auto-scroll ke pesan terbaru

### 📁 File Sharing
- Upload dan kirim file (gambar, dokumen)
- Preview otomatis untuk gambar
- Download file yang dikirim

### 👤 User Management
- Otomatis buat chat antara user dan admin
- Admin dapat melihat semua chat aktif
- Sistem notifikasi pesan belum dibaca

### 📊 Dashboard Integration
- Widget notifikasi chat di admin dashboard
- Statistik pesan real-time
- Quick access ke chat

## Cara Menggunakan

### Untuk User (Frontend)
1. Login ke sistem
2. Klik menu "Chat" di navigation
3. Mulai percakapan dengan admin
4. Ketik pesan atau upload file
5. Terima respon real-time dari admin

### Untuk Admin (Filament)
1. Login ke admin panel (/admin)
2. Lihat widget notifikasi chat di dashboard
3. Klik menu "Chat Support" di sidebar
4. Pilih chat dari daftar user
5. Balas pesan user secara real-time

## Teknologi yang Digunakan

- **Laravel Reverb**: WebSocket server untuk real-time
- **Livewire**: Reactive components
- **Laravel Echo**: Client-side broadcasting
- **Pusher JS**: WebSocket client library
- **Filament**: Admin panel framework

## Setup dan Konfigurasi

### 1. Start WebSocket Server
Jalankan file `start-reverb.bat` atau command:
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

### 2. Environment Variables
Pastikan setting berikut di file `.env`:
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

### 3. Queue Worker (Optional)
Untuk performa optimal, jalankan queue worker:
```bash
php artisan queue:work
```

## Database Structure

### Tables
- `chats`: Menyimpan informasi chat room
- `chat_messages`: Menyimpan pesan individual
- `chat_user`: Pivot table untuk relasi many-to-many

### Models
- `Chat`: Model untuk chat room
- `ChatMessage`: Model untuk pesan
- `User`: Extended dengan relasi chat

## File Upload

### Supported Formats
- **Images**: jpg, jpeg, png
- **Documents**: pdf, doc, docx, txt
- **Max Size**: 10MB

### Storage
Files disimpan di `storage/app/public/chat-files/`

## Broadcasting Events

### NewChatMessage Event
Triggered ketika pesan baru dibuat:
- Channel: `private-chat.{chatId}`
- Event: `new-message`
- Data: message details, user info, timestamp

## Security Features

### Channel Authorization
- User hanya bisa akses chat mereka sendiri
- Admin bisa akses semua chat
- Validasi melalui `routes/channels.php`

### File Upload Security
- Validasi tipe file
- Size limit enforcement
- Secure file naming

## Performance Optimization

### Lazy Loading
- Messages dimuat on-demand
- Polling untuk update real-time
- Efficient database queries

### Caching
- Laravel cache untuk session data
- Optimized query dengan eager loading

## Troubleshooting

### WebSocket Connection Issues
1. Pastikan Reverb server berjalan
2. Check firewall settings untuk port 8080
3. Verify environment variables

### File Upload Problems
1. Check storage permissions
2. Verify file size limits
3. Ensure storage link exists: `php artisan storage:link`

### Real-time Not Working
1. Check Reverb server status
2. Verify Echo configuration
3. Check browser console for errors

## Development Notes

### Extending Features
- Tambah tipe pesan baru di `ChatMessage` model
- Extend broadcasting events untuk notifikasi
- Custom Livewire components untuk UI

### Testing
- Test data tersedia via `ChatSeeder`
- Manual testing dengan multiple browser tabs
- Check real-time functionality

## Support

Untuk bantuan teknis atau bug reports:
1. Check logs di `storage/logs/`
2. Verify WebSocket connection
3. Test dengan data seeder

---

**Last Updated**: June 10, 2025
**Version**: 1.0.0
