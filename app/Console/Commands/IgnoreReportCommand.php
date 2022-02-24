<?php

namespace App\Console\Commands;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;

class IgnoreReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ignore_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marked reports as ignored when day has been passed without the respond of administrator';

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
        Report::where('created_at', '<=', Carbon::now()->subDay(1)->toDateTimeString())->where('status', 'Pending')->each(function ($report) {
            $report->status = "Ignored";
            $report->save();
        });
    }
}
