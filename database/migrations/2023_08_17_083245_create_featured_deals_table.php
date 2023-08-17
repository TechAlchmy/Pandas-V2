<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Discount::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Organization::class)->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_deals');
    }
};
