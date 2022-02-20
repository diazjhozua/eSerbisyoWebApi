<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $feedbacks = $this->whenLoaded('feedbacks');
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen(isset($this->feedbacks_count), [
                'feedbacks_count' => $this->feedbacks_count,
            ]),
            'ratings' => $this->ratings,
            'feedbacks' => FeedbackResource::collection($feedbacks),
        ];

        if (isset($this->others)) {
            $data['feedbacks'] = FeedbackResource::collection($this->others);
        }

        $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
        $data['updated_at'] = $this->updated_at->format('Y-m-d H:i:s');

        return $data;
    }
}
