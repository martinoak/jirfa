<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reference extends Model
{
    use HasFactory;

    /**
     * "references" je v některých databázích rezervované slovo, proto je
     * název tabulky uveden výslovně.
     */
    protected $table = 'references';

    protected $fillable = [
        'title',
        'place',
        'category',
        'thumbnail',
        'sort_order',
    ];

    /**
     * Kategorie používané filtrem na homepage.
     */
    public const CATEGORIES = [
        'strechy' => 'Střechy',
        'garaze' => 'Garáže',
        'podlahy' => 'Podlahy',
        'pergoly' => 'Pergoly',
        'stity' => 'Štíty',
        'ostatni' => 'Ostatní',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ReferenceImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * Náhled do mřížky -- zmenšenina, pokud existuje, jinak první obrázek.
     */
    public function thumbnailUrl(): ?string
    {
        if ($this->thumbnail) {
            return asset($this->thumbnail);
        }

        return $this->images->first()?->url();
    }

    /**
     * Adresy všech obrázků galerie pro lightbox.
     *
     * @return array<int, string>
     */
    public function imageUrls(): array
    {
        return $this->images->map(fn (ReferenceImage $image) => $image->url())->all();
    }

    public function fullTitle(): string
    {
        return trim($this->title.' '.$this->place);
    }
}
