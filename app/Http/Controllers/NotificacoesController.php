<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacao;
use App\Models\AgenciaUsuario;
Use Alert;
use App\Models\Demanda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificacoesController extends Controller
{
    public function index(){
        $user = Auth::User();
        $notifications = null;

        $oldNotifications = Notificacao::where('visualizada', '1')->where('clicado', '<', Carbon::now()->subDays(2))->get();
        
        foreach ($oldNotifications as $item) {
            $item->delete();
        }

        if($user->tipo === 'colaborador'){
            $notifications = Notificacao::where('usuario_id', $user->id)->with(['demanda' => function ($query) {
            $query->where('excluido', null);
            $query->select('id', 'titulo');
            }])->orderBy('id', 'DESC')->orderBy('criado', 'DESC')->paginate(25);        
        }else if($user->tipo === 'agencia'){
            $agId = AgenciaUsuario::select('agencia_id')->where('usuario_id', $user->id)->first();
            $notifications = Notificacao::where(function ($query) use ($agId, $user) {
                $query->where('agencia_id', $agId->agencia_id)
                    ->orWhere('usuario_id', $user->id);
            })
            ->with(['demanda' => function ($query) {
                $query->where('excluido', null);
                $query->select('id', 'titulo');
            }])
            ->orderBy('id', 'DESC')
            ->orderBy('criado', 'DESC')->
            paginate(25);
        }

        return view('notificacao', [
            'notifications' => $notifications
        ]);
    }

    public function action(Request $request){
        $notifyId = $request->input('notificacoes');

        if($notifyId != null ){
            foreach($notifyId as $item){
                $visualizada = Notificacao::where('id', $item)->first();
                $visualizada->visualizada = '1';
                $visualizada->clicado = date('Y-m-d H:i:s');
                $visualizada->save();
            }
            
            return back()->with('success', 'Atualizada com sucesso.' );  
        }else{
            return back()->with('error', 'Nenhuma notificação foi encontrada.' );  
        }

    }

    public function actionSingle(Request $request, $id){
        $demandaId = $request->input('demandaId');

        if($demandaId){

            $visualizada = Notificacao::where('id', $id)->first();
            $visualizada->visualizada = '1';
            $visualizada->clicado = date('Y-m-d H:i:s');
            $visualizada->save();

            return redirect()->route('Job', ['id' => $demandaId]);
            
        }else{
            return back()->with('error', 'Nenhuma notificação foi encontrada.' );  
        }

    }

}
