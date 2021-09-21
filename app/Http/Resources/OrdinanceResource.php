<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class OrdinanceResource extends JsonResource
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
            'ordinance_no' => $this->ordinance_no,
            'title' => $this->title,
            'date_approved' => $this->date_approved,

            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id'  => !$type instanceof MissingValue && isset($this->type->id) ? $this->type->id : 0,
                'ordinance_type'  => !$type instanceof MissingValue && isset($this->type->name) ? $this->type->name : NULL,
            ]),

            'custom_type' => $this->custom_type,
            'pdf_name' => $this->pdf_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
