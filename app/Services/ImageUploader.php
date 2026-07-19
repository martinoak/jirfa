<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Ukládá nahrané obrázky přímo do public/, aby byly součástí repozitáře
 * a daly se odeslat na server tlačítkem v administraci.
 */
class ImageUploader
{
    /**
     * Uloží soubor a vrátí cestu relativní k public/
     * (např. images/certificates/certifikat.jpg).
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $target = public_path($directory);

        if (! is_dir($target)) {
            mkdir($target, 0755, true);
        }

        $name = $this->availableName($file, $target);
        $file->move($target, $name);

        return $directory.'/'.$name;
    }

    /**
     * Název odvozený z původního jména, zbavený diakritiky a nebezpečných
     * znaků. Při kolizi se přidá pořadové číslo.
     */
    protected function availableName(UploadedFile $file, string $target): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'obrazek';

        $name = $base.'.'.$extension;
        $counter = 1;

        while (file_exists($target.DIRECTORY_SEPARATOR.$name)) {
            $name = $base.'-'.$counter.'.'.$extension;
            $counter++;
        }

        return $name;
    }

    /**
     * Smaže soubor z public/, pokud existuje.
     */
    public function delete(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $full = public_path($relativePath);

        if (is_file($full)) {
            unlink($full);
        }
    }
}
