<?php

use App\Enum\DeliveryScheduleType;
use App\Models\Tariff;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_phone', 11)
                ->unique();
            $table->enum('schedule_type', DeliveryScheduleType::values())
                ->default(DeliveryScheduleType::EVERY_DAY->value);
            $table->text('comment')
                ->nullable();
            $table->date('first_date');
            $table->date('last_date');

            $table->foreignIdFor(Tariff::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
