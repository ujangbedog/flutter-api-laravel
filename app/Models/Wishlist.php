<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $fillable = [
        'productId',
        'userId',
    ];

    public function scopeWhereArray($query, $array) {
        foreach ($array as $field =>$value) {
            $query -> where($field, $value);
        }
        return $query;
    }
}
