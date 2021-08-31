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
        $ordinance_category = $this->whenLoaded('ordinance_category');

        return [
            'id' => $this->id,
            'ordinance_no' => $this->ordinance_no,
            'title' => $this->title,
            'date_approved' => $this->date_approved,

            $this->mergeWhen($this->relationLoaded('ordinance_category'), [
                'ordinance_category_id' => !$ordinance_category instanceof MissingValue && isset($ordinance_category) ? $ordinance_category->id : NULL,
                'ordinance_category' => !$ordinance_category instanceof MissingValue && isset($ordinance_category) ? $ordinance_category->category : NULL,
            ]),

            'pdf_name' => $this->pdf_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
