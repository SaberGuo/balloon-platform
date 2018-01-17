<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\DeviceData::class, function (Faker $faker) {
    $key = 't_'.$faker->numberBetween(1, 1);
    $data = [
        'ts' => $faker->dateTimeThisMonth,
        'data' => ['lon' => [ 'value' => $faker->randomFloat(2, 112, 114)],'lat'=>['value'=>$faker->randomFloat(2,22,24)]],
        'device_config_id' => 3,
        'device_id' => 2,
    ];
    return $data;
});
