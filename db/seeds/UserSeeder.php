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
            $unique_email = $this->getUniqueEmail();

            $data = [
                'picture' => "/api/image-generator?text=No Image Available",
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $unique_email,
                'password' => password_hash($unique_email, PASSWORD_DEFAULT),
                'is_admin' => $i === 1 ? 1 : 0 // only 1 admin
            ];

            echo __CLASS__ . " => {$i}/{$limit}\n";

            $this->insert('users', $data);
        }
    }

    private function getUniqueEmail()
    {
        $faker = Factory::create();

        $result = $this->query("SELECT email FROM users");
        $user_emails = $result->fetchAll();
        $result->closeCursor();

        do {
            $unique_email = $faker->email;
        } while (in_array($unique_email, $user_emails));

        return $unique_email;
    }
}
