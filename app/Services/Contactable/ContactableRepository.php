<?php

namespace App\Services\Contactable;

use App\Contact;
use App\Contactable;
use App\Services\AbstractRepository;
use Illuminate\Http\Request;

/**
 * @method ContactableRequestReader getRequestReader
 */
abstract class ContactableRepository extends AbstractRepository
{

    use ContactableRequestChecker;
    
    /**
     * @param ContactableRequestReader $requestReader
     */
    public function __construct($requestReader)
    {
        parent::__construct($requestReader);
    }

    public function createContact(Request $request, Contactable $model)
    {
        $model->createContactByAttributes($this->getRequestReader()->readAttributes($request, Contact::class));
    }

    public function updateContact(Request $request, Contact $contact)
    {
        $contact->update($this->getRequestReader()->readAttributes($request, Contact::class));
    }

}
