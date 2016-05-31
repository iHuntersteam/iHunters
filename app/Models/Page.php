<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель страницы сайта
 * Class Page
 * @package App\Models
 */
class Page extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'pages';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'url',
        'site_id',
        'found_date_time',
        'last_scan_date',
    ];

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var array $dates
     */
    public $dates = [
        'found_date_time',
        'last_scan_date',
    ];

    protected $hidden = [
        'pivot', 'url_hash'
    ];

    /**
     * Сайт, которому принадлежит страница
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
