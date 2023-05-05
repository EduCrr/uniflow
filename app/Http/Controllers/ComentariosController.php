<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questionamento;
use App\Models\Demanda;
use App\Models\Agencia;
use App\Models\DemandaTempo;
use App\Models\Alteracao;
use App\Models\Notificacao;
use App\Models\ComentarioPauta;
use App\Models\DemandaReaberta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
Use Alert;
use App\Models\LinhaTempo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ComentariosController extends Controller
{
    public function delete($id){
        $user = Auth::User();
        $comentario = Questionamento::find($id);
        $demanda = Demanda::select('id', 'agencia_id')->where('id', $comentario->demanda_id)->first();

        if($comentario){
            if (stripos($comentario->tipo, 'Questionamento') !== false || stripos($comentario->tipo, 'Observação') !== false || stripos($comentario->tipo, 'Finalizado') !== false || stripos($comentario->tipo, 'Entregue') !== false) {
                $comentario->excluido =  date('Y-m-d H:i:s');
                $comentario->save();
            } else {
                $comentarioPauta = ComentarioPauta::where('comentario_id', $comentario->id)->first();
                $demandaTempo = DemandaTempo::find($comentarioPauta->demandapauta_id);
                $demandasTemposAg = DemandaTempo::where('agencia_id', $demandaTempo->agencia_id)->where('finalizado', null)->count();
                $demandasTemposPautaAg = DemandaTempo::where('agencia_id', $demandaTempo->agencia_id)->where('code_tempo', 'em-pauta')->count();
               
                if($demandasTemposAg == 1 && $demandasTemposPautaAg == 0){
                    $demanda = Demanda::select('id')->where('id', $demandaTempo->demanda_id)->first();
                    $demanda->em_alteracao = 0;
                    $demanda->save();
                }else if($demandasTemposAg == 0){
                    return back()->with('Error', 'Não foi possível excluir esse comentário.' ); 
                }
                
                $agenciaNotificacao = new Notificacao();
                $agenciaNotificacao->demanda_id = $comentario->demanda_id;
                $agenciaNotificacao->criado = date('Y-m-d H:i:s');
                $agenciaNotificacao->visualizada = '0';
                $agenciaNotificacao->tipo = 'criada';
                $agenciaNotificacao->conteudo = 'Excluída '.$comentario->tipo . ' do job '. $comentario->demanda_id;
                
                foreach($demanda['demandasUsuario'] as $item){
                    $agenciaNotificacao->usuario_id = $item->id;
                    $agenciaNotificacao->criado = date('Y-m-d H:i:s');
                    $agenciaNotificacao->save();
                }
              
                $comentario->excluido =  date('Y-m-d H:i:s');
                $comentario->save();
                $demandaTempo->delete();
        
            }

            $newTimeLine = new LinhaTempo();
            $newTimeLine->demanda_id = $comentario->demanda_id;
            $newTimeLine->usuario_id = $user->id;
            $newTimeLine->criado = date('Y-m-d H:i:s');
            $newTimeLine->code = 'removido';
            $newTimeLine->status =  'Removida '.$comentario->tipo;
            $newTimeLine->save();

            return back()->with('success', 'Comentário excluido com sucesso.' );  
        }

        return back()->with('Error', 'Não foi possível excluir esse comentário.' );  

    }

    public function getComentary($id){
     
        if($id){
            $comentary = Questionamento::find($id);
            return $comentary;
        }
        return false;
    }

    public function getComentaryAction(Request $request){
        $id = $request->id;
        if($id){
            $comentary = Questionamento::find($id);
            $comentary->descricao = $request->newContent;
            $comentary->save();
            
            return back()->with('success', 'Comentário atualizado!');   
        }

    }

    public function comentaryAction(Request $request, $id){
        $user = Auth::User();
        $conteudo = $request->input('conteudo');
        $tipo = $request->input('tipo');
        $demanda = Demanda::where('id', $id)->where('excluido', null)->with('criador')->with('demandasUsuario')->first();
        $titleEmail = '';
        $validator = Validator::make($request->all(),[
           'conteudo' => 'required|min:3',
           'tipo' => 'required'
            ],[
                'conteudo.required' => 'Não foi possível adicionar o seu comentário.',
                'tipo.required' => 'Preencha o campo tipo.',
                'conteudo.min' => 'O conteudo deve ter pelo menos 3 caracteres.',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
             if($demanda && $demanda->pausado == 0){
                $demandaComentario = new Questionamento();
                $demandaComentario->usuario_id = $user->id;
                $demandaComentario->descricao = $conteudo;
                $demandaComentario->demanda_id = $id;
                $demandaComentario->criado = Carbon::now();

                $newTimeLine = new LinhaTempo();
                $newTimeLine->demanda_id = $id;
                $newTimeLine->usuario_id = $user->id;
                $newTimeLine->criado = date('Y-m-d H:i:s');
                $hasObsCount = Questionamento::where('demanda_id', $id)->where('tipo', 'like', '%Observação%')->count();
                $hasAlterationQuestCount = Questionamento::where('demanda_id', $id)->where('tipo', 'REGEXP', '^Alteração\s+\d+')->count();

                $criadorNotificacao = new Notificacao();
                $criadorNotificacao->usuario_id = $demanda->criador->id;
                $criadorNotificacao->demanda_id = $demanda->id;
                $criadorNotificacao->criado = date('Y-m-d H:i:s');
                $criadorNotificacao->visualizada = '0';
                
                if($tipo == 'questionamento'){
                    //agencia
                    $hasQuestsCount = LinhaTempo::where('demanda_id', $id)->where('code', 'questionamento')->count();
                    //3
                    if($hasQuestsCount == 0){
                        $newTimeLine->status = 'Questionamento 1';
                        $newTimeLine->code = 'questionamento';
                        $newTimeLine->save();
                        $demandaComentario->tipo = 'Questionamento 1';
                    }else{
                        $newTimeLine->status = 'Questionamento '.($hasQuestsCount + 1);
                        $newTimeLine->code = 'questionamento';
                        $newTimeLine->save();
                        $demandaComentario->tipo = 'Questionamento '.($hasQuestsCount + 1);
                    }
                    
                    $demandaComentario->cor = '#d4624d';
                    $demandaComentario->visualizada_ag = 1;
                    $demandaComentario->visualizada_col = 0;

                    //notificacao
                    $criadorNotificacao->conteudo = 'Novo questionamento.';
                    $criadorNotificacao->tipo = 'questionamento';
                    $criadorNotificacao->save();

                    
                }else if($tipo == 'entregue'){
                    //agencia
                    $demandaComentario->tipo = 'Entregue';
                    $demandaComentario->cor = '#44a2d2';

                    $criadorNotificacao->conteudo = 'Novo comentário: Entregue';
                    $criadorNotificacao->tipo = 'observacao';
                    $criadorNotificacao->save();

                    $demandaComentario->visualizada_ag = 1;
                    $demandaComentario->visualizada_col = 0;
                                
                }else if($tipo == 'observacao'){

                    if($hasObsCount == 0){
                        $demandaComentario->tipo = 'Observação 1';
                        $demandaComentario->cor = '#f9bc0b';
                    }else if($hasObsCount > 0){
                        $demandaComentario->tipo = 'Observação '.($hasObsCount + 1);
                        $demandaComentario->cor = '#f9bc0b';
                    }
                    
                    $demandaComentario->visualizada_ag = 1;
                    $demandaComentario->visualizada_col = 0;

                    $criadorNotificacao->conteudo = 'Novo comentário: Observação';
                    $criadorNotificacao->tipo = 'observacao';
                    $criadorNotificacao->save();

                 }
                else if($tipo == 'observacaoadm'  && $user->id === $demanda->criador_id) {
                    //colaborador
                    
                    if($hasObsCount == 0){
                        $demandaComentario->tipo = 'Observação 1';
                        $demandaComentario->cor = '#f9bc0b';
                    }else if($hasObsCount > 0){
                        $demandaComentario->tipo = 'Observação '.($hasObsCount + 1);
                        $demandaComentario->cor = '#f9bc0b';
                    }

                    $demandaComentario->visualizada_ag = 0;
                    $demandaComentario->visualizada_col = 1;
                    
                 
                    foreach($demanda['demandasUsuario'] as $usuario) {
                        $agenciaNotificacao = new Notificacao();
                        $agenciaNotificacao->demanda_id = $request->id;
                        $agenciaNotificacao->usuario_id = $usuario->id;
                        $agenciaNotificacao->conteudo = 'Novo comentário: Observação';
                        $agenciaNotificacao->tipo = 'observacao';
                        $agenciaNotificacao->criado = date('Y-m-d H:i:s');
                        $agenciaNotificacao->visualizada = '0';
                        $agenciaNotificacao->save();
                        
                    }
                    
                    
                }else if($tipo == 'alteracao' && $user->id === $demanda->criador_id){

                    $conteudoNotificacao = '';
                    //colaborador
                    $hasalterationCount = LinhaTempo::where('demanda_id', $id)->where('code', 'alteracao')->count();
                    

                   //criar tempo
                    $newTimeJob = new DemandaTempo();
                    $newTimeJob->demanda_id = $request->id;
                    $newTimeJob->agencia_id = $demanda->agencia_id;   
                    $newTimeJob->criado = date('Y-m-d H:i:s');
                    $newTimeJob->aceitar_colaborador = 1;
                    $newTimeJob->code_tempo = 'alteracao';

                    $countDemandasReabertas = DemandaReaberta::where('demanda_id', $demanda->id)->count();
                    $demandasReaberta = DemandaReaberta::where('demanda_id', $demanda->id)->orderByDesc('id')->first();

                    //aumentar data demanda
                    if($request->sugeridoComment){
                        $newTimeJob->sugerido = $request->sugeridoComment;
                        if($countDemandasReabertas == 0){
                            if (strtotime($request->sugeridoComment) > strtotime($demanda->final)) {
                                // $request->sugeridoComment é maior que $demanda->final
                                $demanda->final = $request->sugeridoComment;
                                $demanda->save();
                            } 
                        }else if($countDemandasReabertas > 0){
                            if (strtotime($request->sugeridoComment) > strtotime($demandasReaberta->sugerido)) {
                                // $request->sugeridoComment é maior que $demanda->final
                                $demandasReaberta->sugerido = $request->sugeridoComment;
                                $demandasReaberta->save();
                            } 
                        }
                        
                    }

                    if($hasalterationCount >= 0 && $countDemandasReabertas == 0){
                        
                        $newTimeJob->status = 'Alteração '.($hasalterationCount + 1);
                        $titleEmail = 'Alteração '.($hasalterationCount + 1);
                        
                        $newTimeLine->status = 'Alteração '.($hasalterationCount + 1);
                        $conteudoNotificacao = 'Criada alteração '.($hasalterationCount + 1).'.';

                        $newTimeLine->code = 'alteracao';
                        $newTimeLine->save();
                        
                        $demanda->em_alteracao = 1;
                        $demanda->entregue = 0;
                        $demanda->entregue_recebido = 0;
                        $demanda->save();
                        

                    }

                    //criada alteracao com a demanda reaberta
                    if($hasalterationCount >= 0 && $countDemandasReabertas > 0){
                        $newTimeJob->status = '(Reaberto) alteração '.($hasalterationCount + 1);
                        $titleEmail = '(Reaberto) alteração '.($hasalterationCount + 1);

                        $newTimeLine->status = 'Alteração '.($hasalterationCount + 1);
                        $newTimeLine->code = 'alteracao';
                        $newTimeLine->save();
                        $conteudoNotificacao = 'Criada alteração '.($hasalterationCount + 1).'.';
                        
                        $demanda->em_alteracao = 1;
                        $demanda->entregue = 0;
                        $demanda->entregue_recebido = 0;
                        $demanda->save();

                    }

                    //comentario alteracao
                    
                    if($hasAlterationQuestCount == 0){
                        $demandaComentario->tipo = 'Alteração 1';
                        $demandaComentario->cor = '#d56551';
                    }else if($hasAlterationQuestCount > 0){
                        $demandaComentario->tipo = 'Alteração '.($hasAlterationQuestCount + 1);
                        $demandaComentario->cor = '#d56551';
                    }

                    $demandaComentario->visualizada_ag = 0;
                    $demandaComentario->visualizada_col = 1;

                    $demandaComentario->save();

                    //salvar tempo
                    $newTimeJob->save();


                    //notificacao usuario

                    foreach($demanda['demandasUsuario'] as $usuario) {
                        $agenciaNotificacao = new Notificacao();
                        $agenciaNotificacao->demanda_id = $demanda->id;
                        $agenciaNotificacao->usuario_id = $usuario->id;
                        $agenciaNotificacao->conteudo = $conteudoNotificacao;
                        $agenciaNotificacao->tipo = 'criada';
                        $agenciaNotificacao->criado = date('Y-m-d H:i:s');
                        $agenciaNotificacao->visualizada = '0';
                        $agenciaNotificacao->save();
                        
                    }

                    //salvar relacao comentario/pauta

                    $comentarioPauta = new ComentarioPauta();
                    $comentarioPauta->comentario_id = $demandaComentario->id;
                    $comentarioPauta->demandapauta_id = $newTimeJob->id;
                    $comentarioPauta->save();
                    
                    //notificar email
                    $actionLink = route('Job', ['id' => $id]);
                    $bodyEmail = 'O job '.$id . ' recebeu uma nova alteração.'. '<br/>'.  'Acesse pelo link logo abaixo.';

                    $agencies = Agencia::where('id', $demanda->agencia_id)->with(['agenciasUsuarios' => function ($query) {
                        $query->where('excluido', null);
                        $query->select('email', 'nome');
                    }])->first();

                    {
                        //emails usuarios agencia
                        // foreach($agencies['agenciasUsuarios'] as $item){
                        //     Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $item->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($item, $titleEmail, $id) {
                        //         $message->from('envios@fmfm.com.br')
                        //         ->to($item->email)
                        //         ->bcc('agenciacriareof@gmail.com')
                        //         ->subject('Nova alteração do job '. $id);
                               
                        //         // $message->from('dudu1.6@hotmail.com');
                        //         // $message->to($item->email)->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
                        //     });
                        // }
                    }
                    
                    //countAlteracao
                    $newAlteration = new Alteracao();
                    $newAlteration->demanda_id = $demanda->id;
                    $newAlteration->save();
                    
                    $demanda->save();

                }else if($tipo == 'finalizado' && $user->id === $demanda->criador_id){
                    //colaborador
                    $demandaComentario->tipo = 'finalizado';
                    $demandaComentario->cor = '#3dbb3d';
                    $newTimeLine->status = 'Finalizado';
                    $newTimeLine->save();

                    $demandaComentario->visualizada_ag = 0;
                    $demandaComentario->visualizada_col = 1;

                    foreach($demanda['demandasUsuario'] as $usuario) {
                        $agenciaNotificacao = new Notificacao();
                        $agenciaNotificacao->demanda_id = $demanda->id;
                        $agenciaNotificacao->usuario_id = $usuario->id;
                        $agenciaNotificacao->conteudo = 'Novo comentário: Finalizado';
                        $agenciaNotificacao->tipo = 'finalizado';
                        $agenciaNotificacao->criado = date('Y-m-d H:i:s');
                        $agenciaNotificacao->visualizada = '0';
                        $agenciaNotificacao->save();
                    }

                }
                
                $demandaComentario->save();

            }else{
                return back()->with('error', 'Não foi possível adicionar o seu comentário, verifique se o seu job não se encontra pausado.' )->withInput();
            }
        }
        return back()->with('success', 'Comentário adicionado!' );  
    }
}
