<?php

namespace App\Models;

use App\Events\TaskTypeChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Listen for the saving event
        static::saving(function ($task) {
            // Check if the type has changed
            if ($task->isDirty('type')) {
                // Dispatch the TaskTypeChanged event
                event(new TaskTypeChanged($task));
            }
        });
    }
    public static string $CONTENT_TYPE = 'tasks';
    protected $fillable = [
        "user_id",
        "title" ,
        "description" ,
        "due_date" ,
        "type"
    ];
}
