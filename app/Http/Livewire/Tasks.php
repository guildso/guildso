<?php

namespace App\Http\Livewire;
use App\Models\Task;
use Livewire\Component;

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
        $this->validate();
        $task = new Task;
        $task->team_id = auth()->user()->currentTeam->id;
        $task->name = $this->name;
        $task->description = $this->description;
        $task->save();

        session()->flash('message', 'Task Created Successfully.');

        $this->resetInputFields();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->task_id = $id;
        $this->name = $task->name;
        $this->description = $task->description;

        $this->updateMode = true;
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
  
        session()->flash('message', 'Task Updated Successfully.');
        $this->resetInputFields();
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        Task::find($id)->delete();
        session()->flash('message', 'Task Deleted Successfully.');
    }

    public function assign($id)
    {
        $task = Task::find($id);
        if (!$task->user_id) {
            $task->user_id = auth()->user()->id;
        } elseif($task->user_id != auth()->user()->id) {
            $task->user_id = auth()->user()->id;
        } else {
            $task->user_id = NULL;
        }
        $task->save();
        session()->flash('message', 'Task Successfully Assigned.');
    }
}
