<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="flex items-center justify-center w-full px-8 py-10 md:py-16 lg:py-20 xl:px-0">
                <div class="max-w-6xl mx-auto">
                    <div class="flex-col items-center">
                        <div
                            class="flex flex-col items-center justify-center w-full h-full max-w-2xl pr-8 mx-auto text-center">
                            <p class="mb-5 text-base font-medium tracking-tight text-indigo-500 uppercase">Team Status
                            </p>
                            <h2
                                class="text-4xl font-extrabold leading-10 tracking-tight text-gray-900 sm:text-5xl sm:leading-none md:text-6xl lg:text-5xl xl:text-6xl">
                                {{ auth()->user()->currentTeam->name }}</h2>
                        </div>
                        <div class="pt-12 lg:col-span-2">
                            <ul class="space-y-12 sm:grid sm:grid-cols-2 sm:gap-12 sm:space-y-0 lg:gap-x-8">
                                @foreach (auth()->user()->currentTeam->teamShifts() as $member)
                                    @if(isset($member->shifts[0]->status) && $member->shifts[0]->status == 'on')
                                        <li class="px-10 py-4 mx-12 bg-white border border-gray-200 rounded">
                                            <div class="flex items-center space-x-4 lg:space-x-6">
                                                <a href="#_" class="relative">
                                                    <img class="w-16 h-16 rounded-full lg:w-20 lg:h-20"
                                                    src="{{ $member->profile_photo_url }}"
                                                    alt="">
                                                    <span class="absolute bottom-0 right-0 w-4 h-4 mb-1 mr-1 bg-green-500 border-2 border-white rounded-full"></span>
                                                </a>
                                                <div class="space-y-1 text-lg font-medium leading-6">
                                                    <h3>{{ $member->name }}</h3>
                                                    <span class="text-xs leading-none text-gray-700 dark:text-dark-300">{{ $member->teamRole($member->currentTeam)->name }}</span>
                                                    @forelse($member->tasks as $task)
                                                        <p class="text-indigo-600">{{ $task->name }}</p>
                                                    @empty
                                                        <p class="text-gray-600">Not working on any tasks</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                                @foreach (auth()->user()->currentTeam->teamShifts() as $member)
                                    @if(!isset($member->shifts[0]->status) || $member->shifts[0]->status == 'off')
                                        <li class="px-10 py-4 mx-12 bg-white border border-gray-200 rounded">
                                            <div class="flex items-center space-x-4 lg:space-x-6">
                                                <a href="#_" class="relative">
                                                    <img class="w-16 h-16 rounded-full lg:w-20 lg:h-20"
                                                    src="{{ $member->profile_photo_url }}"
                                                    alt="">
                                                    <span class="absolute bottom-0 right-0 w-4 h-4 mb-1 mr-1 bg-gray-500 border-2 border-white rounded-full"></span>
                                                </a>
                                                <div class="space-y-1 text-lg font-medium leading-6">
                                                    <h3>{{ $member->name }}</h3>
                                                    <span class="text-xs leading-none text-gray-700 dark:text-dark-300">{{ $member->teamRole($member->currentTeam)->name }}</span>
                                                    @forelse($member->tasks as $task)
                                                        <p class="text-indigo-600">{{ $task->name }}</p>
                                                    @empty
                                                        <p class="text-gray-600">Not working on any tasks</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>