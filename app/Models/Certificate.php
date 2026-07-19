<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'sort_order',
    ];

    /**
     * Veřejná URL obrázku (soubory leží přímo v public/).
     */
    public function url(): string
    {
        return asset($this->image);
    }
}
