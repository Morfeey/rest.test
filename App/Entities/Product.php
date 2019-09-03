<?php


namespace App\Entities;


use Helpers\Architecture\Entity;

class Product extends Entity
{
    public static $fillable = ['name', 'price'];
}