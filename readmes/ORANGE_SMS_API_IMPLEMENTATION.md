# 📱 Implémentation Orange SMS API - Documentation

## 🎯 Vue d'ensemble

Cette implémentation suit strictement la **documentation officielle Orange SMS API v2.0** pour les pays d'Afrique et du Moyen-Orient.

**Documentation officielle** : https://developer.orange.com/apis/sms/getting-started

---

## ✅ Changements majeurs

### 1. **Authentification OAuth 2.0**
- ✅ Utilisation de OAuth 2.0 v3 avec Basic Authentication
- ✅ Token mis en cache pendant 55 minutes (durée de vie : 1h)
- ✅ Renouvellement automatique du token

### 2. **Structure de requête conforme**
```json
{
  "outboundSMSMessageRequest": {
    "address": "tel:+243971330007",
    "senderAddress": "tel:+2430000",
    "outboundSMSTextMessage": {
      "message": "Votre message ici"
    }
  }
}
```

### 3. **Endpoints corrects**
- Token : `https://api.orange.com/oauth/v3/token`
- Envoi SMS : `https://api.orange.com/smsmessaging/v1/outbound/tel%3A%2B{country_sender_number}/requests`
- Admin (solde) : `https://api.orange.com/sms/admin/v1/contracts`
- Statistiques : `https://api.orange.com/sms/admin/v1/statistics`

### 4. **URL Encoding**
Le `senderAddress` dans l'URL doit être URL-encodé :
- `tel:+2430000` → `tel%3A%2B2430000`

---

## ⚙️ Configuration

### 1. Variables d'environnement (.env)

```env
# Orange SMS API Configuration (mediumart/orange-sms)
ORANGE_SMS_CLIENT_ID=votre_client_id_ici
ORANGE_SMS_CLIENT_SECRET=votre_client_secret_ici
ORANGE_SMS_SENDER_PHONE=+2430000
ORANGE_SMS_SENDER_NAME=SCHOOLA
ORANGE_SMS_COUNTRY_CODE=COD
ORANGE_SMS_TOKEN_URL=https://api.orange.com/oauth/v3/token
ORANGE_SMS_API_URL=https://api.orange.com/smsmessaging/v1

# Activer les notifications SMS
ENABLE_SMS_NOTIFICATIONS=true
```

### 2. Country Sender Numbers par pays

| Pays | Code ISO | country_sender_number |
|------|----------|-----------------------|
| RD Congo | COD | tel:+2430000 |
| Cameroun | CMR | tel:+2370000 |
| Côte d'Ivoire | CIV | tel:+2250000 |
| Sénégal | SEN | tel:+2210000 |
| Mali | MLI | tel:+2230000 |
| Burkina Faso | BFA | tel:+2260000 |
| Guinée Conakry | GIN | tel:+2240000 |
| Madagascar | MDG | tel:+2610000 |

**Source** : [Documentation Orange - Section 3.3](https://developer.orange.com/apis/sms/getting-started)

---

## 📘 Utilisation

### 1. Envoi d'un SMS simple

```php
use App\Domain\Helpers\SmsNotificationHelper;

try {
    $result = SmsNotificationHelper::sendOrangeSMS(
        to: '+243971330007',
        message: 'Bonjour, votre paiement a été enregistré avec succès.'
    );
    
    // Résultat
    // [
    //     'success' => true,
    //     'resource_id' => 'xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
    //     'response' => [...],
    //     'status' => 201
    // ]
    
    $resourceId = $result['resource_id']; // Pour tracking et DR (Delivery Receipt)
    
} catch (Exception $e) {
    Log::error('Erreur SMS: ' . $e->getMessage());
}
```

### 2. Envoi avec sender name personnalisé

```php
$result = SmsNotificationHelper::sendOrangeSMS(
    to: '+243971330007',
    message: 'Rappel: Votre frais scolaire arrive à échéance.',
    senderName: 'SCHOOLA' // Max 11 caractères alphanumériques
);
```

⚠️ **Important** : Le sender name doit être enregistré et validé par Orange au préalable via [ce formulaire](https://developer.orange.com/sms-api-queries/).

### 3. Vérifier le solde SMS

```php
try {
    $balance = SmsNotificationHelper::checkBalance('COD');
    
    // Résultat
    // [
    //     'success' => true,
    //     'contracts' => [
    //         [
    //             'country' => 'COD',
    //             'availableUnits' => 120,
    //             'status' => 'ACTIVE',
    //             'expirationDate' => '2026-02-15T15:04:20.653Z',
    //             ...
    //         ]
    //     ]
    // ]
    
    $units = $balance['contracts'][0]['availableUnits'];
    $expiration = $balance['contracts'][0]['expirationDate'];
    
} catch (Exception $e) {
    Log::error('Erreur solde: ' . $e->getMessage());
}
```

### 4. Récupérer les statistiques d'usage

```php
try {
    $stats = SmsNotificationHelper::getUsageStatistics(
        countryCode: 'COD',
        appId: null // Optionnel
    );
    
    $usage = $stats['statistics']['partnerStatistics']['statistics'];
    
} catch (Exception $e) {
    Log::error('Erreur statistiques: ' . $e->getMessage());
}
```

---

## 🔄 Flux d'authentification

```
┌─────────────────────────────────────────┐
│ 1. Vérifier le cache du token          │
│    Cache::get('orange_sms_access_token')│
└─────────────────┬───────────────────────┘
                  │
                  ↓
          Token existe ?
              /    \
            OUI    NON
             │      │
             │      ↓
             │   ┌──────────────────────────────┐
             │   │ 2. Obtenir nouveau token     │
             │   │    POST /oauth/v3/token      │
             │   │    Basic Auth (base64)       │
             │   │    grant_type=client_credentials│
             │   └──────────┬───────────────────┘
             │              │
             │              ↓
             │   ┌──────────────────────────────┐
             │   │ 3. Mettre en cache (55 min) │
             │   │    Cache::put(...)           │
             │   └──────────┬───────────────────┘
             │              │
             └──────────────┘
                     │
                     ↓
          ┌──────────────────────────┐
          │ 4. Utiliser le token     │
          │    Bearer {access_token} │
          └──────────────────────────┘
```

---

## 📊 Gestion des erreurs

### Erreurs courantes

| Code HTTP | Erreur | Solution |
|-----------|--------|----------|
| 401 | Expired credentials | Le token a expiré, un nouveau sera automatiquement demandé |
| 400 | requestError | Vérifier le sender name (max 11 char, pas de caractères spéciaux) |
| 403 | Insufficient balance | Acheter un nouveau bundle |
| 404 | Resource not found | Vérifier l'endpoint et le country_sender_number |

### Logs

Tous les appels sont loggés :

```php
// Succès
Log::info('SMS envoyé avec succès via Orange API', [
    'to' => '+243971330007',
    'resource_id' => 'xxx-xxx-xxx',
    'status' => 201
]);

// Erreur
Log::error('Erreur lors de l\'envoi du SMS Orange', [
    'to' => '+243971330007',
    'error' => 'Message d\'erreur',
    'trace' => '...'
]);
```

---

## 🔔 Delivery Receipt (DR)

### Principe

Chaque SMS envoyé génère un **Delivery Receipt** dans les 24h. Pour les recevoir, vous devez :

1. Créer un endpoint HTTPS sécurisé (PORT 443)
2. Retourner `HTTP 200 OK` pour confirmer la réception
3. Enregistrer l'URL via [ce formulaire](https://developer.orange.com/sms-api-queries/)

### Format du DR reçu

```json
{
  "deliveryInfoNotification": {
    "callbackData": "xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
    "deliveryInfo": {
      "address": "tel:+243971330007",
      "deliveryStatus": "DeliveredToTerminal"
    }
  }
}
```

### Status possibles

| Status | Signification |
|--------|---------------|
| `DeliveredToTerminal` | ✅ SMS bien reçu par le téléphone |
| `DeliveredToNetwork` | 📡 SMS acheminé vers le réseau |
| `DeliveryImpossible` | ❌ Échec de réception (peut encore être délivré plus tard) |
| `MessageWaiting` | ⏳ SMS en file d'attente |
| `DeliveryUncertain` | ❓ Status inconnu (acheminé par un autre réseau) |

---

## 🧪 Test de l'implémentation

### Commande Artisan (à créer)

```bash
php artisan sms:test +243971330007 "Message de test"
```

### Dans un composant Livewire

```php
public function testSms(): void
{
    try {
        $result = SmsNotificationHelper::sendOrangeSMS(
            to: '+243971330007',
            message: 'Test depuis Schoola - ' . now()->format('H:i:s')
        );
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'SMS envoyé ! ID: ' . $result['resource_id']
        ]);
        
    } catch (Exception $e) {
        $this->dispatch('alert', [
            'type' => 'error',
            'message' => 'Erreur: ' . $e->getMessage()
        ]);
    }
}
```

---

## 📈 Bonnes pratiques

### ✅ À FAIRE

1. **Vérifier le solde régulièrement** avant les envois en masse
2. **Stocker le `resource_id`** pour tracer les SMS et corréler les DR
3. **Limiter à 5 SMS/seconde** (TPS maximum selon Orange)
4. **Utiliser le cache du token** (éviter de redemander à chaque envoi)
5. **Valider les numéros** avant l'envoi (format international)

### ❌ À ÉVITER

1. Hardcoder les credentials dans le code
2. Envoyer des SMS vers des numéros fixes (erreur 400)
3. Utiliser un sender name non validé par Orange
4. Ignorer les erreurs 401 (token expiré)
5. Négliger la gestion des exceptions

---

## 🔐 Sécurité

- ✅ Credentials stockés dans `.env` (jamais en dur)
- ✅ Token mis en cache pour éviter les appels répétés
- ✅ Logs des erreurs sans exposer les données sensibles
- ✅ Validation des numéros de téléphone
- ✅ Gestion des exceptions

---

## 📚 Ressources

- [Documentation Orange SMS API](https://developer.orange.com/apis/sms/getting-started)
- [OAuth 2.0 Guide](https://developer.orange.com/tech_guide/2-legged-oauth-2-v3/)
- [Formulaire de contact Orange](https://developer.orange.com/sms-api-queries/)
- [ISO 3166 Country Codes](http://en.wikipedia.org/wiki/ISO_3166-1_alpha-3)

---

## 🆘 Support

En cas de problème :

1. Vérifier les logs : `storage/logs/laravel.log`
2. Tester l'authentification : appeler `checkBalance()`
3. Valider la configuration dans `.env`
4. Consulter la documentation Orange
5. Contacter l'équipe Orange via le formulaire

---

**Dernière mise à jour** : Janvier 2026  
**Version** : 2.0.0  
**Compatibilité** : Orange SMS API v2.0, Laravel 11+
