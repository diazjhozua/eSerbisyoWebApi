<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVerificationResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'first_name' => $this->user->first_name,
            'middle_name' => $this->user->middle_name,
            'last_name' => $this->user->last_name,
            'address' => $this->user->address,
            'purok' => $this->user->purok,
            'picture_name' => $this->user->picture_name,
            'file_path' => $this->user->file_path,
            'credential_name' => $this->credential_name,
            'status' => $this->status,
            'admin_message' => $this->admin_message,
            'credential_file_path' => $this->credential_file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];

        return $data;
    }
}
