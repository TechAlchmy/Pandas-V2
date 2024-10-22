<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_detail_refunds', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->dateTime('approved_at')->nullable();
            $table->foreignIdFor(\App\Models\OrderDetail::class)->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->longText('note')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'approved_by_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'created_by_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'updated_by_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'deleted_by_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_detail_refunds');
    }
};
