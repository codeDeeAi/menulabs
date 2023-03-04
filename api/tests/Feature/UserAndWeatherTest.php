<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserAndWeatherTest extends TestCase
{
    /**
     * Get all users and weather conditions/details.
     */
    public function test_get_all_users_and_weather(): void
    {
        for ($i = 0; $i < 2; $i++) {
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => 'password',
                'remember_token' => Str::random(10),
                'longitude' => fake()->longitude(),
                'latitude' => fake()->latitude(),
            ]);
        }

        $response = $this->get('/users');

        $response->assertStatus(200);
    }
    /**
     * Get single user weather conditions/details.
     */
    public function test_get_single_user_weather(): void
    {
        $user = User::create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'longitude' => fake()->longitude(),
            'latitude' => fake()->latitude(),
        ]);

        $response = $this->get('/weather/' . $user->id);

        $response->assertStatus(200);
    }
}
