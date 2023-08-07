<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Brand::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Organization::class)->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_organizations');
    }
};
