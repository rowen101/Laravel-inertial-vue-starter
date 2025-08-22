<?php

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
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->string('load_number')->unique();
            $table->string('customer_name');
            $table->text('pickup_location');
            $table->text('delivery_location');
            $table->date('pickup_date');
            $table->date('delivery_date');
            $table->decimal('weight', 10, 2);
            $table->decimal('rate', 10, 2);
            $table->enum('load_type', ['dry_van', 'refrigerated', 'flatbed', 'tanker', 'container'])->default('dry_van');
            $table->enum('status', ['planned', 'assigned', 'in_transit', 'delivered', 'cancelled'])->default('planned');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loads');
    }
};
