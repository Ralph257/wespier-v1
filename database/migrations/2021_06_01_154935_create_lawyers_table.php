<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Agent;

class CreateLawyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->integer('fee');
            $table->integer('location_id');
            $table->string('phone');
            $table->integer('department_id');
            $table->longText('about');
            $table->longText('educations');
            $table->string('designations');
            $table->longText('address');
            $table->longText('experience');
            $table->longText('qualifications');
            $table->string('image');
            $table->tinyInteger('status');
            $table->tinyInteger('show_homepage');
            $table->string('forget_password_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Agent::create([
            'name' => 'Leslie Ann Boyce',
            'slug' =>'leslie_ann_boyce',
            'ceo_title' => 'Leslie Ann Boyce Eqr.',
            'ceo_description' => 'Leslie Ann Boyce Eqr.',
            'email' => 'leslie@wespier.com',
            'password' => bcrypt('rad25700'),
            'fee' => 0,
            'location_id' => 1,
            'phone' => 818-940-1445,
            'department_id' => 1,
            'designations' => 'Income Tax',
            'address' => 'Business Address',
            'experience' => 'Paralegal Services',
            'qualifications' => '<p> Some other text that is required and cannot be null...</p>',
            'image' => 'uploads/website-images/Ralph L.-2022-02-08-11-21-49-9492.png',
            'status' => 1,
            'show_homepage' => 1,
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
        Schema::dropIfExists('doctors');
    }
}
