<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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

        $data =  [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen(isset($this->documents_count), [
                'documents_count' => $this->documents_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            $this->mergeWhen($this->relationLoaded('documents'), [
                'documents' => DocumentResource::collection($documents),
            ]),
        ];

        if (isset($this->others)) {
            $data['documents'] = DocumentResource::collection($this->others);
        }

        $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
        $data['updated_at'] = $this->updated_at->format('Y-m-d H:i:s');

        return $data;
    }
}
