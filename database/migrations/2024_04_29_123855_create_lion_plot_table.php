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
    Schema::create('lion_plot', function (Blueprint $table) {
      $table->id();
      $table->foreignId('lion_id')->constrained()->cascadeOnDelete();
      $table->foreignId('plot_id')->constrained()->cascadeOnDelete();
      $table->boolean('supported')->default(false);
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
    Schema::dropIfExists('lion_plot');
  }
};
