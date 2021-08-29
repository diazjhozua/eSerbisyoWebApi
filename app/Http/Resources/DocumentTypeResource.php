<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DocumentResource;
use Illuminate\Http\Resources\MissingValue;


class DocumentTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $documents = $this->whenLoaded('documents');

        return [
            'id' => $this->id,
            'type' => $this->type,
            $this->mergeWhen(isset($this->documents_count), [
                'documents_count' => $this->documents_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            $this->mergeWhen($this->relationLoaded('documents'), [
                'documents' => DocumentResource::collection($documents),
            ]),



        ];
    }
}
