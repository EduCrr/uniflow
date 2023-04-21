<?php

namespace App\Exports;
use App\Models\Demanda;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DemandasExportPrazos implements FromCollection, WithHeadings
{
    use Exportable;

    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

   
    public function collection(): Collection
    {

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

        $currentYear = date('Y');
            //demandas infos
        for ($month = 1; $month <= 12; $month++) {
            $demandasPrazo[$month] = Demanda::select('id', 'atrasada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $this->id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('atrasada', 0)->where('finalizada', 1)->count();
            $demandasAtrasada[$month] = Demanda::select('id', 'atrasada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $this->id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('atrasada', 1)->where('finalizada', 1)->count();
        }

        $demandasMesesAtrasadas = [];
        foreach ($demandasAtrasada as $indice => $array) {
            if (!empty($array)) {
                $demandasMesesAtrasadas[] = [
                    'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')], 
                    'atrasadas' => $array
                ];
            } else {
                $demandasMesesAtrasadas[] = [
                    'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')],
                    'atrasadas' => 0
                ];
            }
        }

        $demandasMesesNoPrazo = [];
        foreach ($demandasPrazo as $indice => $array) {
            if (!empty($array)) {
                $demandasMesesNoPrazo[] = [
                    'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')], 
                    'prazo' => $array
                ];
            } else {
                $demandasMesesNoPrazo[] = [
                    'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')],
                    'prazo' => 0
                ];
            }
        }

        $resultadosDemandaPrazos = [];
        //juntar atrasadas e no prazo
        foreach($demandasMesesAtrasadas as $c){
            foreach($demandasMesesNoPrazo as $f){
                if($c['mes'] == $f['mes']){
                    $resultadosDemandaPrazos[] = [
                        "mes" => $c['mes'],
                        'atrasadas' => $c['atrasadas'],
                        'prazo' => $f['prazo']
                    ];
                }
            }
        }

        return collect($resultadosDemandaPrazos);
    }

    public function headings(): array
    {
        return [
            'Mês',
            'Atrasadas',
            'Em prazo'
        ];
    }
}
