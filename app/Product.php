<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Product extends Model
{

    use SoftDeletes;

    const NAME = 'name';
    const CODE = 'code';
    const DESCRIPTION = 'description';

    const RELATIONSHIP_MERCHANDISES = 'merchandises';

    protected $fillable = [
        self::NAME, 
        self::CODE, 
        self::DESCRIPTION,
    ];

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class, 'product_id');
    }

    public function createMerchandise(array $attributes)
    {
        return $this->merchandises()->save(new Merchandise($attributes));
    }

    public function updateMerchandise(array $attributes)
    {
        return $this->merchandises()->first()->update($attributes);
    }

}
