<?php

namespace App\Livewire;

use App\Models\Queue;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.client')]
#[Title('Queue Form')]
class QueueForm extends Component
{
    public bool $modal = false;
    #[Validate('min:4', message: 'Pin should be 4 digits.')]
    public string $pin = '';

    public $full_name = '';
    public $contact_number = '';
    public $email = '';
    public $inquiry_type = '';
    public $inquiry_details = '';
    public $notify_sms = false;
    public $notify_email = false;

    public $currentServing = null;

    protected $rules = [
        'full_name' => 'required|string|max:255',
        'contact_number' => 'required|numeric',
        'email' => 'required|email|max:255',
        'inquiry_type' => 'required|string',
        'inquiry_details' => 'string|max:1000',
        'notify_sms' => 'boolean',
        'notify_email' => 'boolean',
    ];

    public function mount()
    {
        $this->fetchCurrentServing();
    }

    public function fetchCurrentServing()
    {
        $this->currentServing = Queue::where('status', 'process')->orderBy('updated_at', 'asc')->first();
    }

    public function save()
    {
        $this->validate();

        $queue = Queue::create([
            'full_name' => $this->full_name,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'inquiry_type' => $this->inquiry_type,
            'inquiry_details' => $this->inquiry_details,
            'notify_sms' => (bool) $this->notify_sms,
            'notify_email' => (bool) $this->notify_email,
        ]);

        return redirect()->to("/queue-pending/{$queue->id}");
    }

    public function checkPin()
    {
        $this->validate();

        if ($this->pin === '2025') {
            return redirect()->to('/admin');
        } else {
            $this->addError('pin', 'The PIN is incorrect.');
        }
    }

    public function render()
    {
        return view('livewire.queue-form');
    }
}
