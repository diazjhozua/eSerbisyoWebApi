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
        $type = $this->whenLoaded('type');
        $announcement_pictures = $this->whenLoaded('announcement_pictures');
        $comments = $this->whenLoaded('comments');

        return [
            'id' => $this->id,
            $this->mergeWhen($this->relationLoaded('type'), [
                'type_id'  => !$type instanceof MissingValue && isset($this->type->id) ? $this->type->id : 0,
                'announcement_type'  => !$type instanceof MissingValue && isset($this->type->name) ? $this->type->name : NULL,
            ]),
            'custom_type' => $this->custom_type,
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
