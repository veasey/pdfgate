<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'is_subscribed', 'is_admin', 'pdf_generated_count', 'subscribed_until', 'last_generated_at'])]
#[Hidden(['password', 'remember_token', 'personal_access_tokens'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

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
            'is_subscribed' => 'boolean',
            'is_admin' => 'boolean',
            'pdf_generated_count' => 'integer',
            'subscribed_until' => 'datetime',
            'last_generated_at' => 'datetime',
        ];
    }

    public function apiTokens(): MorphMany
    {
        return $this->tokens();
    }

    public function pdfJobs(): HasMany
    {
        return $this->hasMany(PdfJob::class);
    }

    public function getIsSubscribedAttribute($value): bool
    {
        if ($this->subscribed_until) {
            return now()->lte($this->subscribed_until);
        }

        return (bool) $value;
    }
}
