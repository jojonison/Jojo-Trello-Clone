<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TaskExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $export_id;
    /**
     * Create a new job instance.
     */
    public function __construct($user_id, $export_id)
    {
        $this->user_id = $user_id;
        $this->export_id = $export_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tasks = Task::where('user_id', $this->user_id)->get();
        Storage::put("public/task_export/{$this->export_id}.json", $tasks->toJson(JSON_PRETTY_PRINT));
    }
}
