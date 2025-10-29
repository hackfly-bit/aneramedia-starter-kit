<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        Schema::disableForeignKeyConstraints();

        // Convert users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id', 26)->primary()->change();
        });

        // Convert roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id', 26)->primary()->change();
        });

        // Convert permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id', 26)->primary()->change();
        });

        // Convert menus table
        Schema::table('menus', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id', 26)->primary()->change();
        });

        // Update foreign key columns to string
        Schema::table('menu_role', function (Blueprint $table) {
            $table->string('menu_id', 26)->change();
            $table->string('role_id', 26)->change();
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->string('permission_id', 26)->change();
            $table->string('role_id', 26)->change();
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->string('permission_id', 26)->change();
            $table->string('user_id', 26)->change();
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->string('role_id', 26)->change();
            $table->string('user_id', 26)->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->string('parent_id', 26)->nullable()->change();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id', 26)->primary()->change();
            $table->string('tokenable_id', 26)->change();
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id', 26)->primary()->change();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropPrimary();
            $table->string('id', 26)->primary()->change();
        });

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks temporarily
        Schema::disableForeignKeyConstraints();

        // Revert users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->change();
        });

        // Revert roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->change();
        });

        // Revert permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->change();
        });

        // Revert menus table
        Schema::table('menus', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->change();
        });

        // Revert foreign key columns to integer
        Schema::table('menu_role', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id')->change();
            $table->unsignedBigInteger('role_id')->change();
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id')->change();
            $table->unsignedBigInteger('role_id')->change();
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id')->change();
            $table->unsignedBigInteger('user_id')->change();
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->change();
            $table->unsignedBigInteger('user_id')->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->change();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->change();
            $table->morphs('tokenable');
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->change();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->change();
        });

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
};
