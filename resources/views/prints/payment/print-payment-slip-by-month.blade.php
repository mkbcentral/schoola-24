<x-print-layout>
    @php
        $total_usd = 0;
        $total_cdf = 0;
    @endphp
    <div class="">
        <h4 class="text-center">BORDEREAU DE VERSEMENT MENSUEL</h4>
        <span><strong>Mois opération: </strong>{{ format_fr_month_name($month) }}</span>
        <table class="table table-bordered table-sm ">
            <thead class="table-dark border border-black">
                <tr class="cursor-hand bg-app">
                    <th class="text-center">#</th>
                    <th>TYPE FRAIS</th>
                    <th class="text-right">MT USD</th>
                    <th class="text-right">MT CDF</th>
                </tr>
            </thead>
            @if ($categoryFees->isEmpty())
                <tr>
                    <td colspan="4"><x-errors.data-empty /></td>
                </tr>
            @else
                <tbody class="">
                    @foreach ($categoryFees as $index => $categoryFee)
                        @if ($categoryFee->getAmountByMonth($month) >= 1)
                            <tr wire:key='{{ $categoryFee->id }}' class="border border-black">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $categoryFee->name }}</td>
                                <td class="text-right">
                                    {{ $categoryFee->currency == 'USD' ? app_format_number($categoryFee->getAmountByMonth($month), 1) : '-' }}
                                </td>
                                <td class="text-right">
                                    {{ $categoryFee->currency == 'CDF' ? app_format_number($categoryFee->getAmountByMonth($month), 1) : '-' }}
                                </td>
                            </tr>
                            @php
                                if ($categoryFee->currency === 'USD') {
                                    $total_usd += $categoryFee->getAmountByMonth($month);
                                } else {
                                    $total_cdf += $categoryFee->getAmountByMonth($month);
                                }
                            @endphp
                        @endif
                    @endforeach
                    <tr class="text-uppercase bg-dark text-white text-bold">
                        <td colspan="2" class="text-right">Total</td>
                        <td class="text-right">{{ app_format_number($total_usd, 1) }} USD</td>
                        <td class="text-right">{{ app_format_number($total_cdf, 1) }} CDF</td>
                    </tr>
                </tbody>
            @endif
        </table>
        <x-widget.finance-info />
    </div>widget.finance-info
</x-print-layout>
