<?php

/**
 * EXEMPLES D'UTILISATION DU SERVICE D'INSCRIPTION
 * 
 * Ce fichier contient des exemples pratiques d'utilisation du RegistrationService
 * dans différents contextes (contrôleurs, commandes, jobs, etc.)
 */

namespace App\Examples;

use App\DTOs\Registration\CreateRegistrationDTO;
use App\DTOs\Registration\CreateStudentDTO;
use App\DTOs\Registration\RegistrationFilterDTO;
use App\DTOs\Registration\UpdateRegistrationDTO;
use App\Services\Registration\RegistrationService;

class RegistrationServiceExamples
{
    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * EXEMPLE 1 : Inscrire un ancien élève
     */
    public function example1_registerExistingStudent()
    {
        $dto = CreateRegistrationDTO::fromArray([
            'student_id' => 15,
            'class_room_id' => 8,
            'registration_fee_id' => 3,
            // school_year_id est optionnel, utilise l'année par défaut
        ]);

        try {
            $registration = $this->registrationService->registerExistingStudent($dto);

            echo "✅ Inscription créée avec succès!\n";
            echo "Code: {$registration->code}\n";
            echo "Élève: {$registration->student->name}\n";
            echo "Classe: {$registration->classRoom->name}\n";

            return $registration;
        } catch (\Exception $e) {
            echo "❌ Erreur: {$e->getMessage()}\n";
        }
    }

    /**
     * EXEMPLE 2 : Inscrire un nouvel élève
     */
    public function example2_registerNewStudent()
    {
        // Données de l'élève
        $studentDTO = CreateStudentDTO::fromArray([
            'name' => 'Jean Dupont',
            'gender' => 'M',
            'place_of_birth' => 'Kinshasa',
            'date_of_birth' => '2010-05-15',
            'responsible_student_id' => 12,
        ]);

        // Données de l'inscription
        $registrationDTO = CreateRegistrationDTO::fromArray([
            'class_room_id' => 8,
            'registration_fee_id' => 3,
        ]);

        try {
            $registration = $this->registrationService->registerNewStudent(
                $studentDTO,
                $registrationDTO
            );

            echo "✅ Nouvel élève inscrit avec succès!\n";
            echo "ID Élève: {$registration->student->id}\n";
            echo "Nom: {$registration->student->name}\n";
            echo "Code inscription: {$registration->code}\n";

            return $registration;
        } catch (\Exception $e) {
            echo "❌ Erreur: {$e->getMessage()}\n";
        }
    }

    /**
     * EXEMPLE 3 : Récupérer les inscriptions avec filtres
     */
    public function example3_getFilteredRegistrations()
    {
        $filter = RegistrationFilterDTO::fromArray([
            'section_id' => 2,
            'gender' => 'M',
            'date_from' => '2024-09-01',
            'is_registered' => true,
        ]);

        // Avec pagination
        $registrations = $this->registrationService->getFiltered($filter, perPage: 20);

        echo "📋 Inscriptions trouvées: {$registrations->total()}\n";
        echo "Page actuelle: {$registrations->currentPage()}\n";

        foreach ($registrations as $registration) {
            echo "- {$registration->student->name} ({$registration->classRoom->name})\n";
        }

        return $registrations;
    }

    /**
     * EXEMPLE 4 : Obtenir les statistiques
     */
    public function example4_getStatistics()
    {
        $filter = RegistrationFilterDTO::fromArray([
            'section_id' => 2,
            // school_year_id utilisera l'année par défaut
        ]);

        $stats = $this->registrationService->getStats($filter);

        echo "📊 STATISTIQUES D'INSCRIPTION\n";
        echo "================================\n";
        echo "Total général: {$stats->total}\n";
        echo "Garçons: {$stats->total_male}\n";
        echo "Filles: {$stats->total_female}\n";
        echo "\n";

        echo "Par Section:\n";
        foreach ($stats->by_section as $section) {
            echo "- {$section['name']}: {$section['count']}\n";
        }

        echo "\nPar Option:\n";
        foreach ($stats->by_option as $option) {
            echo "- {$option['name']} ({$option['section_name']}): {$option['count']}\n";
        }

        echo "\nPar Classe:\n";
        foreach ($stats->by_class as $class) {
            echo "- {$class['name']} ({$class['option_name']}): {$class['count']}\n";
        }

        return $stats;
    }

    /**
     * EXEMPLE 5 : Récupérer inscriptions ET statistiques ensemble
     */
    public function example5_getFilteredWithStats()
    {
        $filter = RegistrationFilterDTO::fromArray([
            'class_room_id' => 5,
        ]);

        $result = $this->registrationService->getFilteredWithStats($filter);

        $registrations = $result['registrations'];
        $stats = $result['stats'];

        echo "📋 Liste avec statistiques\n";
        echo "Total: {$stats['total']}\n";
        echo "Inscriptions sur cette page: {$registrations->count()}\n";

        return $result;
    }

    /**
     * EXEMPLE 6 : Mettre à jour une inscription
     */
    public function example6_updateRegistration(int $registrationId)
    {
        $dto = UpdateRegistrationDTO::fromArray([
            'class_room_id' => 10,
            'is_registered' => true,
        ]);

        try {
            $registration = $this->registrationService->update($registrationId, $dto);

            echo "✅ Inscription mise à jour!\n";
            echo "Nouvelle classe: {$registration->classRoom->name}\n";

            return $registration;
        } catch (\Exception $e) {
            echo "❌ Erreur: {$e->getMessage()}\n";
        }
    }

    /**
     * EXEMPLE 7 : Marquer un élève comme ayant abandonné
     */
    public function example7_markAsAbandoned(int $registrationId)
    {
        try {
            $registration = $this->registrationService->markAsAbandoned($registrationId);

            echo "⚠️ Inscription marquée comme abandonnée\n";

            return $registration;
        } catch (\Exception $e) {
            echo "❌ Erreur: {$e->getMessage()}\n";
        }
    }

    /**
     * EXEMPLE 8 : Changer un élève de classe
     */
    public function example8_changeClass(int $registrationId, int $newClassRoomId)
    {
        try {
            $registration = $this->registrationService->changeClass(
                $registrationId,
                $newClassRoomId
            );

            echo "🔄 Classe changée avec succès!\n";
            echo "Nouvelle classe: {$registration->classRoom->name}\n";

            return $registration;
        } catch (\Exception $e) {
            echo "❌ Erreur: {$e->getMessage()}\n";
        }
    }

    /**
     * EXEMPLE 9 : Vérifier si un élève est déjà inscrit
     */
    public function example9_checkIfStudentRegistered(int $studentId)
    {
        $isRegistered = $this->registrationService->isStudentRegistered($studentId);

        if ($isRegistered) {
            echo "✅ L'élève est déjà inscrit pour cette année\n";
        } else {
            echo "ℹ️ L'élève n'est pas encore inscrit\n";
        }

        return $isRegistered;
    }

    /**
     * EXEMPLE 10 : Obtenir l'historique des inscriptions d'un élève
     */
    public function example10_getStudentHistory(int $studentId)
    {
        $registrations = $this->registrationService->getByStudentId($studentId);

        echo "📚 Historique des inscriptions de l'élève #{$studentId}\n";
        echo "========================================\n";

        foreach ($registrations as $registration) {
            echo "Année: {$registration->schoolYear->name}\n";
            echo "Classe: {$registration->classRoom->name}\n";
            echo "Code: {$registration->code}\n";
            echo "---\n";
        }

        return $registrations;
    }

    /**
     * EXEMPLE 11 : Filtrage avancé avec toutes les options
     */
    public function example11_advancedFiltering()
    {
        $filter = RegistrationFilterDTO::fromArray([
            'section_id' => 2,
            'option_id' => 5,
            'class_room_id' => 8,
            'gender' => 'F',
            'date_from' => '2024-09-01',
            'date_to' => '2024-09-30',
            'is_old' => false,        // Uniquement nouveaux élèves
            'abandoned' => false,     // Non abandonnés
            'is_registered' => true,  // Inscription confirmée
        ]);

        $result = $this->registrationService->getFilteredWithStats($filter);

        echo "🔍 Filtrage avancé appliqué\n";
        echo "Critères:\n";
        echo "- Section: 2\n";
        echo "- Option: 5\n";
        echo "- Classe: 8\n";
        echo "- Genre: Féminin\n";
        echo "- Période: Sept 2024\n";
        echo "- Nouveaux élèves uniquement\n";
        echo "\nRésultats: {$result['stats']['total']} inscriptions\n";

        return $result;
    }

    /**
     * EXEMPLE 12 : Compter les inscriptions par classe
     */
    public function example12_countByClassRoom(int $classRoomId)
    {
        $count = $this->registrationService->countByClassRoom($classRoomId);

        echo "👥 Nombre d'élèves dans la classe #{$classRoomId}: {$count}\n";

        return $count;
    }

    /**
     * EXEMPLE 13 : Exempter un élève des frais d'inscription
     */
    public function example13_markFeeExempted(int $registrationId)
    {
        try {
            $registration = $this->registrationService->markFeeExempted($registrationId);

            echo "💰 Élève exempté des frais d'inscription\n";

            return $registration;
        } catch (\Exception $e) {
            echo "❌ Erreur: {$e->getMessage()}\n";
        }
    }

    /**
     * EXEMPLE 14 : Utilisation dans une commande Artisan
     */
    public function example14_artisanCommand()
    {
        // Exemple pour une commande de migration de données
        $oldStudents = [/* données des anciens élèves */];

        $successCount = 0;
        $errorCount = 0;

        foreach ($oldStudents as $oldStudent) {
            try {
                $dto = CreateRegistrationDTO::fromArray([
                    'student_id' => $oldStudent['id'],
                    'class_room_id' => $oldStudent['class_id'],
                ]);

                $this->registrationService->registerExistingStudent($dto);
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                echo "Erreur pour l'élève {$oldStudent['id']}: {$e->getMessage()}\n";
            }
        }

        echo "\n✅ Migration terminée\n";
        echo "Succès: {$successCount}\n";
        echo "Erreurs: {$errorCount}\n";
    }

    /**
     * EXEMPLE 15 : Utilisation dans un Job
     */
    public function example15_jobUsage()
    {
        // Exemple pour un job d'importation en masse
        $data = [
            'student' => [
                'name' => 'Marie Dubois',
                'gender' => 'F',
                'place_of_birth' => 'Lubumbashi',
                'date_of_birth' => '2011-03-20',
            ],
            'registration' => [
                'class_room_id' => 5,
            ],
        ];

        try {
            $studentDTO = CreateStudentDTO::fromArray($data['student']);
            $registrationDTO = CreateRegistrationDTO::fromArray($data['registration']);

            $registration = $this->registrationService->registerNewStudent(
                $studentDTO,
                $registrationDTO
            );

            // Envoyer une notification, logger, etc.
            echo "✅ Job exécuté: Inscription #{$registration->id} créée\n";
        } catch (\Exception $e) {
            // Logger l'erreur et réessayer si nécessaire
            echo "❌ Échec du job: {$e->getMessage()}\n";
            throw $e;
        }
    }
}
