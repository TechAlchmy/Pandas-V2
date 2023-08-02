<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Organization;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, "user_id")->nullable();
            $table->foreignIdFor(Organization::class, "organization_id")->nullable();
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
        Schema::dropIfExists('managers');
    }
};
