@props(['icon' => 'inbox', 'message' => 'Aucune donnée trouvée', 'colspan' => 10])

<tr>
    <td colspan="{{ $colspan }}" class="text-center py-5">
        <div class="text-muted">
            <i class="bi bi-{{ $icon }}" style="font-size: 3rem;"></i>
            <p class="mt-3">{{ $message }}</p>
        </div>
    </td>
</tr>
