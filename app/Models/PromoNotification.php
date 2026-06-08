<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoNotification extends Model
{
    protected $fillable = [
        'title_uz', 'title_en', 'title_tr',
        'body_uz',  'body_en',  'body_tr',
        'image', 'sent_at', 'recipients_count',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
