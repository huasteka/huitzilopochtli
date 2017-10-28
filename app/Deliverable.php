<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Deliverable extends Model
{

    use SoftDeletes;

    const CODE = 'code';
    const DESCRIPTION = 'description';
    const GROSS_VALUE = 'gross_value';
    const NET_VALUE = 'net_value';
    const DISCOUNT = 'discount';

    const RELATIONSHIP_MERCHANDISES = 'merchandises';
    const RELATIONSHIP_DELIVERY = 'delivery';

    /**
     * @return BelongsToMany
     */
    abstract public function merchandises();

    public function delivery()
    {
        return $this->morphOne(Delivery::class, 'deliverable');
    }

    public function createDelivery(Delivery $delivery)
    {
        $this->delivery()->save($delivery);
    }

}
