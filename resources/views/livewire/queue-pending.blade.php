<div>
    <!-- Queue Pending -->
    @if ($queueStatus !== 'process')
        <!-- Only show if the status is not 'process' -->
        <x-card title="You're in the Queue!" subtitle="We'll notify you when it's your turn." shadow separator>
            <p class="text-xl font-bold mt-2 text-center">Your Queue Number: {{ $queue->id }}</p>
        </x-card>
    @endif

    <!-- Poll the status every 5 seconds -->
    <div wire:poll="pollQueueStatus" class="hidden">
        <!-- Polling is done here to check for changes -->
    </div>

    <!-- Conditional Content Based on Status -->
    @if ($queueStatus === 'process')
        <x-card title="It's Your Turn!" subtitle="Please proceed to the Reception Area." shadow separator>
            <p class="text-xl font-bold mt-2 text-center">Your Queue Number: {{ $queue->id }}</p>
        </x-card>
    @endif
</div>
