<?php

use App\Models\Brand;
use App\Models\OfferType;
use App\Models\User;
use App\Models\VoucherType;
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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Brand::class, 'brand_id')->nullable();
            $table->string('name', 255)->unique();
            $table->foreignIdFor(VoucherType::class)->nullable()->constrained()->nullOnDelete();
            $table->string('slug', 255)->unique();
            $table->boolean('is_active')->default(false);
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->integer('status')->default(1);
            $table->string('api_link')->nullable();
            $table->string('link')->nullable();
            $table->unsignedInteger('cta')->nullable();
            $table->integer('views')->default(0);
            $table->integer('clicks')->default(0);
            $table->string('code')->nullable();
            $table->jsonb('amount')->nullable();
            $table->integer('limit_qty')->nullable();
            $table->decimal('limit_amount', 10, 2)->nullable();
            $table->decimal('public_percentage', 10, 2)->nullable();
            $table->decimal('percentage', 10, 2)->nullable();
            $table->foreignIdFor(User::class, 'created_by_id')->nullable();
            $table->foreignIdFor(User::class, 'updated_by_id')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
