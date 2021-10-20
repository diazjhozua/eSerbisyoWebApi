<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EmployeeResource;

class PositionResource extends JsonResource
{
    public function toArray($request)
    {
        $employees = $this->whenLoaded('employees');

        $data = [
            'id' => $this->id,
            'ranking' => $this->ranking,
            'name' => $this->name,
            'job_description' => $this->job_description,
            $this->mergeWhen(isset($this->employees_count), [
                'employees_count' => $this->employees_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'employees' => EmployeeResource::collection($employees),
        ];

        if (isset($this->others)) {
            $data['employees'] = EmployeeResource::collection($this->others);
        }

        return $data;
    }
}
