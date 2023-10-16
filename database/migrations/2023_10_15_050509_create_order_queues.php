<?php

use App\Enums\BlackHawkOrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $table->timestamp('attempted_at')->nullable();

            $table->boolean('is_order_placed')
                ->default(false)
                ->comment('If order place is successfully, this will be true. But if get order returns failed, make this false');

            $table->string('request_id')->unique()->nullable()
                ->comment('This should be set when the request is sent to blackhawk. This is used to track the request and response');

            $table->boolean('is_current')
                ->default(false)
                ->comment('Set this when this queue starts running, and unset it when it stops or ends.');

            $table->string('order_status')
                ->default(BlackHawkOrderStatus::Default)
                ->comment('This is the response received when performing get status api.');

            $table->timestamp('fetched_at')->nullable();

            $table->json('gifts')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_queues');
    }
};
