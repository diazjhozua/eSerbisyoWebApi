<?php

namespace App\Console\Commands;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldPicturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old_pictures';

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
        Report ::where([['created_at', '<', Carbon::now()->subDays(30)->toDateTimeString()], ['status', '!=', 1]])->each(function ($report) {
            Storage::delete('public/reports/'. $report->picture_name);
            $report->picture_name = NULL; //ignored
            $report->file_path = NULL;
            $report->save();
        });
    }
}
