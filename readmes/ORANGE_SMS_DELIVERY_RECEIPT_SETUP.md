# Configuration des Routes pour Orange SMS Delivery Receipt

## Route à ajouter dans `routes/api.php`

```php
use App\Http\Controllers\API\SmsDeliveryReceiptController;

// Orange SMS Delivery Receipt endpoint
Route::post('/sms/delivery-receipt', [SmsDeliveryReceiptController::class, 'receive'])
    ->name('sms.delivery-receipt');

// Ping endpoint pour vérifier la disponibilité
Route::get('/sms/ping', [SmsDeliveryReceiptController::class, 'ping'])
    ->name('sms.ping');
```

## URL à communiquer à Orange

Une fois déployé sur votre serveur HTTPS, fournir cette URL à Orange via le formulaire :
https://developer.orange.com/sms-api-queries/

**URL complète** :
```
https://votre-domaine.com/api/sms/delivery-receipt
```

## Exigences Orange

- ✅ Doit être en **HTTPS** (certificat SSL valide, pas auto-signé)
- ✅ Accessible sur le **PORT 443**
- ✅ Doit retourner **HTTP 200 OK** pour confirmer la réception
- ✅ L'IP publique d'Orange doit être whitelistée (fournie par Orange après enregistrement)

## Test de l'endpoint

```bash
# Vérifier que l'endpoint est accessible
curl https://votre-domaine.com/api/sms/ping

# Tester la réception d'un DR (simulé)
curl -X POST https://votre-domaine.com/api/sms/delivery-receipt \
  -H "Content-Type: application/json" \
  -d '{
    "deliveryInfoNotification": {
      "callbackData": "test-resource-id",
      "deliveryInfo": {
        "address": "tel:+243971330007",
        "deliveryStatus": "DeliveredToTerminal"
      }
    }
  }'
```

## Sécurité recommandée (optionnel)

Pour sécuriser l'endpoint, vous pouvez ajouter une vérification de l'IP source :

```php
// Dans le controller
if (!in_array($request->ip(), config('services.orange_sms.allowed_ips', []))) {
    Log::warning('DR reçu d\'une IP non autorisée', ['ip' => $request->ip()]);
    return response()->json(['message' => 'Forbidden'], 403);
}
```

Puis dans `config/services.php` :

```php
'orange_sms' => [
    // ... autres configs
    'allowed_ips' => [
        'XX.XX.XX.XX', // IP fournie par Orange
    ],
],
```
