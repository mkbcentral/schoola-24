<?php

namespace App\Domain\Utils;

class AppMessage
{
    const LOGGED_IN_SUCCESS = "Connexion bien établie !";
    const DATA_SAVED_SUCCESS = "Donnée bien sauvegardée";
    const DATA_UPDATED_SUCCESS = "Donnée bien mises à jour";
    const DATA_DELETED_SUCCESS = "Donnée bien rétirée";

    const ACTION_FAILLED = "Echec de l'opération !";
    const DATA_DELETED_FAILLED = "Impossible de réaliser l'opoération";
    const LOGGED_IN_FAILLED = "Email ou mot de password incorrect !";
}
