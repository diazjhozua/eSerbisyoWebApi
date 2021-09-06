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

        $user = $this->whenloaded('user');
        $complainants = $this->whenloaded('complainants');
        $defendants = $this->whenloaded('defendants');
        $complaint_type = $this->whenLoaded('complaint_type');
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
            $this->mergeWhen($this->relationLoaded('user'), [
                'submitted_by' => !$user instanceof MissingValue && isset($user) ? $this->getFullNameAttribute() : NULL,
            ]),

            $this->mergeWhen($this->relationLoaded('complaint_type'), [
                'complaint_type_id'  => !$complaint_type instanceof MissingValue && isset($this->complaint_type->id) ? $this->complaint_type->id : NULL,
                'complaint_type'  => !$complaint_type instanceof MissingValue && isset($this->complaint_type->type) ? $this->complaint_type->type : 'Others-'.$this->custom_type,
            ]),

            'reason' => $this->reason,
            'action' => $this->action,

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
            'status_of_application' => $status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
