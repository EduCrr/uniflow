<?php

namespace App\Exports;
use App\Models\Demanda;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DemandasExportJobs implements FromCollection, WithHeadings
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
                $demandasCriadas[$month] = Demanda::select('id', 'criado', 'finalizada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $this->id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->count();
                $demandasFinalizadas[$month] = Demanda::select('id', 'criado', 'finalizada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $this->id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('finalizada', 1)->count();
            }
    
            $demandasMesesCriadas = [];
    
            foreach ($demandasCriadas as $indice => $array) {
                if (!empty($array)) {
                    $demandasMesesCriadas[] = [
                        'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')], 
                        'criadas' => $array
                    ];
                } else {
                    $demandasMesesCriadas[] = [
                        'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')],
                        'criadas' => 0
                    ];
                }
            }
    
            $demandaMesesFinalizadas = [];
    
            foreach ($demandasFinalizadas as $indice => $array) {
                if (!empty($array)) {
                    $demandaMesesFinalizadas[] = [
                        'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')], 
                        'finalizadas' => $array
                    ];
                } else {
                    $demandaMesesFinalizadas[] = [
                        'mes' => $meses[Carbon::createFromFormat('!m', $indice)->format('M')],
                        'finalizadas' => 0
                    ];
                }
            }  
            
            $resultadosDemanda = [];
    
            foreach($demandasMesesCriadas as $c){
                foreach($demandaMesesFinalizadas as $f){
                    if($c['mes'] == $f['mes']){
                        $resultadosDemanda[] = [
                            "mes" => $c['mes'],
                            'criadas' => $c['criadas'],
                            'finalizadas' => $f['finalizadas']
                        ];
                    }
                }
            }

        return collect($resultadosDemanda);
    }

    public function headings(): array
    {
        return [
            'Mês',
            'Criadas',
            'Finalizadas'
        ];
    }
}
