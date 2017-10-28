<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class Contactable extends Model
{

    const RELATIONSHIP_CONTACTS = 'contacts';

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function createContacts(array $contactCollection)
    {
        return $this->contacts()->saveMany($contactCollection);
    }

    public function createContact(Contact $contact)
    {
        return $this->contacts()->save($contact);
    }

    public function createContactByAttributes(array $attributes)
    {
        return $this->createContact(new Contact($attributes));
    }

}
