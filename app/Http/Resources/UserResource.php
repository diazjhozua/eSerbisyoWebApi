<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);

        $data = [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            $this->mergeWhen($this->relationLoaded('user_role'), [
                'user_role'  => $this->user_role->id,
                'role'  => $this->user_role->role,
            ]),
            'is_verified' => $this->is_verified,
            'verification_status' => $this->is_verified == 1 ? 'Verified' : 'Unverified',
            'status' => $this->status == 'Enable' ? 'Enable Access' : 'Restricted Access',
            'latest_user_verification' => $this->whenLoaded('latest_user_verification'),
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // 'employees' => EmployeeResource::collection($employees),
        ];

        return $data;

    }
}
