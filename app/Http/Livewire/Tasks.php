<?php

namespace App\Http\Livewire;
use App\Models\Task;
use Livewire\Component;
use Livewire\Event;

class Tasks extends Component
{
    public $tasks, $name, $description, $status, $task_id;
    public $updateMode = false;

    protected $rules = [
        'name' => 'required|min:6',
        'description' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function render()
    {
        $this->tasks = Task::where('team_id', auth()->user()->currentTeam->id)->get();
        return view('livewire.tasks');
    }
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields(){
        $this->name = '';
        $this->description = '';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function store()
    {
        if (auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'create')) {
            $this->validate();
            $task = new Task;
            $task->team_id = auth()->user()->currentTeam->id;
            $task->name = $this->name;
            $task->description = $this->description;
            $task->save();

            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'message' => 'Task Created Successfully!']);

            $this->resetInputFields();
        } else {
            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'message' => 'You do not have permissions to add tasks to this team!']);
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function edit($id)
    {
        if (auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update')) {
            $task = Task::findOrFail($id);
            $this->task_id = $id;
            $this->name = $task->name;
            $this->description = $task->description;

            $this->updateMode = true;

        } else {
            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'message' => 'You do not have permissions to edit tasks!']);
        }

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function update()
    {
        if (auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update')) {
            $validatedDate = $this->validate([
                'name' => 'required',
                'description' => 'required',
            ]);

            $task = Task::find($this->task_id);
            $task->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $this->updateMode = false;

            $this->resetInputFields();
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'message' => 'Task Updated Successfully!']);
        } else {
            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'message' => 'You do not have permissions to edit tasks!']);
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        if (auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'delete')) {
            Task::find($id)->delete();
            $this->dispatchBrowserEvent('notification', ['type' => 'warning', 'message' => 'You have deleted the task!']);
        } else {
            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'message' => 'You do not have permissions to delete tasks!']);
        }
    }

    public function assign($id)
    {
        $task = Task::find($id);
        if (!$task->user_id) {
            $task->user_id = auth()->user()->id;
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'message' => 'You have assigned the task to yourself!']);
        } elseif($task->user_id != auth()->user()->id) {
            $task->user_id = auth()->user()->id;
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'message' => 'You have assigned the task to yourself!']);
        } else {
            $task->user_id = NULL;
            $this->dispatchBrowserEvent('notification', ['type' => 'notice', 'message' => 'You have unassigned the task!']);
        }
        $task->save();
    }
}
