<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->longText('body');
            $table->enum('status', ['draft', 'sending', 'sent'])->default('draft');
            // Targeting filters
            $table->json('filter_product_ids')->nullable();   // null = tutti
            $table->json('filter_category_ids')->nullable();  // null = tutte
            $table->unsignedInteger('min_orders')->default(0);
            $table->boolean('target_registered')->default(true);
            $table->boolean('target_guests')->default(true);
            // Stats
            $table->unsignedInteger('total_recipients')->nullable();
            $table->unsignedInteger('sent_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('email_campaign_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('email_campaigns')->onDelete('cascade');
            $table->string('email');
            $table->string('name')->nullable();
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaign_logs');
        Schema::dropIfExists('email_campaigns');
    }
};
