<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillabe = [
        'application_id',
        'comment',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
