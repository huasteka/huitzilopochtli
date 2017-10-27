<?php

namespace App\Services\Client;

use App\Client;
use App\Contact;
use App\Services\Contactable\ContactableRepository;
use Illuminate\Http\Request;

/**
 * @method ClientRequestReader getRequestReader
 */
class ClientRepository extends ContactableRepository
{

    public function __construct(ClientRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    public function create(Request $request)
    {
        $client = new Client($this->getRequestReader()->readAttributes($request, Client::class));
        if ($client->save() && $this->hasContacts($request)) {
            $client->createContacts($this->getRequestReader()->readCollection($request, Contact::class));
        }
        return $client;
    }

    public function update(Request $request, Client $client)
    {
        $client->update($this->getRequestReader()->readAttributes($request, Client::class));
    }

}
