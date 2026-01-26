# 🚀 Guide de Migration Orange SMS API v2.0

## ⚡ Étapes rapides (5 minutes)

### 1. 📝 Mettre à jour `.env`

Remplacez votre configuration actuelle par :

```env
# Orange SMS API Configuration (Official API v2.0)
ORANGE_SMS_CLIENT_ID=votre_client_id_ici
ORANGE_SMS_CLIENT_SECRET=votre_secret_ici
ORANGE_SMS_SENDER_PHONE=+2430000
ORANGE_SMS_SENDER_NAME=SCHOOLA
ORANGE_SMS_COUNTRY_CODE=COD
ORANGE_SMS_TOKEN_URL=https://api.orange.com/oauth/v3/token
ORANGE_SMS_API_URL=https://api.orange.com/smsmessaging/v1

# Activer les notifications SMS
ENABLE_SMS_NOTIFICATIONS=true
```

**⚠️ Important** : 
- Le `SENDER_PHONE` doit être au format **exact** selon votre pays (voir tableau ci-dessous)
- Le `SENDER_NAME` doit être enregistré auprès d'Orange (max 11 caractères)

### 2. 🗄️ Exécuter la migration

```bash
php artisan migrate
```

Cette migration ajoute les champs nécessaires pour tracker les SMS :
- `resource_id` : ID unique Orange pour chaque SMS
- `status` : État de l'envoi (sent, delivered, failed)
- `delivery_status` : Status de livraison Orange
- `sent_at` / `delivered_at` : Timestamps

### 3. ✅ Tester l'envoi

```bash
# Tester avec votre numéro
php artisan sms:test +243971330007 "Test depuis Schoola"

# Vérifier le solde
php artisan sms:test +243971330007 --balance

# Voir les statistiques
php artisan sms:test +243971330007 --stats
```

---

## 📋 Country Sender Numbers

| Pays | Code | ORANGE_SMS_SENDER_PHONE | COUNTRY_CODE |
|------|------|-------------------------|--------------|
| **RD Congo** | +243 | `+2430000` | `COD` |
| Cameroun | +237 | `+2370000` | `CMR` |
| Côte d'Ivoire | +225 | `+2250000` | `CIV` |
| Sénégal | +221 | `+2210000` | `SEN` |
| Mali | +223 | `+2230000` | `MLI` |
| Burkina Faso | +226 | `+2260000` | `BFA` |
| Guinée Conakry | +224 | `+2240000` | `GIN` |
| Madagascar | +261 | `+2610000` | `MDG` |

---

## 🔧 Utilisation dans votre code

### Ancien code (à remplacer)

```php
// ❌ NE PLUS UTILISER
use Mediumart\Orange\SMS\SMS;
$sms = new SMS($client);
$response = $sms->message($message)->from($phone)->to($to)->send();
```

### Nouveau code (recommandé)

```php
// ✅ UTILISER CE CODE
use App\Domain\Helpers\SmsNotificationHelper;

try {
    $result = SmsNotificationHelper::sendOrangeSMS(
        to: '+243971330007',
        message: 'Votre message ici'
    );
    
    // Succès
    $resourceId = $result['resource_id']; // Conserver pour tracking
    
} catch (Exception $e) {
    // Erreur
    Log::error('SMS error: ' . $e->getMessage());
}
```

### Pour les paiements (encore mieux)

```php
use App\Services\SMS\PaymentSmsService;

$smsService = new PaymentSmsService();

// Envoyer un SMS pour un paiement
$result = $smsService->sendPaymentNotification($payment);

// Envoyer un rappel
$result = $smsService->sendPaymentReminder($payment);

// Envoi en masse
$results = $smsService->sendBulkPaymentNotifications([1, 2, 3, 4]);
```

---

## 🐛 Dépannage

### Erreur 401 (Unauthorized)

**Cause** : Credentials invalides ou token expiré

**Solution** :
1. Vérifier `ORANGE_SMS_CLIENT_ID` et `CLIENT_SECRET` dans `.env`
2. Nettoyer le cache : `php artisan cache:clear`
3. Tester l'authentification : `php artisan sms:test +243xxx --balance`

### Erreur 400 (Bad Request)

**Cause** : Format de requête incorrect ou sender name non autorisé

**Solution** :
1. Vérifier le `ORANGE_SMS_SENDER_PHONE` (format exact selon pays)
2. Le `SENDER_NAME` doit être validé par Orange
3. Vérifier le format du numéro destinataire (+243...)

### Erreur 403 (Forbidden)

**Cause** : Solde insuffisant

**Solution** :
1. Vérifier le solde : `php artisan sms:test +243xxx --balance`
2. Acheter un bundle sur https://developer.orange.com

### Les SMS ne sont pas reçus

**Checklist** :
- ✅ Le numéro est au format international (+243...)
- ✅ Le numéro est un mobile Orange (pour offre Orange Only)
- ✅ Le message ne dépasse pas 160 caractères
- ✅ Le solde SMS est positif
- ✅ Le contrat n'est pas expiré

---

## 📊 Monitoring

### Voir les logs

```bash
tail -f storage/logs/laravel.log
```

### Vérifier les SMS envoyés

```sql
SELECT * FROM sms_payments 
WHERE sent_at >= NOW() - INTERVAL 1 DAY
ORDER BY sent_at DESC;
```

### Statistiques d'envoi

```php
// Dans votre dashboard
$totalSent = SmsPayment::where('status', 'sent')->count();
$totalDelivered = SmsPayment::where('delivery_status', 'DeliveredToTerminal')->count();
$failedSms = SmsPayment::where('status', 'failed')->count();
```

---

## 🔐 Sécurité

### ✅ Bonnes pratiques appliquées

- ✅ Credentials stockés dans `.env` (jamais en code)
- ✅ Token OAuth mis en cache (55 min)
- ✅ Rate limiting : max 5 SMS/seconde
- ✅ Logs complets pour audit
- ✅ Validation des numéros
- ✅ Gestion des erreurs

---

## 📚 Ressources

- [Documentation Orange SMS API](https://developer.orange.com/apis/sms/getting-started)
- [Obtenir des credentials](https://developer.orange.com) (MyApps)
- [Acheter des bundles](https://developer.orange.com)
- [Support Orange](https://developer.orange.com/sms-api-queries/)

---

## ✅ Checklist finale

Avant de passer en production :

- [ ] Configuration `.env` complète et validée
- [ ] Migration exécutée : `php artisan migrate`
- [ ] Test d'envoi réussi : `php artisan sms:test +243xxx "Test"`
- [ ] Solde vérifié : `php artisan sms:test +243xxx --balance`
- [ ] Sender name enregistré auprès d'Orange (si personnalisé)
- [ ] Logs activés et surveillés
- [ ] Numéros de test validés
- [ ] Documentation lue et comprise

---

**🎉 Félicitations ! Votre intégration Orange SMS est prête.**

Pour toute question, consultez la [documentation complète](./ORANGE_SMS_API_IMPLEMENTATION.md).

---

**Dernière mise à jour** : Janvier 2026  
**Version** : 2.0.0
