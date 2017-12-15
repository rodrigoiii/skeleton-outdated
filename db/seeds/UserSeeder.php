<?php

use Phinx\Seed\AbstractSeed;
use App\User;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $limit = 10;
        $data  = [];

        for ($i = 1; $i <= $limit; $i++)
        {
            $data[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName
            ];

            echo __CLASS__ . " => {$i}/{$limit}\n";
        }

        $this->insert('users', $data);
    }
}
