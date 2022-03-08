<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InquiryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = $this->whenloaded('type');

        return [
            'id' => $this->id,
            'submitted_by' => $this->user->getFullNameAttribute(),
            'about' => $this->about,
            'message' => $this->message,
            'admin_message' => $this->admin_message == null ? 'Not yet responded' : $this->admin_message,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
