<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelReady\LicenseServer\Traits\Licensable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Licensable;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
    ];
}
