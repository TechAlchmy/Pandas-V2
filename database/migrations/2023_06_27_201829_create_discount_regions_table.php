<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Region;
use App\Models\Discount;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discount_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Discount::class, "discount_id")->nullable();
            $table->foreignIdFor(Region::class, "region_id")->nullable();
            $table->foreignIdFor(User::class, "created_by")->nullable();
            $table->foreignIdFor(User::class, "updated_by")->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, "deleted_by")->nullable();
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
