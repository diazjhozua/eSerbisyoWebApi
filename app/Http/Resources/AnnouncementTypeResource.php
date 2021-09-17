<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $announcements = $this->whenLoaded('announcements');

        return [
            'id' => $this->id,
            'type' => $this->type,
            $this->mergeWhen(isset($this->announcements_count), [
                'announcements_count' => $this->announcements_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'announcements' => AnnouncementResource::collection($announcements),
        ];

    }
}
