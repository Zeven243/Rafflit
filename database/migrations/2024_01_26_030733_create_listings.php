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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('full_price', 10, 2)->default(0.00);
            $table->unsignedInteger('amount_of_tickets')->default(0);
            $table->decimal('ticket_price', 10, 2)->virtualAs('full_price / amount_of_tickets');
            $table->string('cover_image_path')->nullable();
            $table->json('image_paths')->nullable();
            $table->unsignedInteger('tickets_sold')->default(0);
            $table->string('company')->nullable();
            $table->unsignedInteger('potential_tickets')->default(0);
            $table->string('SKU')->nullable();
            $table->boolean('is_active')->default(false); // Set is_active to false by default
            $table->unsignedBigInteger('winner_user_id')->nullable();
            $table->boolean('delivery_confirmed')->default(false); // Add delivery_confirmed column
            $table->string('item_condition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};