<?php

namespace App\Services\Contactable;

use App\Contact;
use App\Contactable;
use App\Services\AbstractService;
use Illuminate\Http\Request;

/**
 * @method ContactableValidator getValidator
 * @method ContactableRepository getRepository
 */
abstract class ContactableService extends AbstractService
{

    /**
     * @param ContactableValidator $validator
     * @param ContactableRepository $repository
     */
    public function __construct($validator, $repository)
    {
        parent::__construct($validator, $repository);
    }

    public function createContact(Request $request, Contactable $contactable)
    {
        return $this->getRepository()->createContact($request, $contactable);
    }

    public function updateContact(Request $request, Contact $contact)
    {
        $this->getRepository()->updateContact($request, $contact);
    }
    
    public function validateContact()
    {
        return $this->getValidator()->getValidationRulesForContact();
    }

}
