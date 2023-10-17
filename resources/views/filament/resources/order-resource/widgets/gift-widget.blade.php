<x-filament-widgets::widget>
    @if(!empty($data))

    <div class="flex justify-center">
        <x-filament::card class="w-1/2 bg-purple-100 rounded-lg shadow-lg p-6">
            <p><strong>Transaction ID:</strong> {{ $data['transactionId'] }}</p>
            <p><strong>Success:</strong> {{ $data['success'] ? 'Yes' : 'No' }}</p>
            <p><strong>Order Number:</strong> {{ $data['orderNumber'] }}</p>
            <p><strong>Content Provider Code:</strong> {{ $data['contentProviderCode'] }}</p>
            <p><strong>Transaction Amount:</strong> ${{ $data['transactionAmount'] }}</p>
            <p><strong>Card Number:</strong> {{ $data['cardNumber'] }}</p>
            <p><strong>PIN:</strong> {{ $data['pin'] }}</p>
        </x-filament::card>
        <x-filament::card class=" bg-purple-100 rounded-lg shadow-lg p-6 h-200">
            @php($photo = \App\Models\Discount::firstWhere('code', $data['contentProviderCode'])->media?->first()?->original_url)
            <img src="{{$photo}}" style="height: 200px;">
        </x-filament::card>
    </div>
    @endif

</x-filament-widgets::widget>