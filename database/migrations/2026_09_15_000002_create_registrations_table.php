<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('ktp_number')->nullable();
            $table->string('ktp_hash')->unique();
            $table->string('email_private');
            $table->string('email_user')->nullable();
            $table->string('worker_number')->nullable();
            $table->string('company_name');
            $table->string('function');
            $table->string('position');
            $table->string('employment_status');
            $table->string('training_status')->default('baru'); // baru | refreshment
            $table->date('training_date')->nullable();

            $table->string('status')->default('registered');
            // registered | invited | confirmed | attended | cancelled | no_show
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['batch_id', 'status']);
            $table->index('ktp_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
