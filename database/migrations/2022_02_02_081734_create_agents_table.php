<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Agent;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('email')->unique();
            $table->string('password');
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
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('prescription_address');
            $table->string('prescription_email');
            $table->string('prescription_phone');
            $table->string('forget_password_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Agent::create([
            'name' => 'Wendy S.',
            'slug' =>'wendy_s',
            'ceo_title' => 'Wendy S.',
            'ceo_description' => 'Wendy S.',
            'email' => 'admi@wespier.com',
            'password' => bcrypt('Rad25700'),
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
        Schema::dropIfExists('agents');
    }
}
