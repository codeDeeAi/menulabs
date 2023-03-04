<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'longitude',
        'latitude',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be added to model.
     *
     * @var array<string>
     */
    protected $append = [
        'weather_details'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Fetch user weather details.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function weatherDetails(): Attribute
    {
        return new Attribute(
            get: function () {
                $weatherApi = env('WEATHER_API') ?? 'https://api.weather.gov/points/';
                $path = $weatherApi . $this->latitude . ',' . $this->longitude;
                $response = Http::get($path);

                return $response->json();
            },
        );
    }

    /**
     * Scope a query to users and weather details.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopePlusWeatherOptions($query)
    {
        $query->select(
            'id',
            'name',
            'email',
            'longitude',
            'latitude',
        );
    }
}
