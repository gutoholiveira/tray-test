<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'seller_id'  => $this->seller_id,
            'value'      => $this->value / 100,
            'commission' => $this->commission / 100,
            'date'       => $this->date,
            'seller'     => $this->seller,
            'created_at' => !empty($this->created_at) ? date("Y-m-d H:i:s", strtotime($this->created_at)) : '',
            'updated_at' => !empty($this->updated_at) ? date("Y-m-d H:i:s", strtotime($this->updated_at)) : ''
        ];
    }
}
