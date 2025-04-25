<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    protected $tableName;
    protected $createBy;

    public function __construct()
    {
        $this->tableName = config('announcements.table_name', 'announcements');
        $this->createBy = config('announcements.create_by_table_name', 'user_id');
    }

    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->foreignId($this->createBy)->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};

