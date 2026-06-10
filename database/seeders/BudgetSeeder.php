<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $categories = Category::all();


        foreach ($categories as $category) {
            Budget::factory()->create([
                'user_id'     => $user->id,
                'category_id' => $category->id,
            ]);
        }
    }
}
