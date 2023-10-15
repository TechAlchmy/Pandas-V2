<?php

use App\Models\Order;
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
            $table->foreignIdFor(Order::class);
            $table->timestamp('attempted_at')->nullable();

            $table->boolean('is_order_placed')
                ->default(false)
                ->comment('If order place is successfully, this will be true. But if get order returns failed, make this false');

            $table->boolean('is_order_success')
                ->nullable()
                ->comment('If get order details returns success this will be true and this cycle is done. But if get order returns failed, make this false and softdelete this and create a duplicate queue to retry from start');

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
