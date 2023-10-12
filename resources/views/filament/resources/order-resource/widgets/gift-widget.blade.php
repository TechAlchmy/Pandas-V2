<x-filament-widgets::widget>
    @if(!empty($data))

    <x-filament::section>
        <div class="p-4 bg-white rounded shadow-md">
            <p><strong>Transaction ID:</strong> {{ $data['transactionId'] }}</p>
            <p><strong>Success:</strong> {{ $data['success'] ? 'Yes' : 'No' }}</p>
            <p><strong>Order Number:</strong> {{ $data['orderNumber'] }}</p>
            <p><strong>Content Provider Code:</strong> {{ $data['contentProviderCode'] }}</p>
            <p><strong>Transaction Amount:</strong> ${{ $data['transactionAmount'] }}</p>
            <p><strong>Card Number:</strong> {{ $data['cardNumber'] }}</p>
            <p><strong>PIN:</strong> {{ $data['pin'] }}</p>
        </div>
    </x-filament::section>
    @endif

</x-filament-widgets::widget>