<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Resources\MissingValue;


class ProjectTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $projects = $this->whenLoaded('projects');

        return [
            'id' => $this->id,
            'type' => $this->type,
            $this->mergeWhen(isset($this->projects_count), [
                'projects_count' => $this->projects_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            $this->mergeWhen($this->relationLoaded('projects'), [
                'projects' => ProjectResource::collection($projects),
            ]),
        ];

    }
}
