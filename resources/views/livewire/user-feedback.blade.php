<x-card title="How was your experience?" subtitle="Your feedback helps us improve our service" shadow separator>
    <form wire:submit.prevent="submitFeedback" class="space-y-4">
        <label class="block font-semibold">Reaction</label>
        <div class="flex gap-3">
            <button type="button" wire:click="$set('reaction', 'angry')"
                class="p-2 rounded text-4xl {{ $reaction === 'angry' ? 'bg-red-500' : 'bg-gray-200' }}">😡</button>
            <button type="button" wire:click="$set('reaction', 'sad')"
                class="p-2 rounded text-4xl {{ $reaction === 'sad' ? 'bg-blue-500' : 'bg-gray-200' }}">😟</button>
            <button type="button" wire:click="$set('reaction', 'neutral')"
                class="p-2 rounded text-4xl {{ $reaction === 'neutral' ? 'bg-gray-500' : 'bg-gray-200' }}">😐</button>
            <button type="button" wire:click="$set('reaction', 'happy')"
                class="p-2 rounded text-4xl {{ $reaction === 'happy' ? 'bg-green-500' : 'bg-gray-200' }}">🙂</button>
            <button type="button" wire:click="$set('reaction', 'very_happy')"
                class="p-2 rounded text-4xl {{ $reaction === 'very_happy' ? 'bg-yellow-500' : 'bg-gray-200' }}">😀</button>
        </div>

        <x-input label="Full Name" wire:model="full_name" :disabled="$anonymous" />

        <x-textarea label="Additional comments (optional)" wire:model="comments"
            placeholder="Tell us more about your experience..." hint="Max 1000 chars" rows="5" inline />

        <x-checkbox label="Submit as Anonymous" wire:model="anonymous" />

        <x-button label="Submit Feedback" class="btn-primary" type="submit" spinner="submitFeedback" />

        @if (session()->has('message'))
            <p class="text-green-500 mt-2">{{ session('message') }}</p>
        @endif
    </form>
</x-card>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('redirectAfterDelay', function() {
            setTimeout(() => {
                window.location.href = '/'; // Redirect to homepage
            }, 5000); // 5-second delay
        });
    });
</script>
