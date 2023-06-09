<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resposta;
use App\Models\Notificacao;
use App\Models\Questionamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\LinhaTempo;
Use Alert;
use App\Models\Demanda;
use Illuminate\Support\Facades\Auth;

class RespostasController extends Controller
{
    public function delete(Request $request, $id){

        $resposta = Resposta::find($id);
        if($resposta){
            $resposta->delete();
            return back()->with('success', 'Comentário excluido com sucesso.' );  
        }

        return back()->with('Error', 'Não foi possível excluir esse comentário.' );  

    }

    public function getAnswer(Request $request, $id){
     
        if($id){
            $response = Resposta::find($id);
            return $response;
        }
        return false;
    }

    public function getAnswerAction(Request $request){
        $id = $request->id;
        if($id){
            $response = Resposta::find($id);
            $response->conteudo = $request->newContent;
            $response->save();
            
            return back()->with('success', 'Comentário atualizado!');   
        }

    }

    function answerCreate(Request $request, $id){
        $user = Auth::User();

        $validator = Validator::make($request->all(),[
            'newContent' => 'required'
            ],[
                'newContent.required' => 'Não foi possível adicionar a sua resposta.',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            if($id){
                $quest = Questionamento::find($id);
                $demanda = Demanda::select('id')->where('id', $quest->demanda_id)->with('demandasUsuario')->first();

                $numerosQuest = preg_replace("/[^0-9]/", "", $quest->tipo);
                $demandaResposta = new Resposta();
                $demandaResposta->usuario_id = $user->id;
                $demandaResposta->conteudo = $request->newContent;
                $demandaResposta->questionamento_id = $id;
                $demandaResposta->criado = Carbon::now();
                $demandaResposta->save();


                if (strpos($quest->tipo, "Questionamento") !== false) {
                    $newTimeLine =  new LinhaTempo();
                    $newTimeLine->code = 'resposta';
                    $newTimeLine->usuario_id = $user->id;
                    $newTimeLine->demanda_id = $request->demandaId;
                    $newTimeLine->criado = date('Y-m-d H:i:s');
                    $newTimeLine->status = 'Resposta Q' . $numerosQuest;
                    $newTimeLine->save();
                }

                $agenciaNotificacao = new Notificacao();
                $agenciaNotificacao->demanda_id = $request->demandaId;
                $agenciaNotificacao->conteudo = 'Novo comentário: Resposta';
                $agenciaNotificacao->criado = date('Y-m-d H:i:s');
                $agenciaNotificacao->visualizada = '0';
                $agenciaNotificacao->tipo = 'observacao';
                $agenciaNotificacao->usuario_id = $quest->usuario_id;
                $agenciaNotificacao->save();

                if( $quest->visualizada_col == 0 ){
                    $quest->visualizada_col = 1;
                    $quest->save();
                }
            
                return back()->with('success', 'resposta adicionada!' );  
            }
       }
       return back()->with('error', 'Não foi possível adicionar o sua resposta.' )->withInput();

    }
}
