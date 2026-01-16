<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class WeddingDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->error('No user found! Please register a user first.');
            return;
        }

        $data = [
            'Alat ibadah' => [
                'mukena' => 200000,
                "Al Qur'an" => 75000,
                'Sajadah' => 50000,
            ],
            'Skin care' => [
                'face wash' => 50000,
                'serum' => 100000,
                'moisturizer' => 200000,
                'suncreen' => 150000,
            ],
            'Pakaian' => [
                'Baju tidur' => 100000,
                'Baju' => 150000,
                'jilbab' => 200000,
                'jeans' => 100000,
            ],
            'Bodycare' => [
                'body lotion' => 150000,
                'body scrun' => 50000,
                'deodorant' => 30000,
                'parfum' => 250000,
            ],
            'Aksesoris' => [
                'Sepatu' => 1500000,
                'tas' => 100000,
                'jam tangan' => 100000,
            ],
            'Make up' => [
                'lipstik' => 100000,
                'maskara' => 100000,
                'blush on' => 50000,
                'liptin' => 100000,
                'fondation' => 250000,
                'face spray' => 150000,
                'bedak' => 150000,
            ],
            'Toiletris' => [
                'handuk' => 200000,
                'bodywash' => 50000,
                'sampo' => 50000,
                'kondisioner' => 50000,
                'pasta gigi' => 20000,
                'sikat gigi' => 30000,
            ],
        ];

        foreach ($data as $categoryTitle => $items) {
            $category = Category::create([
                'user_id' => $user->id,
                'title' => $categoryTitle,
                'order' => Category::where('user_id', $user->id)->max('order') + 1,
            ]);

            foreach ($items as $name => $price) {
                $category->items()->create([
                    'name' => $name,
                    'price' => $price,
                ]);
            }
        }
    }
}
