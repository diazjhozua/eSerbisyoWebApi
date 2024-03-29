<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class FeedbackResource extends JsonResource
{
    public function toArray($request)
    {
        $type = $this->whenloaded('type');

        return [
            'id' => $this->id,
            'submitted_by' => ($this->is_anonymous ? 'Anonymous User' :  $this->user->getFullNameAttribute()),
            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id' => !$type instanceof MissingValue && isset($type) ? $this->type->id : 0,
                'type' => !$type instanceof MissingValue && isset($type) ? $this->type->name : NULL,
            ]),
            'custom_type' => $this->custom_type,
            'rating' => $this->rating,
            'message' => $this->message,
            'admin_respond' => $this->admin_respond == null ? 'Not yet responded' : $this->admin_respond,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];;
    }
}
