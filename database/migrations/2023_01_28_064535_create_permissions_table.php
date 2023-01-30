<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_group_id');
            $table->string('group');
            $table->enum('action', \App\Enum\PermissionActionEnum::values()->toArray());
            $table->boolean('value')->default(0);
            $table->timestamps();

            $table->foreign('user_group_id')
                ->references('id')
                ->on('user_groups')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
