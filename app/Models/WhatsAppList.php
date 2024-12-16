<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppList extends Model
{

    protected $table = 'whatsapp_lists';

    protected $fillable = [
        'title',
        'description',
        'button_text',
        'footer_text'
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(WhatsAppListSection::class, 'whatsapp_list_id', 'id');
    }
}
