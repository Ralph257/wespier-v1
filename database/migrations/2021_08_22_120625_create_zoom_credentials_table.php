<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\ZoomCredential;

class CreateZoomCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_credentials', function (Blueprint $table) {
            $table->id();
            $table->integer('lawyer_id')->default(0);
            $table->integer('agent_id')->default(0); 
            $table->integer('admin_id')->default(0);
            $table->text('zoom_api_key');
            $table->text('zoom_api_secret');
            $table->timestamps();
        });

        ZoomCredential::create([
            'lawyer_id' => 0,
            'agent_id' => 0,
            'admin_id' => 1,
            'zoom_api_key' => 'SYM_zBFARD2yW8XxpfsEVg',
            'zoom_api_secret' => '5g8xMsCN3YxHzsG3hignhNrTS8w1fz4mmH7x',
        ]);        
        ZoomCredential::create([
            'lawyer_id' => 0,
            'agent_id' => 1,
            'admin_id' => 0,
            'zoom_api_key' => 'SYM_zBFARD2yW8XxpfsEVg',
            'zoom_api_secret' => '5g8xMsCN3YxHzsG3hignhNrTS8w1fz4mmH7x',
        ]);
        ZoomCredential::create([
            'lawyer_id' => 1,
            'agent_id' => 0,
            'admin_id' => 0,
            'zoom_api_key' => 'SYM_zBFARD2yW8XxpfsEVg',
            'zoom_api_secret' => '5g8xMsCN3YxHzsG3hignhNrTS8w1fz4mmH7x',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_credentials');
    }
}
