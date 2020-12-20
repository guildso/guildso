<?php

namespace App\Http\Livewire;
use App\Models\Shift;
use App\Models\User;
use Livewire\Component;

class Shifts extends Component
{
    public $timer;
    public $action;
    public $status;
    public $totalHours;

    public function render()
    {
        $this->totalHours();

        if (auth()->user()->isOnShift()) {
            $this->action = 'Stop';
            $this->status = 'On Shift';
        } else {
            $this->action = 'Start';
            $this->status = 'Not On Shift';
        }
        return view('livewire.shifts');
    }

    public function startShift()
    {
        auth()->user()->startShift();
        $this->status = 'On Shift';
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'message' => 'You are now on shift!']);
    }

    public function endShift()
    {
        auth()->user()->endShift();
        $this->status = 'Not On Shift';
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'message' => 'You have ended your shift!']);
    }

    public function changeShiftStatus()
    {
        if (auth()->user()->isOnShift()) {
            $this->endShift();
        } else {
            $this->startShift();
        }
    }

    public function totalHours()
    {
        $this->totalHours = auth()->user()->totalHoursWorked();
    }
}
