<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('user_access', function (Blueprint $table)
        {
            $table->id();
            $table->bigInteger('user_id');
            $table->boolean('admin')->default(false);
            $table->boolean('create_category')->default(false);
            $table->boolean('delete_category')->default(false);
            $table->boolean('create_product')->default(false);
            $table->boolean('delete_product')->default(false);
            $table->boolean('edit_product')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('user_access', function (Blueprint $table)
        {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
