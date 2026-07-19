<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Reference;
use Illuminate\Database\Seeder;

/**
 * Přenese certifikáty a reference, které byly dřív napevno v šabloně,
 * do databáze. Obrázky zůstávají na svých místech v public/.
 *
 * Seeder je možné spustit opakovaně -- už existující záznamy přeskočí.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCertificates();
        $this->seedReferences();
    }

    protected function seedCertificates(): void
    {
        $certificates = ['bramac1', 'bramac2', 'isover', 'rigips', 'velux1', 'velux2'];

        foreach ($certificates as $index => $name) {
            Certificate::firstOrCreate(
                ['image' => "images/certificates/{$name}.jpg"],
                ['title' => 'Certifikát', 'sort_order' => $index],
            );
        }
    }

    protected function seedReferences(): void
    {
        foreach ($this->referenceData() as $index => $data) {
            $reference = Reference::firstOrCreate(
                ['title' => $data['title'], 'place' => $data['place']],
                [
                    'category' => $data['category'],
                    'thumbnail' => "images/reference/{$data['dir']}/{$data['thumb']}",
                    'sort_order' => $index,
                ],
            );

            if ($reference->images()->exists()) {
                continue;
            }

            foreach (array_values($data['images']) as $position => $file) {
                $reference->images()->create([
                    'path' => "images/reference/{$data['dir']}/{$file}",
                    'sort_order' => $position,
                ]);
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function referenceData(): array
    {
        return [
            ['category' => 'garaze',  'title' => 'Garáž',          'place' => 'Praha-Pankrác', 'dir' => 'garaze',  'thumb' => '01_m.jpg', 'images' => ['01.jpg', '02.jpg', '03.jpg', '04.jpg']],
            ['category' => 'strechy', 'title' => 'Plzeň',          'place' => 'krov pivovaru', 'dir' => 'strechy', 'thumb' => '09_m.jpg', 'images' => ['09.jpg', '10.jpg', '11.jpg', '12.jpg']],
            ['category' => 'pergoly', 'title' => 'Pergola',        'place' => 'Hřivnov',       'dir' => 'pergoly', 'thumb' => '01_m.jpg', 'images' => ['01.jpg', '02.jpg', '03.jpg']],
            ['category' => 'pergoly', 'title' => 'Pergola',        'place' => 'Načeradec',     'dir' => 'pergoly', 'thumb' => '04_m.jpg', 'images' => ['04.jpg', '05.jpg', '06.jpg']],
            ['category' => 'podlahy', 'title' => 'Podlahy',        'place' => 'Jičín',         'dir' => 'podlahy', 'thumb' => '01_m.jpg', 'images' => ['01.jpg', '02.jpg', '03.jpg', '04.jpg']],
            ['category' => 'ostatni', 'title' => 'Obložení',       'place' => 'klimatizace',   'dir' => 'ostatni', 'thumb' => '01_m.jpg', 'images' => ['01.jpg', '02.jpg', '03.jpg', '04.jpg']],
            ['category' => 'strechy', 'title' => 'Střecha',        'place' => 'Kolovraty',     'dir' => 'strechy', 'thumb' => '01_m.jpg', 'images' => ['01.jpg', '02.jpg', '03.jpg', '04.jpg', '05.jpg', '06.jpg', '07.jpg', '08.jpg']],
            ['category' => 'stity',   'title' => 'Štít',           'place' => 'Středokluky',   'dir' => 'stity',   'thumb' => '01_m.jpg', 'images' => ['01.jpg', '02.jpg', '03.jpg', '04.jpg', '05.jpg', '06.jpg']],
            ['category' => 'pergoly', 'title' => 'Domov důchodců', 'place' => 'Praha 4',       'dir' => 'pergoly', 'thumb' => '07_m.jpg', 'images' => ['07.jpeg', '08.jpeg', '09.jpeg', '10.jpeg']],
        ];
    }
}
