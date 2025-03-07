@php
$inquiry_type = [
['value' => '', 'name' => 'Select Inquiry Type', 'disabled' => true],
['value' => 'Academic', 'name' => 'Academic (Academic Advising, Course Crediting or Concerns)'],
['value' => 'Complaints', 'name' => 'Complaints (Concerns regarding Department)'],
[
'value' => 'Document Handling',
'name' => 'Document Handling (Request for or Submission of Academic Documents)',
],
['value' => 'Financial', 'name' => 'Financial (Fee Concerns)'],
['value' => 'Other', 'name' => 'Other (Please specify in the next section)'],
];
@endphp

<div>
    <!-- HEADER -->
    <x-header title="Admin Queue Management" separator>
        <x-slot:middle class="!justify-end">
            <div class="flex gap-3 itemsp-center">
                <x-theme-toggle class="btn btn-circle" />
                <x-input placeholder="Search client name..." wire:model.live.debounce="search" clearable
                    icon="o-magnifying-glass" />
            </div>
        </x-slot:middle>
        <x-slot:actions>
            {{--
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" />
            --}}
            <x-button label="Add Queue" @click="$wire.modal = true" responsive icon="o-plus-circle"
                class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE -->
    {{-- <x-card wire:poll.5s="pollRefresh"> --}}
        <x-card wire:poll.5s="pollRefresh">
            <h2 class="mb-4 text-lg font-semibold">Current Queue</h2>
            <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
                @scope('actions', $user)
                <div class="flex items-center">
                    @if ($user['status'] === 'pending')
                    <!-- Show only this button when status is 'pending' -->
                    <x-button icon="o-arrow-path" class="text-blue-500 btn-ghost btn-sm"
                        @click="$wire.openEditStatusModal({{ $user['id'] }})" />
                    @elseif ($user['status'] === 'process')
                    <!-- Show this button when status is 'process' -->
                    <x-button icon="o-check" class="text-green-500 btn-ghost btn-sm"
                        @click="$wire.openEditStatusModalApprove({{ $user['id'] }})" />
                    @else
                    <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})"
                        wire:confirm="Do you want to delete this queue?" spinner
                        class="text-red-500 btn-ghost btn-sm" />
                    @endif
                </div>
                @endscope
            </x-table>
        </x-card>

        <!-- MODAL -->
        <x-modal wire:model="modal" title="Add new queue" subtitle="please enter the details below">
            <x-form wire:submit.prevent="save">
                <x-input label="Full Name" wire:model="full_name" required />

                <x-input type="number" label="Contact Number" wire:model="contact_number" required />

                <x-input label="Email Address" wire:model="email" required />

                <hr />

                <h3 class="font-bold">Notify Via</h3>
                <x-toggle label="SMS" wire:model="notify_sms" />
                <x-toggle label="Email" wire:model="notify_email" />

                <hr>

                <h3 class="font-bold">Inquiry Type</h3>
                <x-select required :options="$inquiry_type" option-value="value" option-label="name"
                    wire:model="inquiry_type" />

                <hr>

                <x-textarea label="Inquiry Details" wire:model="inquiry_details"
                    placeholder="Please describe your inquiry..." hint="Max 1000 chars" rows="5" required inline />

                <x-slot:actions>
                    <x-button label="Join Queue" class="w-full btn-primary" type="submit" spinner="save" />
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
            <div>Are you sure you want to change the status to "Process"?</div>
            <div class="mb-5">Current Window : @foreach ($users as $user)
                <span class="font-bold">{{ $user->id }}</span>
                @endforeach
            </div>
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.modalEditStatusApprove = false" />
                <x-button label="Confirm" class="btn-primary" wire:click="updateStatusToProcess" spinner />
            </x-slot:actions>
        </x-modal>
</div>