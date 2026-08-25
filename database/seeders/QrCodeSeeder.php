<?php

namespace Database\Seeders;

use App\Models\QrCode;
use Illuminate\Database\Seeder;

class QrCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [];
        for ($i = 1; $i <= 50; $i++) {
            $codes[] = [
                'code' => 'CSTL' . $i,
                'prefix' => 'CSTL',
                'sequence' => $i,
                'lab_id' => 1,
            ];
        }
        QrCode::insert($codes);
    }
}
