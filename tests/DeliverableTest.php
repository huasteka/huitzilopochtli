<?php

abstract class DeliverableTest extends TestCase
{

    /**
     * @param $amount
     * @return Illuminate\Database\Eloquent\Collection
     */
    protected function createMerchandises($amount)
    {
        $merchandiseList = factory(App\Merchandise::class)->times($amount)->make();
        /* @var $merchandise App\Merchandise */
        foreach ($merchandiseList as $merchandise) {
            $merchandise->setAttribute('product_id', factory(App\Product::class)->create()->getKey());
            $resultSet[] = $merchandise->save();
        }
        return $merchandiseList;
    }

    /**
     * @return App\Delivery
     */
    protected function createDelivery()
    {
        $deliveryAddress = factory(App\DeliveryAddress::class)->create();
        $deliveryAddress->contacts()->save(factory(App\Contact::class)->make());
        $delivery = factory(App\Delivery::class)->make();
        $delivery->setAttribute('delivery_address_id', $deliveryAddress->getKey());
        return $delivery;
    }

    /**
     * @return App\Delivery
     */
    protected function createDeliveryWithAddressAndContacts()
    {
        $deliveryAddress = factory(App\DeliveryAddress::class)->make();
        $deliveryAddress->setAttribute('contacts', [factory(App\Contact::class)->make()]);
        $delivery = factory(App\Delivery::class)->make();
        $delivery->setAttribute('delivery_address', $deliveryAddress);
        return $delivery;
    }

}
