<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories=[

            ['name'=>'salary','type'=>'income',
            'icon'=>'salary-icon', 'color'=>'#27cfc9',
            'is_default'=>true,'user_id'=>null],

            ['name'=>'pocket-money','type'=>'income',
            'icon'=>'pocket-money-icon', 'color'=>'#27cf46',
            'is_default'=>true,'user_id'=>null],

            ['name'=>'bonus-money','type'=>'income',
            'icon'=>'bonus-money-icon', 'color'=>'#ebca27',
            'is_default'=>true,'user_id'=>null],

            ['name'=>'food','type'=>'expense',
            'icon'=>'food-icon', 'color'=>'#e22013',
            'is_default'=>true,'user_id'=>null],

            ['name'=>'travel','type'=>'expense',
            'icon'=>'travel-icon', 'color'=>'#e22013',
            'is_default'=>true,'user_id'=>null],

            ['name'=>'skin-care','type'=>'expense',
            'icon'=>'skin-care-icon', 'color'=>'#e22013',
            'is_default'=>true,'user_id'=>null],

        ];
        Category::insert(array_map(function ($category,) {
            return array_merge($category,
            [
                'created_at'=> now(),
            'updated_at'=> now(),
            ]);

        },$categories));
    }
}
