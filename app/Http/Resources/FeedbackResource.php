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
        $feedback_type = $this->whenloaded('feedback_type');

        return [
            'id' => $this->id,
            $this->mergeWhen($this->relationLoaded('user'), [
                'submitted_by' => !$user instanceof MissingValue && isset($user) ?
                ($this->is_anonymous ? 'Anonymous User' :  $this->user->getFullNameAttribute()) :
                NULL,
            ]),
            $this->mergeWhen($this->relationLoaded('feedback_type'), [
                'feedback_type' => !$feedback_type instanceof MissingValue && isset($feedback_type) ? $this->feedback_type->type :  NULL,
            ]),

            'message' => $this->message,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];;
    }
}
