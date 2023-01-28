<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'variant',
         'variant_id',
         'product_id'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class,);
    }
    public function prices()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }
}
