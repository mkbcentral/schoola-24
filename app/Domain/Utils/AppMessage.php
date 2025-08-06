<?php

namespace App\Domain\Utils;

class AppMessage
{
    const LOGGED_IN_SUCCESS = "Connexion bien établie !";
    const DATA_SAVED_SUCCESS = "Donnée bien sauvegardée";
    const QRCODE_GENERATED_SUCCESSFULLY = "QRCode bien généré";
    const DATA_UPDATED_SUCCESS = "Donnée bien mises à jour";
    const DATA_DELETED_SUCCESS = "Donnée bien rétirée";
    const SMS_SENT = "Sms bien envoyé";
    const EXECPTION_ERROR = "Le serveur a rencontré un problème";

    const ACTION_FAILLED = "Echec de l'opération !";
    const ACTION_SUCCESS = "Action bien réalisé!";
    const DATA_DELETED_FAILLED = "Impossible de réaliser l'opoération";
    const LOGGED_IN_FAILLED = "Email ou mot de password incorrect !";
    const LOGGED_IN_FAILLED_TO_UNACTIVATE_USER = "Désolé, votre compte est déactivé";
}
