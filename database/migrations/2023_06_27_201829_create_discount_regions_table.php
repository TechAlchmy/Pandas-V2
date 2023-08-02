<?php

use App\Models\Discount;
use App\Models\Region;
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
        Schema::create('discount_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Discount::class, 'discount_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Region::class, 'region_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_regions');
    }
};
