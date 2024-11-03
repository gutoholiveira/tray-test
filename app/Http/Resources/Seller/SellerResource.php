<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
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
            'name'       => $this->name,
            'email'      => $this->email,
            'created_at' => !empty($this->created_at) ? date("Y-m-d H:i:s", strtotime($this->created_at)) : '',
            'updated_at' => !empty($this->updated_at) ? date("Y-m-d H:i:s", strtotime($this->updated_at)) : ''
        ];
    }
}
