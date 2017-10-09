<?php
namespace App;

final class DeliveryAddress extends Contactable
{

    const IS_DEFAULT = 'is_default';
    
    const RELATIONSHIP_CONTACTS = 'contacts';
    
    protected $fillable = [
        self::IS_DEFAULT,
    ];

    public function updateContact(Contact $contact)
    {
        $this->contacts()->first()->update($contact->attributesToArray());
    }

    public function isDefault()
    {
        return $this->getAttribute(self::IS_DEFAULT) === true;
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->contacts()->delete();
        });
    }
    
}
