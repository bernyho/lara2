<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function setName(string $name): void
    {
        $this->attributes['name'] = $name;
    }

    public function setHash(string $hash): void
    {
        $this->attributes['hash'] = $hash;
    }

}
