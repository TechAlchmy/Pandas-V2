<?php

namespace App\Livewire;

use App\Models\ApiCall;
use App\Models\Order;
use App\Models\OrderQueue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class GiftCard extends Component
{
    public function render()
    {
        return view('livewire.gift-card', ['records' => $this->orderQueue]);
    }

    public $orderQueue;
    public $orderId;

    public function mount(int $orderId)
    {
        $this->orderQueue = Order::find($orderId)->orderQueue;
    }

    public function downloadInvoice()
    {
        $pdf = Pdf::loadView('livewire.gift-card', ['records' => $this->apiCall])
            ->setPaper('a5', 'landscape');

        $fileName = 'card_' . time() . '_' . $this->orderId . '.pdf';
        Storage::put('public/temp/' . $fileName, $pdf->stream());
        return Storage::download('public/temp/' . $fileName);
        // This needs to be deleted using a job probably 5 minutes after creating
    }
}
