<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $document_type = $this->whenLoaded('document_type');

        return [
            'id' => $this->id,
            $this->mergeWhen($this->relationLoaded('document_type'), [
                'document_type_id'  => isset($document_type) ? $this->document_type->id : null,
                'document_type'  => isset($document_type) ? $this->document_type->type : null,
            ]),
            'description' => $this->description,
            'year' => $this->year,
            'pdf_name' => $this->pdf_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];

    }
}
