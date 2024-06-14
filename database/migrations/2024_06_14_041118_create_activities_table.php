<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_entry_id')->constrained()->onDelete('cascade');
            $table->enum('timing', ['sebelum istirahat', 'sesudah istirahat']);
            $table->enum('category', ['webinar', 'penilaian', 'admin', 'referensi', 'grafis', 'website']);
            $table->string('supervisor_instruction');
            $table->text('description');
            $table->string('target_completion');
            $table->enum('status', ['sudah tercapai', 'belum tercapai']);
            $table->text('reason_not_achieved')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
