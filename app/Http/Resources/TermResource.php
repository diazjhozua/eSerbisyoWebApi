<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EmployeeResource;

class TermResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $employees = $this->whenLoaded('employees');

        return [
            'id' => $this->id,
            'term' => $this->term,
            $this->mergeWhen(isset($this->employees_count), [
                'employees_count' => $this->employees_count,
            ]),
            'year_start' => $this->year_start,
            'year_end' => $this->year_end,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'employees' => EmployeeResource::collection($employees),
        ];
    }
}
