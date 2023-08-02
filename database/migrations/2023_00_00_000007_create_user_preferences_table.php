<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, "user_id")->nullable();
            $table->smallInteger("email_notification")->default(0)->comment("0: no, 1: yes");
            $table->smallInteger("sms_notification")->default(0)->comment("0: no, 1: yes");
            $table->smallInteger("push_notification")->default(0)->comment("0: no, 1: yes");
            $table->smallInteger("email_marketing")->default(0)->comment("0: no, 1: yes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
