<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Facades\Auth;

class LostAndFoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'submitted_by' => $this->user->getFullNameAttribute(),
            'report_type' => $this->report_type.' Report',
            'item' => $this->item,
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'last_seen' => $this->last_seen,
            'description' => $this->description,
            'contact_information' => $this->contact_information,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
