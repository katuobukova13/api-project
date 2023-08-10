<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CleanUpTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:cleanup {--date_lte= : Date to clean tasks until}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old tasks';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $date = $this->option('date_lte') ?? Carbon::now()->subDays(30)->toDateString();

        Task::where('status', 'backlog')
            ->whereDate('created_at', '<=', $date)
            ->delete();

        $this->info("Tasks older than $date with 'backlog' status have been deleted.");
    }
}
