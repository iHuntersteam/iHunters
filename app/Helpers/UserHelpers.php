<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 03.06.16
 * Time: 17:54
 */

namespace App\Helpers;


use App\User;

class UserHelpers
{
    /**
     * Рандомный id пользователя
     * @return mixed
     */
    public static function randomUserId()
    {
        $ids = User::lists('id')->toArray();
        if (empty($ids)) {
            return null;
        }

        return $ids[ random_int(0, count($ids) - 1) ];
    }

    /**
     * Рандомный пользователь
     * @return null
     */
    public static function randomUser()
    {
        $id = self::randomUserId();

        if (is_null($id)) {
            return null;
        }

        return User::findOrFail(self::randomUserId());
    }

    /**
     * Id рандомного администратора
     * @return null
     */
    public static function randomAdminId()
    {
        $ids = User::where('is_admin', 1)
            ->lists('id')
            ->toArray();
        if (empty($ids)) {
            return null;
        }

        return $ids[ random_int(0, count($ids) - 1) ];
    }
}