<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('borrow_records', function (Blueprint $table) {
            $table->enum('status', ['active', 'returned'])->default('active')->after('return_date');
            $table->decimal('fine_amount', 8, 2)->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('borrow_records', function (Blueprint $table) {
            $table->dropColumn(['status', 'fine_amount']);
        });
    }
};
