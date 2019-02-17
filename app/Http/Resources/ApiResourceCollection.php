<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class ApiResourceCollection extends ResourceCollection
{
    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $data = (object) array_merge(array("status" => 200), (array) $response->getData());
        $response->setData($data);
    }
}
