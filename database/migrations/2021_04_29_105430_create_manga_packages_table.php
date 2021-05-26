<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangaPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_packages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->text('dir_path');
            $table->text('package_img_path')->default('/storage/img/no-manga-image.jpg');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->unsignedInteger('volumes')->default(0);
            $table->unsignedInteger('favorites')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manga_packages');
    }
}
