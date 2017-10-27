<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ServiceInterface
{
    /**
     * @param Request $request
     * @return Model
     */
    function create(Request $request);

    /**
     * @param Request $request
     * @param Model $model
     * @return void
     */
    function update(Request $request, $model);

    /**
     * @param Request $request
     * @return array
     */
    function validateOnCreate(Request $request);

    /**
     * @param Request $request
     * @return array
     */
    function validateOnUpdate(Request $request);

}
