<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Mary\Traits\Toast;
use App\Models\Queue;
use Carbon\Carbon;

#[Title('Admin Dashboard')]
class Admin extends Component
{
    use Toast;

    public string $search = '';
    public bool $drawer = false;
    public bool $modal = false;
    public bool $modalEditStatus = false;
    public bool $modalEditStatusApprove = false;
    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];
    public Collection $users;
    public string $lastUpdatedAt = '';
    public $userIdToUpdate;

    public function openEditStatusModal($userId)
    {
        $this->userIdToUpdate = $userId; // Save the user ID
        $this->modalEditStatusApprove = true; // Show the modal
    }

    public function openEditStatusModalApprove($userId)
    {
        $this->userIdToUpdate = $userId; // Save the user ID
        $this->modalEditStatus = true; // Show the modal
    }

    public function mount()
    {
        $this->refreshUsers();
        $this->lastUpdatedAt = Carbon::now()->toIso8601String(); // Store current timestamp when component is mounted
    }

    public function updateStatusToApprove()
    {
        $queue = Queue::find($this->userIdToUpdate);

        if ($queue) {
            $queue->status = 'approve'; // Update the status to 'approve'
            $queue->save();
            $this->success("Queue #{$this->userIdToUpdate} status updated to 'Process'.", position: 'toast-bottom');
            $this->modalEditStatusApprove = false;
        } else {
            $this->error("Queue #{$this->userIdToUpdate} not found.", position: 'toast-bottom');
        }

        $this->refreshUsers(); // Refresh the users list
    }

    public function updateStatusToProcess()
    {
        $queue = Queue::find($this->userIdToUpdate);

        if ($queue) {
            $queue->status = 'process'; // Update the status to 'process'
            $queue->save();
            $this->success("Queue #{$this->userIdToUpdate} status updated to 'Process'.", position: 'toast-bottom');
            $this->modalEditStatus = false;
        } else {
            $this->error("Queue #{$this->userIdToUpdate} not found.", position: 'toast-bottom');
        }

        $this->refreshUsers();
    }

    // Delete action
    public function delete($id): void
    {
        $queue = Queue::find($id);

        if ($queue) {
            $queue->delete();
            $this->success("Queue #$id deleted successfully.", position: 'toast-bottom');
        } else {
            $this->error("Queue #$id not found.", position: 'toast-bottom');
        }

        $this->refreshUsers();
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'Ticket #', 'class' => 'w-1'],
            ['key' => 'full_name', 'label' => 'Client Name', 'class' => 'w-64', 'sortable' => false],
            ['key' => 'contact_number', 'label' => 'Contact Number', 'class' => 'w-32', 'sortable' => false],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-64', 'sortable' => false],
            ['key' => 'inquiry_type', 'label' => 'Inquiry Type', 'class' => 'w-20', 'sortable' => false],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-20', 'sortable' => false],
        ];
    }

    public function refreshUsers()
    {
        $this->users = Queue::query()
            ->when($this->search, function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->get();
    }

    // Method to check if new queue has been added or updated
    public function checkForNewQueue()
    {
        $latestQueue = Queue::latest()->first(); // Get the latest queue

        if ($latestQueue && $latestQueue->created_at->toIso8601String() !== $this->lastUpdatedAt) {
            $this->lastUpdatedAt = $latestQueue->created_at->toIso8601String(); // Update timestamp
            $this->refreshUsers(); // Refresh only when a new queue is added
        }
    }

    public function pollRefresh()
    {
        $this->refreshUsers();
    }

    public function render()
    {
        return view('livewire.admin', [
            'users' => $this->users,
            'headers' => $this->headers()
        ]);
    }
}
