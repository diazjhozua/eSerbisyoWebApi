<?php

namespace App\Console\Commands;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;

class IgnoreOldUrgentReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:ignore_urgent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Report::where([['created_at', '<=', Carbon::now()->subMinutes(60)->toDateTimeString()], ['status', '=', 1], ['is_urgent', '=', 1]])->each(function ($report) {
            $report->status = 2; //ignored
            $report->save();
        });
    }
}
