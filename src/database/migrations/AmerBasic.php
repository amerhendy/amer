<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('governorates')){
            Schema::create('governorates', function (Blueprint $table) {
                $table->uid();
                $table->string('name');
                $table->string('english');
                $table->dates();
            });
        }
        if(!Schema::hasTable('cities')){
            Schema::create('cities', function (Blueprint $table) {
                $table->uid();
                $table->foreignUuid('gov_id')->constrainded(
                    table: 'governorates'
                )->cascadeOnUpdate()->cascadeOnDelete();
                $table->string('name');
                $table->string('english');
                $table->string('landlinecode');
                $table->dates();
                $table->primary(['id','Gov_id']);
                $table->index('Gov_id');
                $table->index('Name');
            });
        }

        if(!Schema::hasTable('menu')){
            Schema::create('menu', function (Blueprint $table) {
                $table->uid();
                $table->string('title');
                $table->string('icon');
                $table->string('type');
                $table->text('link');
                $table->string('target',255);
                $table->uuid('parent_id')->nullable();
                $table->integer('lft');
                $table->integer('rgt');
                $table->integer('depth');
                $table->dates();
            });
        }

        if(!Schema::hasTable('shorturls')){
            Schema::create('shorturls', function (Blueprint $table) {
                $table->uid();
                $table->text('original')->unique();
                $table->string('shorten')->unique();
                $table->integer('time');
                $table->dates();
                $table->primary(['id','ShortenUrls']);
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governorates');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('shorturls');
    }
};//
