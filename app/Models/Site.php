<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель сайта
 * Class Site
 * @package App\Models
 */
class Site extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'sites';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * Все страницы сайта
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
