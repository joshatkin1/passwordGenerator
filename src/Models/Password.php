<?php

namespace Pixled\PasswordGenerator\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \http\Exception\InvalidArgumentException;
use Pixled\PasswordGenerator\Exceptions\DataBaseFailureException;
use Pixled\PasswordGenerator\Exceptions\UserDoesntExistException;

class Password extends Model
{
    private $passwordSalt = env('PASSWORD_SALT');

    /**
     * Update Specific Users Password
     *
     * @param int $userId
     * @param string $password
     * @throws DataBaseFailureException
     * @return true
     */
    public function update(int $userId, string $password): bool
    {
        if (empty($userId) || empty($password)){
            throw new InvalidArgumentException('User ID and Password Required');
        }

        $password = Hash::make($password . $this->passwordSalt, [
            'rounds' => 12,
        ]);

        $success = DB::table('users')->where('id' , $userId)->update(['password' => $password]);

        if(empty($success)){
            throw new DataBaseFailureException;
        }

        return true;
    }

    /**
     * This validates the input password matches the hashed password in the database
     *
     * @param int $userId
     * @param string $password
     *
     * @return bool
     *
     * @throws UserDoesntExisttException
     */
    public function validatePassword(int $userId, string $password): bool
    {
        $user = DB::table('users')->where('id', $userId)->get();

        if(empty($user)){
            throw new UserDoesntExistException('This user ID does not have an associated user');
        }

        $password = $user->password;

        return Hash::check($password . $this->passwordSalt, $password);
    }
}