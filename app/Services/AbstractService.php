<?php

namespace App\Services;

use Illuminate\Http\Request;

abstract class AbstractService implements ServiceInterface
{

    protected $validator;

    protected $repository;

    /**
     * @param ValidatorInterface $validator
     * @param AbstractRepository $repository
     */
    public function __construct($validator, $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
    }

    public function validateOnCreate(Request $request)
    {
        return $this->getValidator()->getValidationRulesOnCreate($request);
    }

    public function validateOnUpdate(Request $request)
    {
        return $this->getValidator()->getValidationRulesOnUpdate($request);
    }

    /**
     * @return ValidatorInterface
     */
    protected function getValidator()
    {
        return $this->validator;
    }

    /**
     * @return AbstractRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }

}
