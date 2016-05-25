<?php

namespace App\Model;

class PersonPageRank extends Model
{
    protected $fillable = ['PersonID', 'PageID', 'Rank'];
    protected $guarded = [];
    protected $specialchars = [];
}