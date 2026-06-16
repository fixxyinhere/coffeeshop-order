<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Table extends Model
{
    protected $table = 'tables';

    protected $fillable = [
        'table_number',
        'qr_code_token',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Table $table): void {
            if (empty($table->qr_code_token)) {
                $table->qr_code_token = Str::random(40);
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getMenuUrlAttribute(): string
    {
        return route('customer.menu', ['token' => $this->qr_code_token]);
    }
}
