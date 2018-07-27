<?php

use App\Models\User;
use SkeletonCore\BaseTestCase;
use Tests\DBConnectTrait;

class UserTest extends BaseTestCase
{
    public static function setUpBeforeClass()
    {
        static::dbCnnect();
    }

    /**
     * @test
     */
    public function returnTrueIfUserInfoHasChange()
    {
        $user = new User;
        var_dump($user->getFillable());
        $users = User::all();
        if ($users->isNotEmpty())
        {
            $user = $users->first();
        }
        else
        {
            // $user = User::
        }

        $this->assertTrue(true, 'yehey');
        // User::_update();
    }

    /**
     * @test
     */
    public function returnFalseIfUserInfoHasNoChange()
    {

    }
}
