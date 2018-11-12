<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Factory::create();

        $limit = 30;
        $data  = [];

        for ($i = 1; $i <= $limit; $i++)
        {
            $email = $faker->email;

            try {
                $data[] = [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'email' => $email,
                    'password' => password_hash($email, PASSWORD_DEFAULT),
                ];

                echo __CLASS__ . " => {$i}/{$limit}\n";
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }

        $this->insert('users', $data);
    }
}
