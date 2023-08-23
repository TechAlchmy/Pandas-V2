<?php

use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->dateTime('order_date')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('order_status')->comment('pending, processing, on hold, completed, cancelled, refunded, failed')->nullable();
            $table->unsignedBigInteger('order_column')->nullable()->index();
            $table->unsignedInteger('order_discount')->nullable();
            $table->unsignedInteger('order_tax')->nullable();
            $table->unsignedInteger('order_subtotal')->nullable();
            $table->unsignedInteger('order_total')->nullable();
            $table->string('payment_method', 255)->nullable();
            $table->string('payment_status', 255)->comment('pending, paid, failed, refunded')->nullable();

            $table->foreignIdFor(User::class, 'created_by_id')->nullable();
            $table->foreignIdFor(User::class, 'updated_by_id')->nullable();
            $table->foreignIdFor(User::class, 'deleted_by_id')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
