<?php
namespace App\Services;

use App\Contact;
use App\Delivery;
use App\DeliveryAddress;
use App\Merchandise;
use App\MerchandisePurchase;
use App\Purchase;
use Illuminate\Http\Request;


class PurchaseService extends AbstractService
{
    
    public function store(Request $request)
    {
        $purchase = new Purchase($this->readAttributesForPurchase($request));
        if ($purchase->save()) {
            $this->storeMerchandises($request, $purchase);
            $this->storeDelivery($request, $purchase);
        }
        return $purchase;
    }

    private function storeMerchandises(Request $request, Purchase $purchase)
    {
        if ($request->has('merchandises')) {
            foreach ($request->get('merchandises') as $merchandise) {
                $purchase->createMerchandise($merchandise['id'], $merchandise[MerchandisePurchase::QUANTITY], $merchandise[MerchandisePurchase::PURCHASE_PRICE]);
            }
        }
    }

    private function storeDelivery(Request $request, Purchase $purchase)
    {
        if ($request->has('delivery')) {
            $delivery = new Delivery($this->readAttributesForDelivery($request));
            $deliveryAddress = null;
            if ($request->has('delivery.delivery_address_id')) {
                $deliveryAddress = DeliveryAddress::find($request->input('delivery.delivery_address_id'));
            } else if ($request->has('delivery.delivery_address')) {
                $deliveryRequest = $this->readAttributesForDeliveryAddress($request);
                $deliveryAddress = new DeliveryAddress($deliveryRequest['delivery_address']);
                if ($deliveryAddress->save()) {
                    $deliveryAddress->createContactByAttributes($deliveryRequest['contact']);
                }
            }
            if (!is_null($deliveryAddress)) {
                $delivery->setAttribute('delivery_address_id', $deliveryAddress->getKey());
                $purchase->createDelivery($delivery);
            }
        }
    }

    /**
     * TODO Purchased merchandise and delivery information can be updated
     * 
     * @param Request $request
     * @param Purchase $purchase
     * @return null
     */
    public function update(Request $request, Purchase $purchase)
    {
        return null;
    }

    private function readAttributesForPurchase(Request $request)
    {
        return [
            Purchase::CODE => $request->get(Purchase::CODE),
            Purchase::DESCRIPTION => $request->get(Purchase::DESCRIPTION),
            Purchase::GROSS_VALUE => $request->get(Purchase::GROSS_VALUE, 0.00),
            Purchase::NET_VALUE => $request->get(Purchase::NET_VALUE),
            Purchase::DISCOUNT => $request->get(Purchase::DISCOUNT, 0.00),
        ];
    }

    private function readAttributesForDelivery(Request $request)
    {
        return [
            Delivery::SENT_AT => $request->input($this->property(Purchase::RELATIONSHIP_DELIVERY, Delivery::SENT_AT)),
            Delivery::DELIVERY_TIME => $request->input($this->property(Purchase::RELATIONSHIP_DELIVERY, Delivery::DELIVERY_TIME)),
            Delivery::ARRIVED_AT => $request->input($this->property(Purchase::RELATIONSHIP_DELIVERY, Delivery::ARRIVED_AT)),
        ];
    }

    private function readAttributesForDeliveryAddress(Request $request)
    {
        $contactList = $request->input($this->property('delivery', 'delivery_address', 'contacts'));
        $contactArray = array_pop($contactList);
        return [
            'delivery_address' => [
                DeliveryAddress::IS_DEFAULT => $request->input($this->property('delivery', 'delivery_address', DeliveryAddress::IS_DEFAULT))
            ],
            'contact' => [
                Contact::PHONE => $contactArray[Contact::PHONE],
                Contact::ADDRESS => $contactArray[Contact::ADDRESS],
                Contact::ADDRESS_COMPLEMENT => $contactArray[Contact::ADDRESS_COMPLEMENT],
                Contact::POSTAL_CODE => $contactArray[Contact::POSTAL_CODE],
                Contact::CITY => $contactArray[Contact::CITY],
                Contact::REGION => $contactArray[Contact::REGION],
                Contact::COUNTRY => $contactArray[Contact::COUNTRY],
            ]
        ];
    }

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Purchase::CODE => 'required|unique:purchases']);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Purchase::CODE => 'required|exists:purchases']);
    }

    private function getValidationRules(Request $request)
    {
        $purchaseRules = [
            Purchase::GROSS_VALUE => 'sometimes|required|min:0',
            Purchase::NET_VALUE => 'sometimes|required|min:0',
            Purchase::DISCOUNT => 'sometimes|required|min:0',
            Purchase::RELATIONSHIP_MERCHANDISES => 'required',
        ];
        return array_merge(
            $purchaseRules,
            $this->getValidationRulesForMerchandises($request),
            $this->getValidationRulesForDelivery($request)
        );
    }

    private function getValidationRulesForMerchandises(Request $request)
    {
        $rules = [];
        if ($request->has('merchandises')) {
            $rules = array_merge($rules, [
                $this->merchandiseProperty('id') => 'required|exists:merchandises,id',
                $this->merchandiseProperty(MerchandisePurchase::QUANTITY) => 'required|min:1',
                $this->merchandiseProperty(MerchandisePurchase::PURCHASE_PRICE) => 'required|min:0',
            ]);
        }
        return $rules;
    }

    private function merchandiseProperty($merchandiseProperty)
    {
        return $this->property(Purchase::RELATIONSHIP_MERCHANDISES, '*', $merchandiseProperty);
    }

    private function getValidationRulesForDelivery(Request $request)
    {
        $rules = [];
        if ($request->has('delivery')) {
            $rules = [
                $this->property(Purchase::RELATIONSHIP_DELIVERY, Delivery::SENT_AT) => 'sometimes|required|date',
                $this->property(Purchase::RELATIONSHIP_DELIVERY, Delivery::DELIVERY_TIME) => 'sometimes|required|min:1',
                $this->property(Purchase::RELATIONSHIP_DELIVERY, Delivery::ARRIVED_AT) => 'sometimes|required|date|after:sent_at',
            ];
            if ($request->has('delivery.delivery_address_id')) {
                $rules = array_merge($rules, [
                    $this->property(Purchase::RELATIONSHIP_DELIVERY, 'delivery_address_id') => 'sometimes|required|exists:delivery_addresses,id'
                ]);
            } else if ($request->has('delivery.delivery_address')) {
                $rules = array_merge($rules, [
                    $this->property(Purchase::RELATIONSHIP_DELIVERY, 'delivery_address', DeliveryAddress::IS_DEFAULT) => 'required|boolean',
                    $this->deliveryAddressProperty(Contact::PHONE) => 'required',
                    $this->deliveryAddressProperty(Contact::ADDRESS) => 'required',
                    $this->deliveryAddressProperty(Contact::ADDRESS_COMPLEMENT) => 'required',
                    $this->deliveryAddressProperty(Contact::POSTAL_CODE) => 'required',
                    $this->deliveryAddressProperty(Contact::CITY) => 'required',
                    $this->deliveryAddressProperty(Contact::REGION) => 'required',
                    $this->deliveryAddressProperty(Contact::COUNTRY) => 'required',
                ]);
            }
        }
        return $rules;
    }

    private function deliveryAddressProperty($contactProperty)
    {
        return $this->property(
            Purchase::RELATIONSHIP_DELIVERY,
            'delivery_address',
            DeliveryAddress::RELATIONSHIP_CONTACTS,
            '*',
            $contactProperty
        );
    }

}
