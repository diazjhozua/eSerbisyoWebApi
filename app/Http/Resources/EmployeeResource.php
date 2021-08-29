<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $term = $this->whenloaded('term');
        $position = $this->whenloaded('position');
        return [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen($this->relationLoaded('term'), [
                'term_id' => !$term instanceof MissingValue && isset($term) ? $term->id : NULL,
                'term' => !$term instanceof MissingValue && isset($term) ? $term->term.' ('.$term->year_start.'-'.$term->year_end.')' : NULL,
            ]),

            $this->mergeWhen($this->relationLoaded('position'), [
                'position_id' => !$position instanceof MissingValue && isset($position) ? $position->id : NULL,
                'position' => !$position instanceof MissingValue && isset($position) ? $position->position : NULL,
            ]),
            'description' => $this->description,
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
