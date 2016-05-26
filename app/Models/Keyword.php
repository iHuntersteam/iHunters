<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель ключевого слова
 * Class Keyword
 * @package App\Models
 */
class Keyword extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'keywords';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'person_id',
    ];

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * Персона, с которой связано ключевое слово
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
