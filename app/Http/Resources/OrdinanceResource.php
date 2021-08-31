<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
                'ordinance_category_id'  => isset($ordinance_category) ? $this->ordinance_category->id : null,
                'ordinance_category'  => isset($ordinance_category) ? $this->ordinance_category->category : null,
            ]),
            'pdf_name' => $this->pdf_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
