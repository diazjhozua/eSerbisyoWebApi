<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $reports = $this->whenLoaded('reports');

        $data = [
            'id' => $this->id,
            'type' => $this->type,
            $this->mergeWhen(isset($this->reports_count), [
                'reports_count' => $this->reports_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
            $this->mergeWhen($this->relationLoaded('reports'), [
                'reports' => ReportResource::collection($reports),
            ]),
        ];

        if (isset($this->others)) {
            $data['reports'] = ReportResource::collection($this->others);
        }

        return $data;
    }
}
