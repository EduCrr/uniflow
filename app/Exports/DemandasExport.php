<?php

namespace App\Exports;
use App\Models\DemandaTempo;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DemandasExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function collection(): Collection
    {
        $currentYear = date('Y');
        $daysCountByMonth = [];

        for ($month = 1; $month <= 12; $month++) {
            $daysCountByMonth[$month] = DemandaTempo::select('criado', 'iniciado', 'finalizado')->where('agencia_id', $this->id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('finalizado', '!=', null)->get();
        }

        $meses = [
            'Jan' => 'Jan',
            'Feb' => 'Fev',
            'Mar' => 'Mar',
            'Apr' => 'Abr',
            'May' => 'Mai',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Ago',
            'Sep' => 'Set',
            'Oct' => 'Out',
            'Nov' => 'Nov',
            'Dec' => 'Dez',
        ];

        $mediaMeses = [];

        foreach ($daysCountByMonth as $indice => $array) {
            if (!empty($array)) {
                $totalDias = 0;
                $qtdArrays = count($array);
                foreach ($array as $item) {
                    $iniciado = Carbon::parse($item['iniciado']);
                    $finalizado = Carbon::parse($item['finalizado']);
        
                    // se o hora de $iniciado for maior que 17h, não contar esse dia
                    if ($iniciado->format('H:i:s') >= '17:00:00') {
                        $iniciado->addDay();
                    }
        
                    // filtra os dias úteis (segunda a sexta)
                    $diferencaEmDias = $finalizado->diffInDaysFiltered(function($date) {
                        return !$date->isWeekend();
                    }, $iniciado, true);
        
                    if ($diferencaEmDias < 1) {
                        $diferencaEmDias = 0.5;
                    }
        
                    $totalDias += $diferencaEmDias;
                }
                $mediaM = $qtdArrays > 0 ? $totalDias / $qtdArrays : 0; // Verifica se $qtdArrays é maior que 0 antes de fazer a divisão
                if($mediaM < 1){
                   $mediaM = number_format($mediaM, 1); // formata o número com uma casa decimal
                }else{
                 $mediaM = round($mediaM, 0); // arredonda para o número inteiro mais próximo
                 $mediaM = floor($mediaM); // arredonda para baixo
                }
                
                $mediaMeses[] = [
                    'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')], // Obtém o nome do mês a partir do número do índice
                    'dias' => $mediaM
                ];
            } else {
                $mediaMeses[] = [
                    'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')],
                    'dias' => 0
                ];
            }
        }


        return collect($mediaMeses);
    }

    public function headings(): array
    {
        return [
            'Mês',
            'Média de dias'
        ];
    }
}
