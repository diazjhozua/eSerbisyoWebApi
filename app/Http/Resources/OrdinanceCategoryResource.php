<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrdinanceResource;
class OrdinanceCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ordinances = $this->whenLoaded('ordinances');

        return [
            'id' => $this->id,
            'category' => $this->category,
            $this->mergeWhen(isset($this->ordinances_count), [
                'ordinances_count' => $this->ordinances_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            $this->mergeWhen($this->relationLoaded('ordinances'), [
                'ordinances' => OrdinanceResource::collection($ordinances),
            ]),



        ];
    }
}
