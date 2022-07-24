<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uid','pid','totalAmount'
    ];

    public function scopeWhereArray($query, $array) {
        foreach ($array as $field =>$value) {
            $query -> where($field, $value);
        }
        return $query;
    }
    protected $casts = [
        'pid' => 'array'
    ];
}
