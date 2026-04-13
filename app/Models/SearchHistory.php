<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
   protected $fillable = [
    'user_id',
    'conversation_id',
    'query',
    'answer',
    'file_name',
    'file_path',
    'sources'
];

public function user() {
    return $this->belongsTo(User::class);
}

    // sources যদি JSON/Array হয় তবে এটি যোগ করুন
    protected $casts = [
        'sources' => 'array',
    ];
}
