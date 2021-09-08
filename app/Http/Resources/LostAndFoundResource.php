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
        $user = $this->whenLoaded('user');

        switch ($this->status) {
            case 1:
                $status = 'For Approval';
                break;
            case 2:
                $status = 'Approved';
                break;
            case 3:
                $status = 'Denied';
                break;
            case 4:
                $status = 'Resolved';
                break;
        }

        return [
            'id' => $this->id,
            'report_classification' => $this->report_type == 1 ? 'Missing Report' : 'Found Report',
            'item' => $this->item,
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'last_seen' => $this->last_seen,
            'description' => $this->description,
            'contact_information' => $this->contact_information,
            'status' => $this->status,
            'status_of_application' => $status,

            $this->mergeWhen($this->relationLoaded('user'), [
                'user_id' => $this->user_id,
                'submitted_by' => !$user instanceof MissingValue && isset($user) ? $this->user->getFullNameAttribute() : NULL,
            ]),

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
