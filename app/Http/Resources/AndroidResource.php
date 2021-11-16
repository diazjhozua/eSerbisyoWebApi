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
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
