<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payer;

class PayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payers = [
            ['name' => 'MAG', 'email' => 'mag@example.com'],
            ['name' => 'IRCC', 'email' => 'ircc@example.com'],
            ['name' => 'CBSA', 'email' => 'cbsa@example.com'],
            ['name' => 'IRB', 'email' => 'irb@example.com'],
            ['name' => 'CIC', 'email' => 'cic@example.com'],
        ];

        foreach ($payers as $payer) {
            Payer::updateOrCreate(['name' => $payer['name']], ['email' => $payer['email']]);
        }
    }
}
