<?php

use App\Models\Region;
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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('website', 255);
            $table->string('slug', 255);
            $table->string('uniqid', 255);
            $table->string('phone', 45)->nullable();
            $table->string('email', 45);
            $table->foreignIdFor(Region::class, 'region_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
