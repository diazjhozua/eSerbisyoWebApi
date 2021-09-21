<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = $this->whenLoaded('type');
        return [
            'id' => $this->id,
            'submitted_by' => ($this->is_anonymous ? 'Anonymous User' :  $this->user->getFullNameAttribute()),

            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id'  => !$type instanceof MissingValue && isset($this->type->id) ? $this->type->id : 0,
                'report_type'  => !$type instanceof MissingValue && isset($this->type->name) ? $this->type->name : NULL,
            ]),

            'custom_type' => $this->custom_type,
            'location_address' => $this->location_address,
            'landmark' => $this->landmark,
            'description' => $this->description,
            'is_urgent' => $this->is_urgent,
            'urgency_classification' => $this->urgency_classification,
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
