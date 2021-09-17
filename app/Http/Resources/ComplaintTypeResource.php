<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ComplaintResource;

class ComplaintTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $complaints = $this->whenLoaded('complaints');

        $data = [
            'id' => $this->id,
            'type' => $this->type,
            $this->mergeWhen(isset($this->complaints_count), [
                'complaints_count' => $this->complaints_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
            $this->mergeWhen($this->relationLoaded('complaints'), [
                'complaints' => ComplaintResource::collection($complaints),
            ]),


        ];

        if (isset($this->others)) {
            $data['complaints'] = ComplaintResource::collection($this->others);
        }

        return $data;
    }
}
