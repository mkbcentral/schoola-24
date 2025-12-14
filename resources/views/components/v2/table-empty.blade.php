@props([
    'colspan',
    'message',
])

<tr>
    <td colspan="{{ $colspan }}" class="text-center py-4">
        <div class="text-muted text-center">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            {{ $message }}
        </div>
    </td>
</tr>
