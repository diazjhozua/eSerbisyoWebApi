<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MissingItemResource extends JsonResource
{

    public function toArray($request)
    {
        $comments = $this->whenLoaded('comments');
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->getFullNameAttribute(),
            'user_picture_name' => $this->user->picture_name,
            'user_file_path' => $this->user->file_path,
            'user_role' => $this->user->user_role->role,
            'contact_id' => $this->contact_user_id,
            'contact_name' => $this->contact->getFullNameAttribute(),
            'contact_role' => $this->contact->user_role->role,
            'report_type' => $this->report_type,
            'item' => $this->item,
            'picture_name' => $this->picture_name,
            'file_path' => $this->file_path,
            'picture_src' => asset('storage/'.$this->file_path),
            'picture_link' => route('admin.viewFiles', [ 'folderName' => 'missing-pictures', 'fileName' => $this->picture_name ? $this->picture_name : 'asd',]),
            'credential_link' => route('admin.viewFiles', [ 'folderName' => 'credentials', 'fileName' => $this->credential_name ? $this->credential_name : 'asd',]),
            'credential_name' => $this->credential_name,
            'credential_path' => $this->credential_file_path,
            'last_seen' => $this->last_seen,
            'description' => $this->description,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'status' => $this->status,
            'admin_message' => $this->admin_message,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->created_at->format('Y-m-d H:i:s'),
            $this->mergeWhen(isset($this->comments_count), [
                'comments_count' => $this->comments_count,
            ]),
            'comments' => CommentResource::collection($comments),
        ];
    }
}
