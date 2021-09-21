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
            $this->mergeWhen(isset($this->positive_count), [
                'positive' => $this->feedbacks_count ? number_format(round($this->positive_count * 100 / $this->feedbacks_count),0,'.','') . '%' : '0%',
            ]),
            $this->mergeWhen(isset($this->neutral_count), [
                'neutral' => $this->feedbacks_count ? number_format(round($this->neutral_count * 100 / $this->feedbacks_count),0,'.','') . '%' : '0%',
            ]),
            $this->mergeWhen(isset($this->negative_count), [
                'negative' => $this->feedbacks_count ? number_format(round($this->negative_count * 100 / $this->feedbacks_count),0,'.','') . '%' : '0%',
            ]),
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
