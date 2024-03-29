<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class CertificateFormResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);

        $certificate = $this->whenLoaded('certificate');

        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            // 'submitted_by' => $this->user->getFullNameAttribute(),
            $this->mergeWhen($this->relationLoaded('certificate'), [
                'certificate_id'  => !$certificate instanceof MissingValue && isset($this->certificate->id) ? $this->certificate->id : NULL,
                'certificate'  => !$certificate instanceof MissingValue && isset($this->certificate->name) ? $this->certificate->name : NULL,
            ]),
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
        ];

        if ($this->certificate_id != 5) {
            $data['civil_status'] = $this->civil_status;
            $data['birthday'] = $this->birthday;

        }

        if ($this->certificate_id != 5 || $this->certificate_id != 4) {
            $data['citizenship'] = $this->citizenship;
        }

        if ($this->certificate_id == 2 || $this->certificate_id == 4) {
            $data['birthplace'] = $this->birthplace;
        }

        if ($this->certificate_id == 1 || $this->certificate_id == 3) {
            $data['purpose'] = $this->purpose;
        }

        if ($this->certificate_id == 2) {
            $data['profession'] = $this->profession;
            $data['height'] = $this->height;
            $data['weight'] = $this->weight;
            $data['sex'] = $this->sex;
            $data['cedula_type'] = $this->cedula_type;
            $data['tin_no'] = $this->tin_no;
            $data['icr_no'] = $this->icr_no;
            $data['basic_tax'] = $this->basic_tax;
            $data['additional_tax'] = $this->additional_tax;
            $data['gross_receipt_preceding'] = $this->gross_receipt_preceding;
            $data['gross_receipt_profession'] = $this->gross_receipt_profession;
            $data['real_property'] = $this->real_property;
            $data['interest'] = $this->interest;
        }

        if ($this->certificate_id == 4) {
            $data['contact_no'] = $this->contact_no;
            $data['contact_person'] = $this->contact_person;
            $data['contact_person_no'] = $this->contact_person_no;
            $data['contact_person_relation'] = $this->contact_person_relation;
        }

        if ($this->certificate_id == 5) {
            $data['business_name'] = $this->business_name;
        }

        // $data['signature_picture'] =  $this->signature_picture;
        // $data['file_path'] =  $this->file_path;
        $data['status'] =  $this->status;
        $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
        $data['updated_at'] = $this->updated_at->format('Y-m-d H:i:s');

        return $data;
    }
}
