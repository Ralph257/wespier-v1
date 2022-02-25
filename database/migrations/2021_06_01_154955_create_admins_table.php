<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Admin;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('forget_password_token')->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('admin_type')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        Admin::create([
            'name' => 'Ralph L.',
            'email' => 'admin@wespier.com',
            'password' => bcrypt('Rad25700'), 
            'image' => 'uploads/website-images/Ralph L.-2022-02-08-11-21-49-9492.png',
            'status' => 1,
            'admin_type' => 1,
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
