<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use App\Http\Resources\ComplainantResource;
use App\Http\Resources\DefendantResource;

class ComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $complainants = $this->whenloaded('complainants');
        $defendants = $this->whenloaded('defendants');
        $type = $this->whenLoaded('type');

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->getFullNameAttribute(),
            'user_role' => $this->user->user_role->role,

            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id'  => !$type instanceof MissingValue && isset($this->type->id) ? $this->type->id : 0,
                'complaint_type'  => !$type instanceof MissingValue && isset($this->type->name) ? $this->type->name : NULL,
            ]),


            'contact_id' => $this->contact_user_id,
            'contact_name' => $this->contact->getFullNameAttribute(),
            'contact_role' => $this->contact->user_role->role,
            'custom_type' => $this->custom_type,
            'reason' => $this->reason,
            'action' => $this->action,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'status' => $this->status,
            'admin_message' => $this->admin_message,

            $this->mergeWhen(isset($this->complainants_count), [
                'complainants_count' => $this->complainants_count,
            ]),

            $this->mergeWhen($this->relationLoaded('complainants'), [
                'complainants' => ComplainantResource::collection($complainants),
            ]),

            $this->mergeWhen(isset($this->defendants_count), [
                'defendants_count' => $this->defendants_count,
            ]),

            $this->mergeWhen($this->relationLoaded('defendants'), [
                'defendants' => DefendantResource::collection($defendants),
            ]),

            'status' => $this->status,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
