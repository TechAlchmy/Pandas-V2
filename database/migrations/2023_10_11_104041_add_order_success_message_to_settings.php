<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::table('settings', function (Blueprint $table) {
            $table->text('order_success_message')->nullable();
            $table->integer('max_retries')->nullable();
            $table->text('order_failed_message')->nullable();
            $table->string('notification_email')->nullable();
        });

        DB::statement("UPDATE orders set payment_status = 'approved' WHERE payment_status = 'Approved'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['order_success_message', 'max_retries', 'order_failed_message', 'notification_email']);
        });
    }
};
