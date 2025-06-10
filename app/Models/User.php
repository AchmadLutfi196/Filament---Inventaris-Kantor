<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean'
        ];
    }

    /**
     * Role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * Relasi dengan Peminjaman
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Peminjaman aktif user
     */
    public function peminjamansAktif(): HasMany
    {
        return $this->hasMany(Peminjaman::class)
                    ->whereIn('status', ['disetujui', 'dipinjam']);
    }

    /**
     * Check apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check apakah user aktif
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Filament admin access
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->isActive();
    }

    /**
     * Scope untuk admin
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope untuk user biasa
     */
    public function scopeUsers($query)
    {
        return $query->where('role', self::ROLE_USER);
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Chat relationships
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class)
            ->withPivot(['joined_at', 'last_read_at'])
            ->withTimestamps();
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Get or create chat between user and admin
     */
    public function getOrCreateChatWithAdmin(): Chat
    {
        // Find existing chat with admin
        $existingChat = $this->chats()
            ->whereHas('users', function ($query) {
                $query->where('role', self::ROLE_ADMIN);
            })
            ->where('type', 'admin_user')
            ->first();

        if ($existingChat) {
            return $existingChat;
        }

        // Create new chat
        $chat = Chat::create([
            'title' => 'Chat dengan Admin - ' . $this->name,
            'type' => 'admin_user',
            'is_active' => true
        ]);

        // Add user to chat
        $chat->users()->attach($this->id, ['joined_at' => now()]);
        
        // Add admin to chat
        $admin = User::where('role', self::ROLE_ADMIN)->first();
        if ($admin) {
            $chat->users()->attach($admin->id, ['joined_at' => now()]);
        }

        return $chat;
    }
}