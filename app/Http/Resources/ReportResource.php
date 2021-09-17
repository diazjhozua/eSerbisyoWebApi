<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($this->status) {
            case 1:
                $status = 'Pending';
                break;
            case 2:
                $status = 'Ignored';
                break;
            case 3:
                $status = 'Invalid';
                break;
            case 4:
                $status = 'Noted';
                break;
        }

        $user = $this->whenloaded('user');
        $report_type = $this->whenLoaded('report_type');
        return [
            'id' => $this->id,
            $this->mergeWhen($this->relationLoaded('user'), [
                'submitted_by' => !$user instanceof MissingValue && isset($user) ?
                ($this->is_anonymous ? 'Anonymous User' :  $this->user->getFullNameAttribute()) :
                NULL,
            ]),

            $this->mergeWhen($this->relationLoaded('report_type'), [
                'report_type_id' => !$report_type instanceof MissingValue && isset($report_type) ? $report_type->id : NULL,
                'report_type' => !$report_type instanceof MissingValue && isset($report_type) ? $report_type->type : 'Others-'.$this->custom_type,
            ]),

            'custom_type' => $this->custom_type,
            'location_address' => $this->location_address,
            'landmark' => $this->landmark,
            'description' => $this->description,
            'is_urgent' => $this->is_urgent,
            'urgent_status' => $this->is_urgent == 1 ? 'Urgent' :  'Nonurgent',
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'admin_message' => $this->admin_message,
            'status' => $this->status,
            'status_of_application' => $status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
