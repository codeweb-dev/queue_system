<div>
    <!-- HEADER -->
    <x-header title="Admin Queue Management" separator>
        <x-slot:middle class="!justify-end">
            <div class="flex itemsp-center gap-3">
                <x-theme-toggle class="btn btn-circle" />
                <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
            </div>
        </x-slot:middle>
        <x-slot:actions>
            {{-- <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" /> --}}
            <x-button label="Add Queue" @click="$wire.modal = true" responsive icon="o-plus-circle" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE -->
    {{-- <x-card wire:poll.5s="pollRefresh"> --}}
    <x-card wire:poll.5s="pollRefresh">
        <h2 class="text-lg font-semibold mb-4">Current Queue</h2>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('actions', $user)
                <div class="flex items-center">
                    @if ($user['status'] === 'pending')
                        <!-- Show only this button when status is 'pending' -->
                        <x-button icon="o-arrow-path" class="btn-ghost btn-sm text-blue-500"
                            @click="$wire.openEditStatusModal({{ $user['id'] }})" />
                    @elseif ($user['status'] === 'process')
                        <!-- Show this button when status is 'process' -->
                        <x-button icon="o-check" class="btn-ghost btn-sm text-green-500"
                            @click="$wire.openEditStatusModalApprove({{ $user['id'] }})" />
                    @else
                        <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})"
                            wire:confirm="Do you want to delete this queue?" spinner
                            class="btn-ghost btn-sm text-red-500" />
                    @endif
                </div>
            @endscope
        </x-table>
    </x-card>

    <!-- MODAL -->
    <x-modal wire:model="modal" title="Add new queue" subtitle="please enter the details below">
        <x-form wire:submit="save2">
            <x-input label="Client's name" wire:model="client_name" />
            <x-input label="Inquiry type" wire:model="inquiry_type" />

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.modal = false" />
                <x-button label="Submit" class="btn-primary" type="submit" spinner="save2" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- EDIT STATUS MODAL -->
    <x-modal wire:model="modalEditStatus" title="Change Queue Status">
        <div class="mb-5">Are you sure you want to change the status to "Approve"?</div>
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modalEditStatus = false" />
            <x-button label="Confirm" class="btn-primary" wire:click="updateStatusToApprove" spinner />
        </x-slot:actions>
    </x-modal>

    <!-- EDIT STATUS MODAL -->
    <x-modal wire:model="modalEditStatusApprove" title="Change Queue Status">
        <div class="mb-5">Are you sure you want to change the status to "Process"?</div>
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modalEditStatusApprove = false" />
            <x-button label="Confirm" class="btn-primary" wire:click="updateStatusToProcess" spinner />
        </x-slot:actions>
    </x-modal>
</div>
