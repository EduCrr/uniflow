<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demanda;
use App\Models\User;
use App\Models\DemandaImagem;
use App\Models\Marca;
use App\Models\Notificacao;
use App\Models\LinhaTempo;
use App\Models\DemandaTempo;
use App\Models\Questionamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
Use Alert;
use App\Models\DemandaReaberta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DemandasController extends Controller
{   
    
    //findOne job
    public function index(Request $request, $id){
    
        $demanda = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('id', $id)->with('imagens')->with('criador')->with('demandasReabertas')->with(['prazosDaPauta.agencia', 'prazosDaPauta.comentarios'])->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->first();
        
        $user = Auth::User();
        if($demanda){
            $demanda['agencia'] = $demanda->agencia()->with(['agenciasUsuarios' => function ($query) {
            $query->where('excluido', null);
            }])->first();
            
            $demanda['questionamentos'] = $demanda->questionamentos()->where('excluido', null)->with(['usuario' => function ($query) {
            $query->where('excluido', null);
            }])->with('respostas.usuario')->get();


            foreach($demanda['prazosDaPauta'] as $key => $item) {
                if($item->finalizado !== null) {
                    $iniciado = \Carbon\Carbon::parse($item->iniciado);
                    $finalizado = \Carbon\Carbon::parse($item->finalizado);
                    $duracao = null;
                    $diaAtual = \Carbon\Carbon::now();
            
                    // verifica se a demanda foi criada antes ou depois das 17h
                    $iniciadoDepoisDas17h = $iniciado->gte($iniciado->copy()->setHour(17));
                    if ($iniciadoDepoisDas17h) {
                        // se foi criada depois das 17h, conta o dia seguinte como o primeiro dia útil
                        $diasUteis = $iniciado->copy()->addDay()->diffInWeekdays($finalizado, true);
                    } else {
                        // se foi criada antes das 17h, conta o dia atual como o primeiro dia útil
                        $diasUteis = $iniciado->diffInWeekdays($finalizado, true);
                    }
            
                    if($diasUteis === 0 || $diasUteis === 1) {
                        $duracao = "Menos de 1 dia";
                    } else if($diasUteis > 1) {
                        $duracao = $diasUteis . " dias";
                    }
            
                    $demanda['prazosDaPauta'][$key]->final = $duracao;
                } else {
                    $demanda['prazosDaPauta'][$key]->final = null;
                }
            }

            $idsAgUser = [];
            $showAg = false;
            
            foreach($demanda['agencia']['agenciasUsuarios'] as $item){
                array_push($idsAgUser, $item->id);
            }
            
            $isSend = LinhaTempo::where('demanda_id', $id)->where('status', 'Entregue')->count();

            $entregue = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('id', $id)->where('entregue', '1')->count();

            if(in_array($user->id, $idsAgUser)){
                //Ler comentários
                $showAg = true;
                foreach($demanda['questionamentos'] as $quest){
                    if( $quest->visualizada_ag == 0){
                        $quest->visualizada_ag = 1;
                        $quest->save();
                    }

                    foreach($quest['respostas'] as $res){
                        if( $res->visualizada_ag == 0){
                            $res->visualizada_ag = 1;
                            $res->save();
                        }
                    }
                }
            }else{
                $showAg = false;
                foreach($demanda['questionamentos'] as $quest){
                    if( $quest->visualizada_col == 0 ){
                        $quest->visualizada_col = 1;
                        $quest->save();
                    }
                }
            }

            // $hasTimeAgenda = false;
            
            // $verifyTimeAgenda = DemandaTempo::where('demanda_id', $id)->where('finalizado', '=', null)->count();
            // if ($verifyTimeAgenda == 0) {
            //   $hasTimeAgenda = true;
            // } else if ($verifyTimeAgenda > 0) {
            //     $hasTimeAgenda = false;
            // }

            //porcentagem
            if ($demanda->finalizada == 1) {
                $porcentagem = 100;
            } else {
                // Obter o total de prazosDaPauta finalizados da demanda
                $totalFinalizados = $demanda->prazosDaPauta()->whereNotNull('finalizado')->count();
            
                // Obter o total de prazosDaPauta não finalizados da demanda
                $totalNaoFinalizados = $demanda->prazosDaPauta()->whereNull('finalizado')->count();
                
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
            $demanda->porcentagem = $porcentagem;
            $lineTime = LinhaTempo::where('demanda_id', $id)->with('usuario')->get();
            return view('Job/index', [
                'demanda' => $demanda,
                'user' => $user,
                'showAg' => $showAg,
                'isSend' => $isSend,
                'lineTime' => $lineTime,
                'entregue' => $entregue,
            ]);

        }else{
            return redirect('/login')->with('warning', 'Esse job não está disponível.' );
        }
        
    }

    public function downloadImage($id){

        $image = DemandaImagem::find($id);

        if($image){
            $path = public_path('assets/images/files/'.$image->imagem);
            return response()->download($path);
        }

        return view('home');
    }

    public function changeCategoryAg(Request $request){
        $user = Auth::User();
        $userAg =  User::where('excluido', null)->where('id', $user->id)->with('usuariosAgencias')->first();

        $idsAg = [];
          
        foreach($userAg['usuariosAgencias'] as $item){
            array_push($idsAg, $item->id);
        }

        $demandas = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->whereIn('agencia_id', $idsAg)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with(['agencia' => function ($query) {
        $query->where('excluido', null);
        }])->with(['demandasReabertas' => function ($query) {
            $query->where('excluido', null);
            $query->where('finalizado', null);
        }])->withCount(['questionamentos as count_questionamentos' => function ($query) {
            $query->where('visualizada_ag', 0)->where('excluido', null);
        }])->orderBy('id', 'DESC');
    
        if($request->category_id_ag == 'pendentes'){
          $demandas->where('em_pauta', '0')->where('finalizada', '0')->where('entregue', '0')->where('pausado', 0)->take(15);
        }else if($request->category_id_ag == 'em_pauta'){
           $demandas->where('em_pauta', '1')->where('finalizada', '0')->where('entregue', '0')->where('pausado', 0)->take(15);
        }else if($request->category_id_ag == 'pausados'){
          $demandas->where('pausado', '1')->take(15);
        }
        else if($request->category_id_ag == 'entregue'){
          $demandas->where('entregue', '1')->where('finalizada', '0')->where('pausado', 0)->take(15);
        }

        $demandas = $demandas->get();

        foreach($demandas as $key => $item){
            $demandasReabertas = $item->demandasReabertas;
            if ($demandasReabertas->count() > 0) {
                $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                $item->final = $sugerido;
            }
        }

        return view('demandas-categorias', [
            'demandas' => $demandas,
        ]); 
    }

    public function findAll(Request $request){

        $user = Auth::User();
    
        $userAg =  User::where('id', $user->id)->where('excluido', null)->with(['usuariosAgencias' => function ($query) {
        $query->where('excluido', null);
        }])->first();
        $idsAg = [];

        foreach($userAg['usuariosAgencias'] as $item){
            array_push($idsAg, $item->id);
        }

        $demandas = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->whereIn('agencia_id', $idsAg)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with(['agencia' => function ($query) {
        $query->where('excluido', null);
        }])->with(['demandasReabertas' => function ($query) {
            $query->where('finalizado', null);
            $query->where('excluido', null);
        }])->withCount(['questionamentos as count_questionamentos' => function ($query) {
            $query->where('visualizada_ag', 0)->where('excluido', null);
        }])->withCount(['notificacoes as count_notificacoes' => function ($query) {
            $query->where('visualizada', 0)->where('clicado', null);
        }])->orderBy('id', 'DESC');
       
        $search = $request->search;
        $aprovada = $request->aprovada;
        $priority = $request->category_id;
        $inTime = $request->in_tyme;
        $marca = $request->marca_id;
        $dateRange = $request->dateRange;

        if($search){
            $demandas->where('titulo', 'like', "%$search%");
        }

        if($inTime != ''){
            if($inTime == 2){
                $dataAtual = Carbon::now()->toDateString();
                $demandas->whereDate('final', '<', $dataAtual)->where('finalizada', 0);
            }else{
                $demandas->where('atrasada', '=', $inTime)->where('finalizada', 1);
            }
        }
        
        if($aprovada){
            if($aprovada === 'finalizados'){
                 $demandas->where('finalizada', '1');
            }else if($aprovada === 'em_pauta'){
                $demandas->where('em_pauta', '1')->where('finalizada', '0')->where('entregue', '0')->where('pausado', '0');
            }else if($aprovada === 'pendentes'){
                $demandas->where('em_pauta', '0')->where('finalizada', '0')->where('entregue', '0')->where('pausado', '0');
            }else if($aprovada === 'entregue'){
                $demandas->where('em_pauta', '0')->where('finalizada', '0')->where('entregue', '1')->where('pausado', '0');
            }else if($aprovada === 'recebidos'){
                $demandas->where('em_pauta', '0')->where('finalizada', '0')->where('entregue', '0')->where('recebido', 1)->where('entregue_recebido', 0)->where('pausado', '0');
            }else if($aprovada === 'pausados'){
                $demandas->where('pausado', '1');
            }
        }

        if ($dateRange) {
            [$date, $endDate] = explode(' - ', $dateRange);
            $date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d');
            $demandas->where(function($query) use ($date, $endDate) {
                $query->whereDate('inicio', '>=', $date)
                      ->whereDate('inicio', '<=', $endDate)
                      ->orWhereDate('final', '>=', $date)
                      ->whereDate('final', '<=', $endDate);
              });
        }

        if($priority){
            $demandas->where('prioridade', $priority);
        }

        if($marca != '0' && $marca){
            $demandas->whereHas('marcas', function($query)  use($marca){
                $query->where('marcas.id', $marca);
                $query->where('marcas.excluido', null );
            });
        }

        $demandas = $demandas->paginate(15)->withQueryString();

        foreach ($demandas as $demanda) {
            // if ($demanda->finalizada == 1) {
            //     $porcentagem = 100;
            // } else {
            //     // Obter o total de prazosDaPauta finalizados da demanda
            //     $totalFinalizados = $demanda->prazosDaPauta()->whereNotNull('finalizado')->count();
            
            //     // Obter o total de prazosDaPauta não finalizados da demanda
            //     $totalNaoFinalizados = $demanda->prazosDaPauta()->whereNull('finalizado')->count();
               
            //     // Calcular a porcentagem com base nos prazosDaPauta finalizados e não finalizados da demanda
            //     $totalPrazos = $totalFinalizados + $totalNaoFinalizados;
            //     if ($totalPrazos == 0) {
            //         $porcentagem = 0;
            //     } elseif ($totalFinalizados == 0) {
            //         $porcentagem = 10;
            //     } else {
            //         $porcentagem = round(($totalFinalizados / $totalPrazos) * 95);
            //     }
            // }
            // // Adicionar a porcentagem como um atributo da demanda
            // $demanda->porcentagem = $porcentagem;

            //ajustar final quando estiver reaberta

            $demandasReabertas = $demanda->demandasReabertas;
            if ($demandasReabertas->count() > 0) {
                $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                $demanda->final = $sugerido;
            }
        }
     
        $brands = Marca::where('excluido', null)->get();

        return view('Job/jobs', [
            'demandas' => $demandas,
            'search' => $search,
            'inTime' => $inTime,
            'aprovada' => $aprovada,
            'priority' => $priority,
            'brands' => $brands,
            'marca'=> $marca,
            'dateRange' => $dateRange
        ]);
        
    } 
   
    public function uploadImg(Request $request, $id){
        $user = Auth::User();

        $imgs = $request->file('file');
        $input_data = $request->all();

        $validator = Validator::make(
            $input_data, [
            'file.*' => 'required'
            ],[
                'file.*.required' => 'Please upload an image',
            ]
        );
    
        if(!$validator->fails()){
            if ($request->hasFile('file')) {
                foreach($imgs as $item){
                    $extension = $item->extension();
                    $file = $item->getClientOriginalName();
                    $fileName = pathinfo($file, PATHINFO_FILENAME);
                    $photoName = $fileName . '.' . $extension;
                    $destImg = public_path('assets/images/files');
                    $i = 1;
            
                    while(file_exists($destImg . '/' . $photoName)){
                        $photoName = $fileName . '_' . $i . '.' . $extension;
                        $i++;
                    }
            
                    $item->move($destImg, $photoName);
        
                    $newArqJob = new DemandaImagem();
                    $newArqJob->demanda_id =  $id;
                    $newArqJob->imagem = $photoName;
                    $newArqJob->usuario_id = $user->id;
                    $newArqJob->criado =  date('Y-m-d H:i:s');
                    $newArqJob->save();
                }
                return redirect()->back()->with('success', 'Arquivo adicionado!');  
            }else{
                return back()->with('error', 'Não foi possível adicionar este(s) arquivo(s)' );
            }
        }else{
            return back()->with('error', $validator->errors()->first())->withInput();

        }
    }

    public function deleteArq(Request $request, $id){

        $fileUpload = DemandaImagem::find($id);

        if($fileUpload){
            $path=public_path().'/assets/images/files/'.$fileUpload->imagem;
            if (file_exists($path)) {
                unlink($path);
            }
            $fileUpload->delete();

            return back()->with('success', 'Arquivo excluido com sucesso.' );  
        }
        
        return back()->with('Error', 'Não foi possível excluir este arquivo.' );  

    }

    

    //iniciar pauta
    public function changeStatusPauta(Request $request, $id){
        $user = Auth::User();
        $demanda = Demanda::where('id', $request->id)->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->with('criador')->first();

       
        // $validator = Validator::make($request->all(),[
        //    'finalizado' => 'required',
          
        //     ],[
        //         'finalizado.required' => 'Preencha seu prazo para esse job!',
        //     ]
        // );

        // if($validator->fails()) {
        //     return back()->with('error', $validator->messages()->all()[0])->withInput();
        // }

        if($demanda){

            $actionLink = route('Job', ['id' => $demanda->id]);
            
            $titleEmail = '';

            $newTimeLineStart =  new LinhaTempo();
            $newTimeLineStart->demanda_id = $request->id;
            $newTimeLineStart->usuario_id = $user->id;
            $newTimeLineStart->criado = date('Y-m-d H:i:s');

            $newTimeJob = new DemandaTempo();
            $newTimeJob->demanda_id = $request->id;
            $newTimeJob->agencia_id = $demanda->agencia_id;
            $newTimeJob->criado = date('Y-m-d H:i:s');
            $newTimeJob->sugerido = $request->sugeridoAg;
            $newTimeJob->aceitar_agencia = 1;
            $newTimeJob->recebido = 1;
            $newTimeJob->code_tempo = 'em-pauta';
            $newTimeJob->iniciado = date('Y-m-d H:i:s');

            if($demanda->em_alteracao == 0){
                $hasScheduleReopenCount = LinhaTempo::where('demanda_id', $request->id)->where('code', 'iniciada-pauta')->count();
                //5
                if($hasScheduleReopenCount == 0){
                    $newTimeLineStart->status = 'Iniciada pauta 1';
                    $newTimeLineStart->code = 'iniciada-pauta';
                    $titleEmail = 'Iniciada pauta 1';
                    $newTimeJob->status = 'Pauta 1';
                   
                }else{
                    $newTimeLineStart->status = 'Iniciada pauta '.($hasScheduleReopenCount + 1);
                    $newTimeLineStart->code = 'iniciada-pauta';
                    $titleEmail = 'Iniciada pauta '.($hasScheduleReopenCount + 1);
                    $newTimeJob->status = 'Pauta '.($hasScheduleReopenCount + 1);
                }

                $demanda->em_pauta = 1;
                $demanda->finalizada = 0;
                $demanda->entregue = 0;
                $demanda->save();

                $newTimeLineStart->save();
                $newTimeJob->save();
           
            }
                
            //notificar criador

            $criadorNotificacao = new Notificacao();
            $criadorNotificacao->usuario_id = $demanda->criador->id;
            $criadorNotificacao->demanda_id = $demanda->id;
            $criadorNotificacao->conteudo = 'O job entrou em pauta.';
            $criadorNotificacao->criado = date('Y-m-d H:i:s');
            $criadorNotificacao->visualizada = '0';
            $criadorNotificacao->tipo = 'pauta';
            $criadorNotificacao->save();

            $bodyEmail = 'O job '.$id. ' mudou o status para: Em pauta.'. '<br/>' . 'Acesse pelo link logo abaixo.';

            // Mail::send('notify-job', ['action_link' => $actionLink, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($demanda, $id, $titleEmail) {
            //     $message->from('envios@fmfm.com.br')
            //     ->to($demanda->criador->email)
            //     ->bcc('agenciacriareof@gmail.com')
            //     ->subject('O job '.$id.' mudou o status para: Em pauta');
                 
            // });
        }
        
        return back()->with('success', 'Job alterado com sucesso.' ); 
    }


    public function editStatusPauta(Request $request, $id){
        $timePauta = DemandaTempo::find($id);
        $validator = Validator::make($request->all(),[
           'finalizadoEdit' => 'required',
          
            ],[
                'finalizadoEdit.required' => 'Preencha seu prazo para esse job!',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            if($timePauta){
                $timePauta->finalizado = $request->finalizadoEdit;
                $timePauta->save();
            }

            return back()->with('success', 'Título alterado com sucesso.' ); 

        }
       
    }

    public function changeDemandatitle(Request $request, $id){
        $demanda = Demanda::find($id);
        if($demanda){
            $demanda->titulo = $request->titulo;
            $demanda->save();
            
            return back()->with('success', 'Pauta alterada com sucesso.' ); 

        }else{
            return back()->with('error', 'Esse job não pode ser alterado.' ); 
        }
    }


    public function changeTime(Request $request, $id){
        $user = Auth::User();
        $validator = Validator::make($request->all(),[
            'sugerido' => 'required',
            'sugeridoAlt' => 'required',
            ],[
                'sugerido.required' => 'Preencha o campo data.',
                'sugeridoAlt.required' => 'Descreva o motivo para a alteração do prazo!',
            ]
        );
        
        if($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }

        if(!$validator->fails()){
            $demandaPrazo = DemandaTempo::where('id', $id)->with('demanda:id,criador_id,agencia_id', 'demanda.agencia')->first();
            $notificacao = new Notificacao();
            $notificacao->demanda_id = $demandaPrazo->demanda_id;
            $notificacao->criado = date('Y-m-d H:i:s');
            $notificacao->visualizada = '0';
            $notificacao->tipo = 'alterado';

            $userDemanda =  $demandaPrazo->demanda->criador->id;
            $user = Auth::User();
            
            //pegar numero
            if (preg_match('/\d+/', $demandaPrazo->status, $matches)) {
                $lastNumber = $matches[0];
            }

            $demandaPrazo->sugerido = $request->sugerido;

            //agencia notificacao
            if($user->id == $userDemanda){
                $demandaPrazo->aceitar_colaborador = 1;
                $demandaPrazo->aceitar_agencia = 0;
                $notificacao->agencia_id = $demandaPrazo->agencia_id;
                if($demandaPrazo->code_tempo === 'em-pauta'){
                    $notificacao->conteudo = $user->nome . ' definiu uma nova data para a '. strtolower($demandaPrazo->status).'.';

                }else if($demandaPrazo->code_tempo === 'alteracao'){
                    $notificacao->conteudo = $user->nome . ' definiu uma nova data para a alteração '. $lastNumber.'.';
                }

                $demanda = Demanda::select('final', 'id')->where('id', $demandaPrazo->demanda_id)->first();

                if (strtotime($request->sugerido) > strtotime($demanda->final)) {
                    // $request->sugeridoComment é maior que $demanda->final
                    $demanda->final = $request->sugerido;

                } 

                $demanda->save();
                    

            }else if($user->id != $userDemanda){
                //criador notificacao
                $demandaPrazo->aceitar_colaborador = 0;
                $demandaPrazo->aceitar_agencia = 1;
                $notificacao->usuario_id = $userDemanda;
                if($demandaPrazo->code_tempo === 'em-pauta'){
                    $notificacao->conteudo = $demandaPrazo->demanda->agencia->nome . ' definiu uma nova data para a '. strtolower($demandaPrazo->status).'.';
                }else if($demandaPrazo->code_tempo === 'alteracao'){
                    $notificacao->conteudo = $demandaPrazo->demanda->agencia->nome . ' definiu uma nova data para a alteração '. $lastNumber.'.';
                }

            }
            
            $demandaPrazo->save();


            $newComment = new Questionamento();
            $newComment->demanda_id = $demandaPrazo->demanda_id;
            $newComment->usuario_id = $user->id;
            $newComment->descricao = $request->sugeridoAlt;
            $newComment->criado = date('Y-m-d H:i:s');
            if($demandaPrazo->code_tempo === 'em-pauta'){
                $newComment->tipo = 'Mudança de prazo da '. strtolower($demandaPrazo->status);
            }else if($demandaPrazo->code_tempo === 'alteracao'){
                $newComment->tipo = 'Mudança de prazo da alteração '. $lastNumber.'.';

            }
            $newComment->cor = '#f9bc0b';
            $newComment->save();
            $notificacao->save();
           

            return back()->with('success', 'Pauta alterada com sucesso.' ); 
        }

    }

    public function finalizeAgenda(Request $request, $id){
        $user = Auth::User();
        $lastNumber = '';
        $demandaPrazo = DemandaTempo::where('id', $id)
        ->with('agencia')->first();
        $demandaPrazo->finalizado = date('Y-m-d H:i:s');
        $demandaPrazo->aceitar_agencia = 1;
        $demandaPrazo->aceitar_colaborador = 1;
        $titleEmail = '';
        //verificar se foi atrasada
        if ($demandaPrazo->finalizado > $demandaPrazo->sugerido) {
            $demandaPrazo->atrasada = 1;
        }

        $demandaPrazo->save();

        //pegar numero
        if (preg_match('/\d+/', $demandaPrazo->status, $matches)) {
            $lastNumber = $matches[0];
        }

        $demanda = Demanda::find($request->demandaId);
        $criado = Carbon::parse($demandaPrazo->criado);

        $newTimeLine =  new LinhaTempo();
        $newTimeLine->demanda_id = $request->demandaId;
        $newTimeLine->usuario_id = $user->id;
        $newTimeLine->criado = date('Y-m-d H:i:s');

        $criadorNotificacao = new Notificacao();
        $criadorNotificacao->usuario_id = $demanda->criador->id;
        $criadorNotificacao->demanda_id =  $request->demandaId;
        $criadorNotificacao->criado = date('Y-m-d H:i:s');
        $criadorNotificacao->visualizada = '0';
        $criadorNotificacao->tipo = 'entregue';

        $actionLink = route('Job', ['id' => $request->demandaId]);
       
        //LINHA TEMPO ENTREGUE PAUTA
        if($demandaPrazo->code_tempo === 'em-pauta'){
           
            $newTimeLine->status = 'Entregue pauta '.$lastNumber;
            $titleEmail = 'Entregue a '. strtolower($demandaPrazo->status);
            $newTimeLine->code = 'entregue';
            $newTimeLine->save();
            $criadorNotificacao->conteudo = 'Agência ' . $demandaPrazo->agencia->nome . ' entregou a '. strtolower($demandaPrazo->status).'.';
            $criadorNotificacao->save();

            $bodyEmail = 'Foi entregue a '.strtolower($demandaPrazo->status). '<br/>'. 'Acesse pelo link logo abaixo.';

            // Mail::send('notify-job', ['action_link' => $actionLink, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($demanda, $titleEmail) {
            //     $message->from('envios@fmfm.com.br')
            //     ->to($demanda->criador->email)
            //     ->bcc('agenciacriareof@gmail.com')
            //     ->subject($titleEmail);
                 
            // });
        }

        //LINHA TEMPO ENTREGUE ALTERACAO

        if($demandaPrazo->code_tempo === 'alteracao'){

            $newTimeLine->status = 'Entregue pauta A'.$lastNumber;
            $titleEmail = 'Entregue a alteração ' . $lastNumber;
            $newTimeLine->code = 'entregue-alteracao';
            $newTimeLine->save();
            $criadorNotificacao->conteudo =  'Agência ' . $demandaPrazo->agencia->nome . ' entregou a alteração '. $lastNumber . '.';
            $criadorNotificacao->save();
            
            $bodyEmail = 'Foi entregue a alteração ' . $lastNumber.'.'. '<br/>'. 'Acesse pelo link logo abaixo.';

            // Mail::send('notify-job', ['action_link' => $actionLink, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($demanda, $titleEmail) {
            //     $message->from('envios@fmfm.com.br')
            //     ->to($demanda->criador->email)
            //     ->bcc('agenciacriareof@gmail.com')
            //     ->subject($titleEmail);
                 
            // });

        }

        //verificar se foi a última pauta e entregar job
        $verifyTimeAgenda = DemandaTempo::where('demanda_id', $request->demandaId)->where('finalizado', '=', null)->count();
        if ($verifyTimeAgenda == 0) {
            $demanda->em_pauta = 0;
            $demanda->finalizada = 0;
            $demanda->entregue = 1;
            $demanda->em_alteracao = 0;
            $demanda->entregue_recebido = 0;

            $titleEmail = 'O job '.$demanda->id.' foi entregue';

            $criadorNotificacaoEntrega = new Notificacao();
            $criadorNotificacaoEntrega->usuario_id = $demanda->criador->id;
            $criadorNotificacaoEntrega->demanda_id =  $request->demandaId;
            $criadorNotificacaoEntrega->criado = date('Y-m-d H:i:s');
            $criadorNotificacaoEntrega->visualizada = '0';
            $criadorNotificacaoEntrega->tipo = 'entregue';
            $criadorNotificacaoEntrega->conteudo =  'Agência ' . $demandaPrazo->agencia->nome .' alterou o status para entregue.';
            $criadorNotificacaoEntrega->save();

            $countDemandasReabertas = DemandaReaberta::where('demanda_id', $demanda->id)->count();

            if($countDemandasReabertas == 0){
                 // criar a data atual
                $dataAtual = Carbon::now();

                // converter para o fuso horário da América/São_Paulo
                $dataAtual->setTimezone('America/Sao_Paulo');

                // criar a data final
                $dataFinal = Carbon::createFromFormat('Y-m-d H:i:s', $demanda->final);

                // verificar se este trabalho foi reaberto
                $verifyReOpenJob = DemandaReaberta::where('demanda_id', $id)->orderBy('id', 'DESC')->first();

                if ($verifyReOpenJob) {
                    // converter a data final para o fuso horário da América/São_Paulo
                    $dataFinal = Carbon::createFromFormat('Y-m-d H:i:s', $verifyReOpenJob->sugerido);
                    $dataFinal->setTimezone('America/Sao_Paulo');
                }

                // comparar as datas
                if ($dataAtual->greaterThan($dataFinal)) {
                    $demanda->atrasada = 1;
                } else {
                    $demanda->atrasada = 0;
                }
            }
            $demanda->save();
            
            $bodyEmail = 'O job '.$demanda->id. ' foi entregue e aguarda sua análise para fechamento.' . '<br/>'. 'Acesse pelo link logo abaixo.';

            // Mail::send('notify-job', ['action_link' => $actionLink, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($demanda, $titleEmail) {
            //     $message->from('envios@fmfm.com.br')
            //     ->to($demanda->criador->email)
            //     ->bcc('agenciacriareof@gmail.com')
            //     ->subject($titleEmail);
                 
            // });
            
        }

        return back()->with('success', 'Job alterado com sucesso.' ); 
    
    }

   
    public function startAgenda(Request $request, $id){
        $user = Auth::User();
        $demandaPrazo = DemandaTempo::where('id', $id)->with('agencia')->first();
        $demandaPrazo->iniciado = date('Y-m-d H:i:s');
        $demandaPrazo->save();
        $demandaPrazoN = preg_replace("/[^0-9]/", "", $demandaPrazo->status);

        $demanda = Demanda::find($request->demandaId);
        $demanda->em_pauta = 1;
        $demanda->save();

        $notificacao = new Notificacao();
        $notificacao->demanda_id = $demandaPrazo->demanda_id;
        $notificacao->criado = date('Y-m-d H:i:s');
        $notificacao->visualizada = '0';
        $notificacao->conteudo = 'Agência ' . $demandaPrazo->agencia->nome .' iniciou a alteração ' . $demandaPrazoN .'.';
        $notificacao->tipo = 'criada';
        $notificacao->usuario_id = $demanda->criador_id;
        $notificacao->save();
        
        $newTimeLine = new LinhaTempo();
        $newTimeLine->demanda_id = $demanda->id;
        $newTimeLine->usuario_id = $user->id;
        $newTimeLine->code = 'iniciada-alteracao';
        
        $newTimeLine->status = 'Em pauta A' . $demandaPrazoN;
        $newTimeLine->criado = date('Y-m-d H:i:s');
        $newTimeLine->save();

        return back()->with('success', 'Alteração iniciada com sucesso.' ); 

    }

    public function acceptTime(Request $request, $id){
        $demandaPrazo = DemandaTempo::where('id', $id)
        ->with('agencia')
        ->with(['demanda' => function($query) {
            $query->select('criador_id', 'id');
        }])
        ->first();

         //pegar numero
         if (preg_match('/\d+/', $demandaPrazo->status, $matches)) {
            $lastNumber = $matches[0];
        }

        $demandaPrazo->aceitar_agencia = 1;
        $demandaPrazo->save();
        
        //notificar
        
        $notificacao = new Notificacao();
        $notificacao->demanda_id = $demandaPrazo->demanda_id;
        $notificacao->criado = date('Y-m-d H:i:s');
        $notificacao->visualizada = '0';
        if($demandaPrazo->code_tempo === 'em-pauta'){
            $notificacao->conteudo = 'A agência ' . $demandaPrazo->agencia->nome . '  aceitou o novo prazo da ' . strtolower($demandaPrazo->status).'.';
        }else if($demandaPrazo->code_tempo === 'alteracao'){
            $notificacao->conteudo = 'A agência ' . $demandaPrazo->agencia->nome . '  aceitou o novo prazo da alteração ' . $lastNumber.'.';
        }
        $notificacao->tipo = 'criada';
        $notificacao->usuario_id = $demandaPrazo->demanda->criador_id;
        $notificacao->save();
        
        return back()->with('success', 'Prazo aceito.' ); 

    }

    public function receive($id){
        $user = Auth::User();
        $demanda = Demanda::select('id', 'recebido', 'agencia_id', 'criador_id')->where('id', $id)->with('agencia')->first();
        if($demanda){
            
            $demandaReopen = DemandaReaberta::where('demanda_id', $demanda->id)->count();
            
            $newTimeLine = new LinhaTempo();
            $newTimeLine->demanda_id = $id;
            $newTimeLine->usuario_id = $user->id;
            $newTimeLine->code = 'recebido';
            
            $newTimeLine->criado = date('Y-m-d H:i:s');

            $notificacao = new Notificacao();
            $notificacao->demanda_id = $demanda->id;
            $notificacao->criado = date('Y-m-d H:i:s');
            $notificacao->visualizada = '0';
            $notificacao->tipo = 'criada';
            $notificacao->usuario_id = $demanda->criador_id;

            if($demandaReopen == 0){
                $demanda->recebido = 1;
                $demanda->save();
                $newTimeLine->status = "Recebido";
                $notificacao->conteudo = 'A agência ' . $demanda->agencia->nome . '  recebeu o briefing.';
    
            }else{
                $demanda->recebido = 1;
                $demanda->save();
                $newTimeLine->status = "Recebido job reaberto " .$demandaReopen;
                $notificacao->conteudo = 'A agência ' . $demanda->agencia->nome . '  recebeu o job reaberto.';
            }

            $notificacao->save();
            $newTimeLine->save();
           
            return back()->with('success', 'Job recebido.'); 

        }

        return back()->with('error', 'Não foi possível receber esse job.');

    }

    public function receiveAlteration(Request $request, $id){
        $user = Auth::User();
        $demandaId = $request->demandaId;

        $demanda = Demanda::select('id', 'criador_id', 'agencia_id')->where('id', $demandaId)->with('agencia')->first();

        $demandaTempo = DemandaTempo::find($id);

        $demandaTempoN = preg_replace("/[^0-9]/", "", $demandaTempo->status);

        if($demandaTempo){
            $demandaTempo->recebido = 1;
            $demandaTempo->save();

            $newTimeLine = new LinhaTempo();
            $newTimeLine->demanda_id = $demandaId;
            $newTimeLine->usuario_id = $user->id;
            $newTimeLine->code = 'recebida-alteracao';
            $newTimeLine->status = "Recebida alteração " .$demandaTempoN;
            $newTimeLine->criado = date('Y-m-d H:i:s');
            $newTimeLine->save();

            //notificacao

            $notificacao = new Notificacao();
            $notificacao->demanda_id = $demanda->id;
            $notificacao->criado = date('Y-m-d H:i:s');
            $notificacao->visualizada = '0';
            $notificacao->tipo = 'criada';
            $notificacao->usuario_id = $demanda->criador_id;
            $notificacao->conteudo = 'A agência ' . $demanda->agencia->nome . '  recebeu a alteração ' .$demandaTempoN.'.';

            $notificacao->save();

            return back()->with('success', 'Alteração recebida.'); 

        }

        return back()->with('error', 'Não foi possível receber essa alteração.');
    }

}
