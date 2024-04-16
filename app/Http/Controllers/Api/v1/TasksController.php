<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ApiController;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TasksController extends ApiController
{
    public function index(Request $request){
        //get user tasks
        $tasks = auth()->user()->tasks;
        if (empty($tasks)) {
            return $this->response(404);
        }

        return $this->response(200, $tasks);
    }

    public function show(Request $request, $id){
        //check id validation
        if (empty($id) || !is_numeric($id) || $id < 1){
            return $this->response(404);
        }

        //find task from id
        $task = auth()->user()->tasks()->where('id', $id)->first();
        if (empty($task)) {
            return $this->response(404);
        }

        return $this->response(200, $task);
    }

    public function store(Request $request){
        //validate post request
        $validator = Validator::make($request->all(), [
            'title'         => 'required|max:255',
            'description'   => 'required',
            'due_date'      => 'required|date_format:Y/d/m H:i',
            'type'          => 'required|in:backlog,in_progress,testing,complete',
        ]);
        if($validator->fails()){
            return $this->response(400, $validator->messages());
        }

        //create new task
        $task = Task::create([
            'user_id'       => auth()->user()->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'due_date'      => $request->due_date,
            'type'          => $request->type,

        ]);
        if (empty($task)){
            return $this->response(400, 'Failed to create task.');
        }

        return $this->response(200, $task);
    }

    public function update(Request $request, $id){
        //check id validation
        if (empty($id) || !is_numeric($id) || $id < 1){
            return $this->response(404);
        }

        //find task from id
        $task = auth()->user()->tasks()->where('id', $id)->first();
        if (empty($task)) {
            return $this->response(404);
        }

        //validate post request
        $validator = Validator::make($request->all(), [
            'title'         => 'max:255',
            'due_date'      => 'date_format:Y/d/m H:i',
            'type'          => 'in:backlog,in_progress,testing,complete',
        ]);
        if($validator->fails()){
            return $this->response(400, $validator->messages());
        }

        //update task if now empty each request
        if (!$task->update([
            'title'         => $request->title ?? $task->title,
            'description'   => $request->description ?? $task->description,
            'due_date'      => $request->due_date ?? $task->due_date,
            'type'          => $request->type ?? $task->type,
        ])) {
            return $this->response(500);
        }

        return $this->response(200, $task);
    }

    public function destroy($id){
        //check id validation
        if (empty($id) || !is_numeric($id) || $id < 1){
            return $this->response(404);
        }

        //find task from id
        $task = auth()->user()->tasks()->where('id', $id)->first();
        if (empty($task)) {
            return $this->response(404);
        }

        //delete user task
        $task->delete();
        return $this->response(200, $task);
    }

}
