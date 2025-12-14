<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter des index pour optimiser les performances des requêtes
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Index pour les recherches fréquentes
            $table->index(['is_paid', 'created_at'], 'idx_payments_is_paid_created');
            $table->index(['month', 'is_paid'], 'idx_payments_month_paid');
            $table->index(['registration_id', 'is_paid'], 'idx_payments_registration_paid');
            $table->index(['scolar_fee_id'], 'idx_payments_scolar_fee');
            $table->index(['user_id', 'created_at'], 'idx_payments_user_created');
        });

        Schema::table('registrations', function (Blueprint $table) {
            // Index pour les jointures fréquentes
            $table->index(['school_year_id', 'student_id'], 'idx_registrations_year_student');
            $table->index(['class_room_id', 'school_year_id'], 'idx_registrations_class_year');
        });

        Schema::table('students', function (Blueprint $table) {
            // Index pour les recherches par nom
            $table->index(['responsible_student_id'], 'idx_students_responsible');
            $table->index(['name'], 'idx_students_name');
        });

        Schema::table('scolar_fees', function (Blueprint $table) {
            // Index pour les jointures
            $table->index(['category_fee_id'], 'idx_scolar_fees_category');
        });
    }

    /**
     * Supprimer les index
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_is_paid_created');
            $table->dropIndex('idx_payments_month_paid');
            $table->dropIndex('idx_payments_registration_paid');
            $table->dropIndex('idx_payments_scolar_fee');
            $table->dropIndex('idx_payments_user_created');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex('idx_registrations_year_student');
            $table->dropIndex('idx_registrations_class_year');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_responsible');
            $table->dropIndex('idx_students_name');
        });

        Schema::table('scolar_fees', function (Blueprint $table) {
            $table->dropIndex('idx_scolar_fees_category');
        });
    }
};
