<div>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Change your shift status!</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        <p>Current team {{ auth()->user()->currentTeam->name }}</p>
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif

                <form wire:submit.prevent="changeShiftStatus()">
                    <input type="hidden" wire:model="task_id">
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-4">
                                    <h3>Total Hours Worked for current team: <span>{{  $totalHours }}</span></h3>
                                    <label class="block text-sm font-medium text-gray-700" for="current_password">
                                        Currently not on shift.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 text-right bg-gray-50 sm:px-6">
                            <button wire:submit.prevent="changeShiftStatus()"
                                class="inline-flex items-center px-4 py-2 mr-10 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                {{ $action }} Shift
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>