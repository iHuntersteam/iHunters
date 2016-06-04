<?php

namespace App\Model;

class Person_Page_Rank extends Model
{
    protected $fillable = ['Person_ID', 'Page_ID', 'Rank'];
    protected $guarded = [];
    protected $specialchars = [];
}