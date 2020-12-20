<div>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Craete a new task for your team!</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        Start by creating a task here.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif

                @if($updateMode)
                    @include('livewire.editTask')
                @else
                    @include('livewire.addTask')
                @endif
            </div>
        </div>
        <div class="flex flex-col mt-10">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        User
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Task
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Role
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($task->user)
                                                    <div class="flex-shrink-0 w-10 h-10">
                                                        <img class="w-10 h-10 rounded-full"
                                                            src="{{ $task->user->profile_photo_url }}"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $task->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $task->user->email }}
                                                        </div>
                                                    </div>
                                                @else
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        Unassigned Task
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Feel free to take it!
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $task->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $task->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            @if($task->user)
                                                {{ $task->user->teamRole($task->user->currentTeam)->name }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <button wire:click="assign({{ $task->id }})" class="text-indigo-600 hover:text-indigo-900">
                                                @if($task->user_id == auth()->user()->id)
                                                    Unassign
                                                @else
                                                    Assign
                                                @endif
                                            </button>
                                            <button wire:click="edit({{ $task->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            @if(auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'delete'))
                                            <button wire:click="delete({{ $task->id }})" class="text-indigo-600 hover:text-indigo-900">Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>