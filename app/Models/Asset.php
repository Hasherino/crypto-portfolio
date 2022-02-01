<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'value',
        'currency'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
