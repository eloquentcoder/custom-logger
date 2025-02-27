<?php

namespace EloquentCoder\LogSender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedLog extends Model
{
    use HasFactory;

    protected $fillable = ['level', 'message', 'context', 'environment', 'sent', 'project'];

    protected $casts = [
        'context' => 'array',
    ];

    public static function getBatch($limit = 50)
    {
        return self::where('sent', false)->limit($limit)->get();
    }
}
