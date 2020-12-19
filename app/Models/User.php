<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function shifts()
    {
        return $this->hasMany('App\Models\Shift');
    }

    public function startShift()
    {
        $shift = $this->shifts()->where('team_id', $this->currentTeam->id)->exists();

        if ($shift) {
            $shift = $this->shifts()->where('team_id', $this->currentTeam->id)->firstOrFail();
            $shift->status = 'on';
            $shift->started_at = new Carbon;
            $shift->user_id = auth()->user()->id;
            try {
                $shift->update();
                return true;
            } catch (\Throwable $th) {
                throw $th;
            }
        } else {
            $shift = new Shift;
            $shift->status = 'on';
            $shift->started_at = new Carbon;
            $shift->user_id = $this->id;
            $shift->team_id = $this->currentTeam->id;
            try {
                $shift->save();
                return true;
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public function endShift()
    {
        $shift = $this->shifts()->where('team_id', $this->currentTeam->id)->whereNotNull('started_at')->firstOrFail();
        $now = new Carbon;
        $totalHours = $now->diffInMinutes($shift->started_at, true);
        $totalHours = $totalHours/60;
        $shift->total_hours += $totalHours;
        $shift->status = 'off';
        $shift->started_at = NULL;

        try {
            $shift->save();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function isOnShift()
    {
        $shift = $this->shifts()->where('team_id', $this->currentTeam->id)->where('status', 'on')->exists();
        if ($shift) {
            return true;
        }
        return false;
    }

    public function totalHoursWorked()
    {
        if ($this->shifts()->where('team_id', $this->currentTeam->id)->exists()) {
            $shift = $this->shifts()->where('team_id', $this->currentTeam->id)->firstOrFail();
            return round($shift->total_hours, 2);
        }
        return 0;
    }
}
