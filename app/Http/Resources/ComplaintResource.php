<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use App\Http\Resources\ComplainantResource;
use App\Http\Resources\DefendantResource;

class ComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $complainants = $this->whenloaded('complainants');
        $defendants = $this->whenloaded('defendants');
        $type = $this->whenLoaded('type');

        return [
            'id' => $this->id,
            'submitted_by' => $this->user->getFullNameAttribute(),

            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id'  => !$type instanceof MissingValue && isset($this->type->id) ? $this->type->id : 0,
                'type'  => !$type instanceof MissingValue && isset($this->type->name) ? $this->type->name : NULL,
            ]),

            'custom_type' => $this->custom_type,
            'reason' => $this->reason,
            'action' => $this->action,

            $this->mergeWhen(isset($this->complainants_count), [
                'complainants_count' => $this->complainants_count,
            ]),

            $this->mergeWhen($this->relationLoaded('complainants'), [
                'complainants' => ComplainantResource::collection($complainants),
            ]),

            $this->mergeWhen(isset($this->defendants_count), [
                'defendants_count' => $this->defendants_count,
            ]),

            $this->mergeWhen($this->relationLoaded('defendants'), [
                'defendants' => DefendantResource::collection($defendants),
            ]),

            'status' => $this->status,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
