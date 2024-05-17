<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('Governorates')){
            Schema::create('Governorates', function (Blueprint $table) {
                $table->id();
                $table->string('Name');
                $table->string('English');
                $table->nullableTimestamps(0);
                $table->softDeletes($column = 'deleted_at', $precision = 0);
            }); 
        }
        if(!Schema::hasTable('Cities')){
            Schema::create('Cities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('Gov_id')->constrainded(
                    table: 'Governorates'
                )->cascadeOnUpdate()->cascadeOnDelete();
                $table->string('Name');
                $table->string('English');
                $table->char('LandLineCode',3);
                $table->nullableTimestamps(0);
                $table->softDeletes($column = 'deleted_at', $precision = 0);
                $table->primary(['id','Gov_id']);
                $table->index('Gov_id');
                $table->index('Name');
            });    
        } 
        
        if(!Schema::hasTable('Menu')){
            Schema::create('Menu', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('icon');
                $table->string('protocol');
                $table->text('link');
                $table->string('target',20);
                $table->integer('parent_id');
                $table->integer('lft');
                $table->integer('rgt');
                $table->integer('depth');
                $table->nullableTimestamps(0);
                $table->softDeletes($column = 'deleted_at', $precision = 0);
            });
        }
        
        if(!Schema::hasTable('ShortUrls')){
            Schema::create('ShortUrls', function (Blueprint $table) {
                $table->id();
                $table->text('OriginalUrls')->unique();
                $table->string('ShortenUrls')->unique();
                $table->integer('time');
                $table->nullableTimestamps(0);
                $table->softDeletes($column = 'deleted_at', $precision = 0);
                $table->primary(['id','ShortenUrls']);
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Governorates');
        Schema::dropIfExists('Cities');
        Schema::dropIfExists('Menu');
        Schema::dropIfExists('ShortUrls');
    }
};//
