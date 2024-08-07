<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback', function ($table) {
            $table->dateTime('datetime')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function ($table) {
            $table->dropColumn('datetime');
        });
    }
};
