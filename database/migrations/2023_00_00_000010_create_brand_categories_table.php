<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brand_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class, "category_id")->nullable();
            $table->foreignIdFor(Brand::class, "brand_id")->nullable();
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
        Schema::dropIfExists('brand_categories');
    }
};
