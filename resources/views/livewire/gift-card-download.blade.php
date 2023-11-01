<div class="container">
    @php($discount = App\Models\Discount::firstWhere('code', $gift['contentProviderCode']))

    <div class="header">
        <img class="logo" src="https://panda-static.s3.us-east-2.amazonaws.com/assets/panda_logo.png" alt="Logo">
        <span class="name">{{ $discount?->name }}</span>
    </div>

    <div class="gift-details">
        <img class="gift-card" src="{{ $discount->media?->first()?->original_url }}" alt="Gift Icon">
        <div class="gift-info">
            <b>Amount</b>
            <br /> ${{ $gift['amount'] ?? 'N/A' }}<br /><br />
            <b>Access Number</b>
            <br /> {{ $gift['pin'] ?? 'N/A' }}<br /> <br />
            <b>Card Number</b><br /> {{ substr($gift['cardNumber'], 0, -1 * strlen($gift['pin']))}}
        </div>
    </div>
    <hr style="border: 1px solid #e0e0e0" />
    <div class="scan-code">
        <b>Scan Code</b><br />
        <img src="{{ barCodeGenerator($gift['cardNumber'])}}" style="height: 40px;" alt="Scan Code Image">
    </div>
</div>

<style>
    .container {
        width: 100%;
        font-family: Arial, sans-serif;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        padding: 10px;
        background-color: #fff;
        border: 2px dotted #e0e0e0;
        /* This line adds the border */
        max-width: 75%;
        /* This line ensures the card doesn't go beyond 60% width */
        margin: 0 auto;
    }

    .logo {
        width: 40px;
        height: auto;
        display: inline-block;
        vertical-align: middle;
    }

    .name {
        display: inline-block;
        vertical-align: middle;
    }

    .gift-details {
        margin-top: 20px;
    }

    .gift-card {
        width: 50%;
        /* or whatever width is suitable */
        display: inline-block;
        vertical-align: top;
    }

    .gift-info {
        display: inline-block;
        vertical-align: top;
        padding-left: 15px;
        padding-bottom: 20px;
    }

    .scan-code {
        margin-top: 5px;
        margin-bottom: 5px;
        text-align: center;
    }
</style>