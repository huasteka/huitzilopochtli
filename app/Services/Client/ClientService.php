<?php

namespace App\Services\Client;

use App\Client;
use App\Services\Contactable\ContactableService;
use Illuminate\Http\Request;

/**
 * @method ClientValidator getValidator
 * @method ClientRepository getRepository
 */
final class ClientService extends ContactableService
{

    public function __construct(ClientValidator $validator, ClientRepository $repository)
    {
        parent::__construct($validator, $repository);
    }

    /**
     * @param Request $request
     * @return Client
     */
    public function create(Request $request)
    {
        return $this->getRepository()->create($request);
    }

    /**
     * @param Request $request
     * @param Client $client
     */
    public function update(Request $request, $client)
    {
        $this->getRepository()->update($request, $client);
    }

}
