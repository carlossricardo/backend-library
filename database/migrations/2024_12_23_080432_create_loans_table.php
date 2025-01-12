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
        Schema::create('loans', function (Blueprint $table) {            
            
            
            $table->uuid('id')->primary();

            $table->uuid('user_id'); 
            $table->uuid('reviewed_by')->nullable(); 

            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');            
            $table->timestamp('date_returned')->nullable();  
            $table->text('note')->nullable();          
            $table->bigInteger('total_units');
            $table->enum('status', ['active', 'accepted', 'returned', 'overdue', 'rejected'])->default('active');
            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
