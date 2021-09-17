<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        $user = $this->whenloaded('user');
        $type = $this->whenloaded('type');

        return [
            'id' => $this->id,
            'submitted_by' => !$user instanceof MissingValue && isset($user) ? ($this->is_anonymous ? 'Anonymous User' :  $this->user->getFullNameAttribute()) : NULL,
            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id' => !$type instanceof MissingValue && isset($type) ? $this->type->id : NULL,
                'type' => !$type instanceof MissingValue && isset($type) ? $this->type->name : NULL,
            ]),
            'custom_type' => $this->custom_type,
            'polarity' => $this->polarity,
            'message' => $this->message,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];;
    }
}
