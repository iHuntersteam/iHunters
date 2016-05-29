<?php

namespace App\Model;

class Sites extends Model
{
    protected $fillable = ['Name'];
    protected $guarded = ['id'];
    protected $specialchars = ['Name'];
}