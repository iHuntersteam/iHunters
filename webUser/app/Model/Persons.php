<?php

namespace App\Model;

class Persons extends Model
{
    protected $fillable = ['Name'];
    protected $guarded = ['id'];
    protected $specialchars = ['Name'];
}