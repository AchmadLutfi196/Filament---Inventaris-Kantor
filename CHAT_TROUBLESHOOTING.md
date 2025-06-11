# Chat Troubleshooting Guide

## Masalah Admin Tidak Bisa Membalas Chat

### Perbaikan yang Sudah Dilakukan:

1. **Broadcasting Event**: 
   - ✅ Added `broadcast(new NewChatMessage($message))->toOthers()` di sendMessage() dan sendFile()
   - ✅ Update last_message_at saat pesan dikirim

2. **Component Key Management**:
   - ✅ Improved wire:key untuk ChatRoom component di Filament 
   - ✅ Added dispatch events untuk refresh component

3. **UI Improvements**:
   - ✅ Added visual feedback untuk selected chat
   - ✅ Added error handling dan error messages

4. **Error Handling**:
   - ✅ Added try-catch di sendMessage method
   - ✅ Added error display di chat interface

### Testing Steps:

1. **Start Services**:
   ```bash
   cd c:\laragon\www\Filament---Inventaris-Kantor
   php artisan reverb:start  # Terminal 1 (background)
   php artisan serve         # Terminal 2
   ```

2. **Login as Admin**:
   - URL: http://localhost:8000/admin
   - Email: admin@admin.com
   - Password: password
   - Navigate to: Chat Support

3. **Login as User**:
   - URL: http://localhost:8000
   - Email: user@user.com  
   - Password: password
   - Navigate to: Chat

4. **Test Flow**:
   - User kirim pesan ke admin
   - Admin pilih chat dari sidebar
   - Admin reply pesan
   - Check real-time updates

### Potential Issues:

1. **WebSocket Connection**:
   - Pastikan Laravel Reverb running di port 8080
   - Check browser console untuk WebSocket errors
   - Verify REVERB_APP_KEY di .env

2. **Broadcasting Configuration**:
   - Verify .env settings:
     ```
     BROADCAST_DRIVER=reverb
     REVERB_APP_ID=your-app-id
     REVERB_APP_KEY=your-app-key
     REVERB_APP_SECRET=your-app-secret
     ```

3. **Component State**:
   - Jika admin tidak melihat chat updates, refresh halaman
   - Component key harus unique untuk setiap chat

4. **Database Issues**:
   - Verify chat_user pivot table ada data
   - Admin harus auto-join chat ketika memilih chat

### Debug Commands:

```bash
# Check if Reverb is running
netstat -an | findstr 8080

# Check database data
php artisan tinker
> App\Models\Chat::with('users', 'messages')->get()
> App\Models\User::where('role', 'admin')->first()->chats

# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Manual Testing Checklist:

- [ ] Reverb server running
- [ ] Laravel dev server running  
- [ ] Admin can login to /admin
- [ ] User can login to frontend
- [ ] User can send message
- [ ] Admin can see chat in sidebar
- [ ] Admin can select chat
- [ ] ChatRoom component loads for admin
- [ ] Admin can type and send reply
- [ ] Message appears in admin interface
- [ ] User receives message in real-time
- [ ] File upload works
- [ ] WebSocket events triggered

### Known Working Features:

✅ User registration dan login
✅ Chat creation between user and admin  
✅ Message storage in database
✅ File upload functionality
✅ Admin chat list display
✅ Chat selection UI
✅ Broadcasting event structure
✅ Component mounting dan initialization

### Next Steps:

1. Run test-chat.bat untuk automated testing
2. Check browser console untuk JavaScript errors
3. Monitor Laravel logs untuk PHP errors
4. Verify WebSocket connection di Network tab browser
