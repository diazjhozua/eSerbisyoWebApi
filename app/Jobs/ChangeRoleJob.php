<?php

namespace App\Jobs;

use App\Mail\ChangeRoleMail;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ChangeRoleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $subject;
    protected $userRole;

    public function __construct(User $user, $subject, UserRole $userRole)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->userRole = $userRole;
    }

    public function handle()
    {
        Mail::to($this->user->email)
            ->send(new ChangeRoleMail($this->subject, $this->userRole));
    }
}
