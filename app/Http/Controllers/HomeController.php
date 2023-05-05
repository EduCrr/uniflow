<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demanda;
use App\Models\Marca;
use App\Models\Notificacao;
use App\Models\Comentario;
use App\Models\DemandaUsuario;
use App\Models\Agencia;
use App\Models\Questionamento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{   

    public function homeIndex(){
        $user = Auth::User();
        if(Auth::check()){
            if($user->tipo === 'admin'){
              return redirect('/admin');
            }else if($user->tipo === 'colaborador'){
              return redirect('/dashboard');
            }else{
                
                $events = [];
                
                $demandas = Demanda::where('excluido', null)
                ->where('etapa_1', 1)
                ->where('etapa_2', 1)
                ->where(function ($query) use ($user) {
                    $query->whereHas('demandasUsuario', function ($query) use ($user) {
                        $query->where('usuario_id', $user->id);
                    });
                })
                ->with(['marcas' => function ($query) {
                    $query->where('excluido', null);
                }])
                ->with(['agencia' => function ($query) {
                    $query->where('excluido', null);
                }])
                ->with(['demandasReabertas' => function ($query) {
                    $query->where('finalizado', null);
                }])
                ->withCount(['questionamentos as count_questionamentos' => function ($query) {
                    $query->where('visualizada_ag', 0)->where('excluido', null);
                }])
                ->withCount(['questionamentos as count_respostas' => function ($query) {
                    $query->whereHas('respostas', function ($query) {
                        $query->where('visualizada_ag', 0);
                    });
                }])
                ->orderBy('id', 'DESC')
                ->paginate(15);

                foreach($demandas as $key => $item){
                    if ($item->finalizada == 1) {
                        $porcentagem = 100;
                    } else {
                        // Obter o total de prazosDaPauta finalizados da demanda
                        $totalFinalizados = $item->prazosDaPauta()->whereNotNull('finalizado')->count();
                    
                        // Obter o total de prazosDaPauta não finalizados da demanda
                        $totalNaoFinalizados = $item->prazosDaPauta()->whereNull('finalizado')->count();
                       
                        // Calcular a porcentagem com base nos prazosDaPauta finalizados e não finalizados da demanda
                        $totalPrazos = $totalFinalizados + $totalNaoFinalizados;
                        if ($totalPrazos == 0) {
                            $porcentagem = 0;
                        } elseif ($totalFinalizados == 0) {
                            $porcentagem = 10;
                        } else {
                            $porcentagem = round(($totalFinalizados / $totalPrazos) * 95);
                        }
                    }
        
                    // Adicionar a porcentagem como um atributo da demanda
                    $item->porcentagem = $porcentagem;
                    
                    $demandasReabertas = $item->demandasReabertas;
                    if ($demandasReabertas->count() > 0) {
                        $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                        $item->final = $sugerido;
                    }
                }
                

                $demandasEvents = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->with(['demandasReabertas' => function ($query) {
                    $query->where('finalizado', null);
                }])->where(function ($query) use ($user) {
                    $query->whereHas('demandasUsuario', function ($query) use ($user) {
                        $query->where('usuario_id', $user->id);
                    });
                })->where('em_pauta', '1')->where('pausado', 0)->get();

                if($demandasEvents != null){
                    foreach($demandasEvents as $key => $demanda){
                        $demandasReabertas = $demanda->demandasReabertas;
                        if ($demandasReabertas->count() > 0) {
                            $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                            $demanda->final = $sugerido;
                        }
                        $events[] = [
                            'title' => $demanda->titulo,
                            'start' => $demanda->inicio,
                            'end' => $demanda->final,
                            'url' => route('Job', ['id' => $demanda->id]),
                            'color' => $demanda->cor,
                        ];
                        
                    }
                }else{
                    $events = [];
                }
                
                // $emPautaCount = count($demandasEvents);
                // $finalizadosCount =  Demanda::whereIn('agencia_id', $idsAg)->where('finalizada', '1')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('pausado', 0)->count();
                // $pendentesCount = Demanda::whereIn('agencia_id', $idsAg)->where('finalizada', '0')->where('etapa_1', 1)->where('etapa_2', 1)->where('em_pauta', '0')->where('entregue', '0')->where('pausado', '0')->where('excluido', null)->count();
                // $pausadosCount = Demanda::where('agencia_id', $idsAg)->where('pausado', '1')->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->count();
                // $entregueCount = Demanda::where('agencia_id', $idsAg)->where('entregue', '1')->where('finalizada', '0')->where('pausado', 0)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->count();
        
                // $questsIds =  Demanda::select('id')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('pausado', 0)->whereIn('agencia_id', $idsAg)->orderBy('criado', 'DESC')->take(15)->get();
             
                // $quests =  Questionamento::whereIn('demanda_id', $questsIds)->where('excluido', null)->orderBy('criado', 'DESC')->with('respostas.usuario')->take(15)->get();

                return view('index', [
                    'demandas' => $demandas,
                    'events' => $events,
                    // 'quests' => $quests,
                    // 'emPautaCount' => $emPautaCount,
                    // 'finalizadosCount' => $finalizadosCount,
                    // 'pendentesCount' => $pendentesCount,
                    // 'pausadosCount' => $pausadosCount,
                    // 'entregueCount' => $entregueCount
                ]);
            }
        }else{
            return view('login');
        }
    }

    

}
