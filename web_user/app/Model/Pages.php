<?php

namespace App\Model;

class Pages extends Model
{
    protected $fillable = ['Url', 'SiteID'];
    protected $guarded = ['id'];
    protected $specialchars = ['Url', 'SiteID'];
}