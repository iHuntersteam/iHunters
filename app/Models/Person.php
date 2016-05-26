<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель персоны/личности
 * Class Person
 * @package App\Models
 */
class Person extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'persons';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'hash_name',
    ];

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * Ключевые слова персоны
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }
}
