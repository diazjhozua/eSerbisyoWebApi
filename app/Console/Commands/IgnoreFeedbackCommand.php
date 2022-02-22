<?php

namespace App\Console\Commands;

use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Console\Command;

class IgnoreFeedbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ignore_feedback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marked feedbacks as ignored when seven days has been passed without the respond of administrator';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        activity()->disableLogging();
        Feedback::where('created_at', '<=', Carbon::now()->subDay(10)->toDateTimeString())->where('status', 'Pending')->each(function ($feedback) {
            $feedback->status = "Ignored";
            $feedback->save();
        });
    }
}
