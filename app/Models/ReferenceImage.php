<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferenceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'path',
        'sort_order',
    ];

    public function reference(): BelongsTo
    {
        return $this->belongsTo(Reference::class);
    }

    public function url(): string
    {
        return asset($this->path);
    }
}
