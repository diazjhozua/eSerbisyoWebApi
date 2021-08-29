<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Facades\Auth;

class MissingPersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->whenLoaded('user');
        return [
            'id' => $this->id,
            'missing_name' => $this->name,
            'height' => $this->height,
            'weight' => $this->weight,
            'age' => $this->age,
            'eyes' => $this->eyes,
            'hair' => $this->hair,
            'unique_sign' => $this->unique_sign,
            'important_information' => $this->important_information,
            'last_seen' => $this->last_seen,
            'contact_information' => $this->contact_information,
            //this should be check whether the user is in adminisdrative role
            'is_resolved' => $this->is_resolved,
            'is_approved' => $this->is_resolved,
            // end
            $this->mergeWhen($this->relationLoaded('user'), [
                'user_id' => $this->user_id,
                'submitted_by' => !$user instanceof MissingValue && isset($user) ?
                ($this->is_anonymous ? 'Anonymous User' :  $this->user->getFullNameAttribute()) :
                NULL,
            ]),
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
