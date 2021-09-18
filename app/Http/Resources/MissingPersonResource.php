<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class MissingPersonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'submitted_by' => $this->user->getFullNameAttribute(),
            'report_type' => $this->report_type.' Report',
            'missing_name' => $this->name,
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'height' => $this->height,
            'height_unit' => $this->height_unit,
            'weight' => $this->weight,
            'weight_unit' => $this->weight_unit,
            'age' => $this->age,
            'eyes' => $this->eyes,
            'hair' => $this->hair,
            'unique_sign' => $this->unique_sign,
            'important_information' => $this->important_information,
            'last_seen' => $this->last_seen,
            'contact_information' => $this->contact_information,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
