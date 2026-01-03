<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('channel', ['email', 'whatsapp', 'push']);
            $table->string('notification_type');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            
            $table->unique(['user_id', 'channel', 'notification_type'], 'notif_prefs_user_channel_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
