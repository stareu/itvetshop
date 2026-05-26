<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
		$role = Role::query()->create([
			'name' => 'Админ'
		]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.ru',
			'password' => 'admin',
			'role_id' => $role->id
        ]);

		Product::create([
			'name' => 'Принтер HP DeskJet 2320',
			'is_active' => true,
			'type' => 1,
			'price' => 300,
			'quantity' => 100
		]);

		Product::create([
			'name' => 'Клавиатура HP Pavilion G6',
			'is_active' => true,
			'type' => 1,
			'price' => 300,
			'quantity' => 100
		]);

		Product::create([
			'name' => 'Мышь проводная A4Tech X-710BK',
			'is_active' => true,
			'type' => 1,
			'price' => 300,
			'quantity' => 100
		]);
    }
}
