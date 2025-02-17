<div>
    <!-- HEADER -->
    <x-header title="Feedback" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-theme-toggle class="btn btn-circle" />
        </x-slot:middle>
    </x-header>

    <!-- TABLE  -->

    <div class="grid grid-cols-1 gap-4 md:lg-grid-cols-2 lg:grid-cols-4">
        @foreach ($feedbacks as $feedback)
            <x-card>
                <div class="flex flex-col gap-1">
                    <h2 class="text-lg font-semibold">{{ $feedback->full_name }}</h2>
                    <div class="flex items-center gap-1 text-4xl">
                        @if ($feedback->reaction === 'angry')
                            😡
                        @endif
                        @if ($feedback->reaction === 'sad')
                            😟
                        @endif
                        @if ($feedback->reaction === 'neutral')
                            😐
                        @endif
                        @if ($feedback->reaction === 'happy')
                            🙂
                        @endif
                        @if ($feedback->reaction === 'very_happy')
                            😀
                        @endif
                    </div>
                    <p class="text-sm py-3">{{ $feedback->comments }}</p>
                </div>
            </x-card>
        @endforeach
    </div>
</div>
