<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppListSectionOption extends Model
{

    protected $table = 'whatsapp_list_section_options';

    protected $fillable = [
        'title',
        'description'
    ];
}
