<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequirementResource extends JsonResource
{
    public function toArray($request)
    {
        $certificates = $this->whenLoaded('certificates');

        return [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen(isset($this->certificates_count), [
                'certificates_count' => $this->certificates_count,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'certificates' => CertificateResource::collection($certificates),
        ];
    }
}
