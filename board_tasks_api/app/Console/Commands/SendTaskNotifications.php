<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\TaskNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskDueNotification;
use App\Models\NotificationSetting;



class SendTaskNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for tasks close to due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {

         $today = Carbon::today();

        $tasks = Task::with('user')->where('completed', false)->get();

        foreach ($tasks as $task) {

            $setting = NotificationSetting::where('user_id', $task->user_id)->first();

            if (!$setting) {
                continue;
            }

            $daysBefore = $setting->days_before;

            $notificationDate = Carbon::parse($task->due_date)->subDays($daysBefore);

            if (!$today->equalTo($notificationDate)) {
                continue;
            }

            $alreadySent = TaskNotification::where('task_id', $task->id)
                ->where('days_before', $daysBefore)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            Mail::to($task->user->email)
                ->send(new TaskDueNotification($task));

            TaskNotification::create([
                'task_id' => $task->id,
                'user_id' => $task->user_id,
                'days_before' => $daysBefore,
                'sent_at' => now()
            ]);

            $this->info("Notification sent for task {$task->id}");
        }

        return Command::SUCCESS;

    }
}
