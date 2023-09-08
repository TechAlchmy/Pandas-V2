<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_insight_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->foreignIdFor(\App\Models\DiscountInsight::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Discount::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_insight_models');
    }
};
