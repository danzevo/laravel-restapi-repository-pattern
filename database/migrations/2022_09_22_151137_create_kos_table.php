<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 32, 2)->default(0);
            $table->enum('kos_type' , ['putra', 'putri', 'campur'])->default('putra');
            $table->longText('description')->nullable();
            $table->string('kos_established', 4);
            $table->string('room_type', 75)->nullable();
            $table->string('admin_name', 75)->nullable();
            $table->foreignId('user_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kos');
    }
}
