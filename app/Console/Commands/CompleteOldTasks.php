<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class CompleteOldTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:complete-old-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change old task to complete type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // find tasks created more than two days ago
        $twoDaysAgo = Carbon::now()->subDays(2);
        $oldTasks = Task::where('created_at', '<', $twoDaysAgo)->get();

        // loop  each old task and change to complete
        foreach ($oldTasks as $task) {
            $task->update(['type' => 'complete']);
        }

        $this->info('old tasks change to  complete.');
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tasks:complete-old')->daily();
    }
}
