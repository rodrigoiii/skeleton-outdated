<?php

use App\Models\User;
use PHPUnit\Framework\TestCase;
use SkeletonCore\Traits\UnitTestingTrait;

class UserTest extends TestCase
{
    use UnitTestingTrait;

    /**
     * Connect to database
     */
    public static function setUpBeforeClass()
    {
        static::dbConnect();
    }

    /**
     * @test
     */
    public function return_true_if_user_info_was_changed()
    {
        $users = User::all();

        // pick first user or create one
        if (!$user = $users->first())
        {
            $user = User::create([
                'first_name' => $this->faker()->firstName,
                'last_name' => $this->faker()->lastName,
                'email' => $this->faker()->email
            ]);
        }

        $id = $user->id;
        $data = [];

        // provide data first name different from first
        do {
            $data['first_name'] = $this->faker()->firstName;
        } while ($user->first_name === $data['first_name']);

        // provide data last name different from first
        do {
            $data['last_name'] = $this->faker()->lastName;
        } while ($user->last_name === $data['last_name']);

        // provide data email different from first
        do {
            $data['email'] = $this->faker()->email;
        } while ($user->email === $data['email']);

        $result = User::_update($id, $data);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function return_false_if_user_info_was_not_changed()
    {
        $users = User::all();

        // pick first user or create one
        if (!$user = $users->first())
        {
            $user = User::create([
                'first_name' => $this->faker()->firstName,
                'last_name' => $this->faker()->lastName,
                'email' => $this->faker()->email
            ]);
        }

        $id = $user->id;
        $data = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email
        ];

        $result = User::_update($id, $data);
        $this->assertFalse($result);
    }
}
