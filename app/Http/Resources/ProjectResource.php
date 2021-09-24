<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;


class ProjectResource extends JsonResource
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
            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id'  => !$type instanceof MissingValue && isset($this->type->id) ? $this->type->id : 0,
                'project_type'  => !$type instanceof MissingValue && isset($this->type->name) ? $this->type->name : NULL,
            ]),
            'name' => $this->name,
            'description' => $this->description,
            'cost' => $this->cost,
            'project_start' => $this->project_start,
            'project_end' => $this->project_end,
            'location' => $this->location,
            'pdf_name' => $this->pdf_name,
            'file_path' => $this->file_path,
            'is_starting'=> $this->is_starting,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
          
        ];
    }
}
