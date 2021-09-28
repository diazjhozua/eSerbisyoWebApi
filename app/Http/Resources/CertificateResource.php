<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $requirements = $this->whenLoaded('requirements');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'status' => $this->status,
            'delivery_option' => $this->is_open_delivery == 1 ? 'Open for delivery' : 'Walkin only',
            'delivery_fee' => $this->delivery_fee,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            $this->mergeWhen(isset($this->requirements_count), [
                'requirements_count' => $this->requirements_count,
            ]),
            'requirements' => RequirementResource::collection($requirements),
        ];
    }
}
