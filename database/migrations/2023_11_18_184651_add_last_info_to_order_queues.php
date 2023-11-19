<?php

use App\Enums\BlackHawkApiType;
use App\Models\ApiCall;
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
        Schema::table('order_queues', function (Blueprint $table) {
            $table->jsonb('last_info')->nullable();
        });

        Schema::table('api_calls', function (Blueprint $table) {
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->dropColumn('previous_request');
        });

        ApiCall::where('success', true)
            ->update(['status_code' => 201]);

        ApiCall::where('success', true)
            ->where('api', BlackHawkApiType::Catalog->value)
            ->update(['status_code' => 200]);

        DB::statement("UPDATE api_calls SET api = 'realtime_order' WHERE api = 'order'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_queues', function (Blueprint $table) {
            $table->dropColumn('last_info');
        });

        Schema::table('api_calls', function (Blueprint $table) {
            $table->dropColumn('status_code');
            $table->integer('previous_request')->nullable();
        });
    }
};
