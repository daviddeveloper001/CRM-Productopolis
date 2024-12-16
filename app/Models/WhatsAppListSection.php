<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppListSection extends Model
{

    protected $table = 'whatsapp_list_sections';

    protected $fillable = [
        'title'
    ];

    public function options(): HasMany
    {
        return $this->hasMany(WhatsAppListSectionOption::class, 'whatsapp_list_section_id', 'id');
    }
}
