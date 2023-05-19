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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50);
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('collection_amount',10,2);
            $table->decimal('commission_amount',10,2);
            $table->decimal('discount',10,2);
            $table->string('tax_rate')->nullable();
            $table->decimal('tax_value',10,2);
            $table->decimal('total',10,2);
            $table->enum('status', ['Not Paid', 'Paid', 'Partially Paid'])->default('Not Paid');
            $table->date('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
