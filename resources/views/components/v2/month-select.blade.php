@props(['model' => 'month'])

<label class="form-label">Mois</label>
<select wire:model.live="{{ $model }}" class="form-select">
    <option value="">Tous les mois</option>
    <option value="01">Janvier</option>
    <option value="02">Février</option>
    <option value="03">Mars</option>
    <option value="04">Avril</option>
    <option value="05">Mai</option>
    <option value="06">Juin</option>
    <option value="07">Juillet</option>
    <option value="08">Août</option>
    <option value="09">Septembre</option>
    <option value="10">Octobre</option>
    <option value="11">Novembre</option>
    <option value="12">Décembre</option>
</select>
