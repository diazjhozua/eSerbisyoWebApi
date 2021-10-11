<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class CertificateFormResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);

        $certificate = $this->whenLoaded('certificate');

        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'submitted_by' => $this->user->getFullNameAttribute(),
            $this->mergeWhen($this->relationLoaded('certificate'), [
                'certificate_id'  => !$certificate instanceof MissingValue && isset($this->certificate->id) ? $this->certificate->id : NULL,
                'certificate'  => !$certificate instanceof MissingValue && isset($this->certificate->name) ? $this->certificate->name : NULL,
            ]),
            'name' => $this->name,
            'address' => $this->address,
        ];

        if ($this->certificate_id === 1 || $this->certificate_id === 3) {
            $data['purpose'] = $this->purpose;
        }

        if ($this->certificate_id === 2) {
            $data['birthday'] = $this->birthday;
            $data['birthplace'] = $this->birthplace;
            $data['citizenship'] = $this->citizenship;
            $data['civil_status'] = $this->civil_status;
        }

        if ($this->certificate_id === 4) {
            $data['contact_no'] = $this->contact_no;
            $data['contact_person'] = $this->contact_person;
            $data['contact_person_no'] = $this->contact_person_no;
            $data['contact_person_relation'] = $this->contact_person_relation;
        }

        if ($this->certificate_id === 5) {
            $data['business_name'] = $this->business_name;
        }

        $data['signature_picture'] =  $this->signature_picture;
        $data['file_path'] =  $this->file_path;
        $data['status'] =  $this->status;
        $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
        $data['updated_at'] = $this->updated_at->format('Y-m-d H:i:s');

        return $data;
    }
}
