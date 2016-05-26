<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 26.05.16
 * Time: 17:11
 */

namespace App\Helpers;


use App\Models\Person;

class PersonHelpers
{
    /**
     * Рандомный идентификатор персоны
     * @return mixed
     */
    public static function randomPersonId()
    {
        $ids = Person::lists('id')->toArray();

        return $ids[ random_int(0, count($ids) - 1) ];
    }

    /**
     * Рандомная персона
     * @return mixed
     */
    public static function randomPerson()
    {
        return Person::find(self::randomPersonId());
    }
}