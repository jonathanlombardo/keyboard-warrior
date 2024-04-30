<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('lions', function (Blueprint $table) {
      $table->id();
      $table->string('name', 30);
      $table->foreignId('user_id')->nullable()->constrained();
      $table->foreignId('lion_id')->nullable()->constrained();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('lions');
  }
};
