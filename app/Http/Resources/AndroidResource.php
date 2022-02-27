<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AndroidResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'description' => $this->description,
            'url' => $this->url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
