<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Claim>
 */
class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id'        => Item::factory()->approved(),
            'user_id'        => User::factory(),
            'nama_pengambil' => fake()->name(),
            'NIMorKTP'       => fake()->numerify('################'), // 16 digit
            'phone_pengambil'=> fake()->phoneNumber(),
            'foto_pengambil' => 'claims/default.jpg',
            'tgl_ambil'      => now()->addDays(1)->toDateString(),
            'status'         => 'pending',
        ];
    }
}
