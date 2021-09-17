<?php

namespace App\Http\Resources;

use App\Models\AnnouncementPicture;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $announcement_type = $this->whenLoaded('announcement_type');
        $announcement_pictures = $this->whenLoaded('announcement_pictures');
        $comments = $this->whenLoaded('comments');
        return [
            'id' => $this->id,
            $this->mergeWhen($this->relationLoaded('announcement_type'), [
                'announcement_type_id'  => !$announcement_type instanceof MissingValue && isset($this->announcement_type->id) ? $this->announcement_type->id : NULL,
                'announcement_type'  => !$announcement_type instanceof MissingValue && isset($this->announcement_type->type) ? $this->announcement_type->type : NULL,
            ]),
            'title' => $this->title,
            'description' => $this->description,
            'announcement_pictures' => AnnouncementPictureResource::collection($announcement_pictures),
            'comments' => CommentResource::collection($comments),
            'comments_count' => $this->comments_count,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
