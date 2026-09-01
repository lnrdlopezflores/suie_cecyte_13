<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 50)->unique();
            $table->text('valor')->nullable();
            $table->timestamps();
        });

        // Insertar colores por defecto del SUIE
        DB::table('configuraciones_sistema')->insert([
            ['clave' => 'color_primario', 'valor' => '#841B44', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'color_hover',    'valor' => '#681535', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'color_light',    'valor' => '#fdf2f4', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_sistema');
    }
};
