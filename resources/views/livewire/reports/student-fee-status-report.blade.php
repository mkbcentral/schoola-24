<div>
    <h2>Rapport Statut Paiement des Élèves</h2>
    <form wire:submit.prevent="loadResults" class="mb-4">
        <div>
            <label>Catégories de frais :</label>
            <select wire:model="categoryFeeIds" multiple>
                @foreach ($allCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Option :</label>
            <select wire:model="optionId">
                <option value="">Toutes</option>
                @foreach ($allOptions as $opt)
                    <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Classe :</label>
            <select wire:model="classRoomId">
                <option value="">Toutes</option>
                @foreach ($allClassRooms as $cr)
                    <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Afficher</button>
    </form>

    @if ($results)
        <table border="1" cellpadding="4" cellspacing="0">
            <thead>
                <tr>
                    <th>Élève</th>
                    @if (isset($results[0]['category']))
                        <th>Catégorie</th>
                        <th>Statut</th>
                    @else
                        <th
                            v-for="mois in ['AOUT','SEPTEMBRE','OCTOBRE','NOVEMBRE','DECEMBRE','JANVIER','FEVRIER','MARS','AVRIL','MAI','JUIN']">
                            Mois</th>
                        <th>Paiement récent (3j)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $row)
                    <tr>
                        <td>{{ $row['student'] }}</td>
                        @if (isset($row['category']))
                            <td>{{ $row['category'] }}</td>
                            <td>{{ $row['status'] }}</td>
                        @else
                            @foreach (['AOUT', 'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DECEMBRE', 'JANVIER', 'FEVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN'] as $mois)
                                <td>{{ $row['months'][$mois] ?? '-' }}</td>
                            @endforeach
                            <td>
                                @if ($row['recent_payment_status'])
                                    <span style="color:green">Oui</span>
                                @else
                                    <span style="color:red">Non</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
