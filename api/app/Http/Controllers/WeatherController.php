<?php

namespace App\Http\Controllers;

use App\Jobs\UsersWeatherInformation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    # Get all users and weather details
    public function usersAndWeatherDetails(Request $request)
    {
        $cache_name = 'ALL_USER_WEATHER_DETAILS';

        $cache_time = 60 * 60; // One hour (3600 secs)

        if (Cache::has($cache_name)) {
            return Cache::get($cache_name);
        }

        return  Cache::remember($cache_name, $cache_time, function () {
            $users = User::plusWeatherOptions()->get();

            UsersWeatherInformation::dispatch($users);

            return $users;
        });
    }

    # Get Single User weather details
    public function userWeatherDetails(Request $request, User $user)
    {
        $cache_name = "USER_WEATHER_DETAILS_$user->id";

        $cache_time = 60 * 60; // One hour (3600 secs)

        $user_weather_details = (Cache::has($cache_name)) ?
            Cache::get($cache_name) : Cache::remember($cache_name, $cache_time, function () use ($user) {
                return $user->weather_details;
            });

        return response()->json([
            'data' => $user_weather_details
        ], 200);
    }
}
