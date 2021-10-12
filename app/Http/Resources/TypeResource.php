<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $documents = $this->whenLoaded('documents');
        $ordinances = $this->whenLoaded('ordinances');
        $projects = $this->whenLoaded('projects');
        $complaints = $this->whenLoaded('complaints');
        $reports = $this->whenLoaded('reports');
        $announcements = $this->whenLoaded('announcements');

        $data =  [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen(isset($this->documents_count), [
                'documents_count' => $this->documents_count,
            ]),
            $this->mergeWhen(isset($this->ordinances_count), [
                'ordinances_count' => $this->ordinances_count,
            ]),
            $this->mergeWhen(isset($this->projects_count), [
                'projects_count' => $this->projects_count,
            ]),
            $this->mergeWhen(isset($this->complaints_count), [
                'complaints_count' => $this->complaints_count,
            ]),
            $this->mergeWhen(isset($this->reports_count), [
                'reports_count' => $this->reports_count,
            ]),
            $this->mergeWhen(isset($this->announcements_count), [
                'announcements_count' => $this->announcements_count,
            ]),

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'documents' => DocumentResource::collection($documents),
            'ordinances' => OrdinanceResource::collection($ordinances),
            'projects' => ProjectResource::collection($projects),

            'complaints' => ComplaintResource::collection($complaints),
            'reports' => ReportResource::collection($reports),
            'announcements' => AnnouncementResource::collection($announcements),
        ];

        if (isset($this->others)) {
            if ($this->model_type === "Document") { $data['documents'] = DocumentResource::collection($this->others); }
            elseif($this->model_type === "Ordinance") { $data['ordinances'] = OrdinanceResource::collection($this->others);}
            elseif($this->model_type === "Project") { $data['projects'] = ProjectResource::collection($this->others);}
            elseif($this->model_type === "Complaint") { $data['complaints'] = ComplaintResource::collection($this->others);}
            elseif($this->model_type === "Report") { $data['reports'] = ReportResource::collection($this->others);}
            elseif($this->model_type === "Announcement") { $data['announcements'] = AnnouncementResource::collection($this->others);}

        }

        $data['created_at'] = $this->created_at->format('Y-m-d H:i:s');
        $data['updated_at'] = $this->updated_at->format('Y-m-d H:i:s');

        return $data;
    }
}
