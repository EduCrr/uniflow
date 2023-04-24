<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demanda;
use App\Models\Questionamento;
use App\Models\User;
use App\Models\Marca;
use App\Models\Agencia;
use App\Models\DemandaImagem;
use App\Models\DemandaMarca;
use App\Models\LinhaTempo;
use App\Models\Notificacao;
use App\Models\DemandaReaberta;
use Carbon\Carbon;
use App\Models\DemandaTempo;
use Illuminate\Support\Facades\Validator;
Use Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ColaboradorController extends Controller
{
    public function index(Request $request){
        $user = Auth::User();
        $demandas = Demanda::where('criador_id', $user->id)->where('etapa_1', 1)->where('etapa_2', 1)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with(['agencia' => function ($query) {
            $query->where('excluido', null);
        }])->with(['demandasReabertas' => function ($query) {
            $query->where('finalizado', null);
            $query->where('excluido', null);
        }])->withCount(['questionamentos as count_questionamentos' => function ($query) {
            $query->where('visualizada_col', 0)->where('excluido', null);
        }])->orderBy('id', 'DESC')->where('excluido', null)->paginate(15);

        foreach($demandas as $key => $item){
            $demandasReabertas = $item->demandasReabertas;
            if ($demandasReabertas->count() > 0) {
                $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                $item->final = $sugerido;
            }
        }


        $events = array();
        $demandasEvents = Demanda::select('titulo', 'inicio', 'final', 'id', 'cor')->where('etapa_1', 1)->where('etapa_2', 1)->where('criador_id', $user->id)->where('em_pauta', '1')->where('excluido', null)->with(['demandasReabertas' => function ($query) {
            $query->where('excluido', null);
            $query->where('finalizado', null);
        }])->where('pausado', 0)->get();
        
        // $emPautaCount = count($demandasEvents);
        // $finalizadosCount =  Demanda::where('criador_id', $user->id)->where('finalizada', '1')->where('excluido', null)->where('pausado', '0')->where('etapa_1', 1)->where('etapa_2', 1)->count();
        // $pendentesCount = Demanda::where('criador_id', $user->id)->where('finalizada', '0')->where('em_pauta', '0')->where('entregue', '0')->where('pausado', '0')->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->count();
        // $pausadosCount = Demanda::where('criador_id', $user->id)->where('pausado', '1')->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->count();
        // $entregueCount = Demanda::where('criador_id', $user->id)->where('entregue', '1')->where('finalizada', '0')->where('pausado', 0)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->count();

        // $questsIds =  Demanda::select('id')->where('criador_id', $user->id)->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('pausado', 0)->orderBy('criado', 'DESC')->take(15)->get();

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
        
        // $quests = Questionamento::whereIn('demanda_id', $questsIds)->where('excluido', null)->orderBy('criado', 'DESC')->with('respostas.usuario')->take(15)->get();

        return view('Dashboard/index', [
            'demandas' => $demandas,
            // 'quests' => $quests,
            'events' => $events,
            // 'emPautaCount' => $emPautaCount,
            // 'finalizadosCount' => $finalizadosCount,
            // 'pendentesCount' => $pendentesCount,
            // 'pausadosCount' => $pausadosCount,
            // 'entregueCount' => $entregueCount
        ]);
    }

    public function changeCategory(Request $request){
        $user = Auth::User();
    
        $demandas = Demanda::where('criador_id', $user->id)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with(['agencia' => function ($query) {
            $query->where('excluido', null);
        }])->with(['demandasReabertas' => function ($query) {
            $query->where('excluido', null);
            $query->where('finalizado', null);
        }])->withCount(['questionamentos as count_questionamentos' => function ($query) {
            $query->where('visualizada_col', 0)->where('excluido', null);
        }])->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->orderBy('id', 'DESC');


        if($request->category_id == 'pendentes'){
            $demandas->where('em_pauta', '0')->where('finalizada', '0')->where('entregue', '0')->where('pausado', 0)->take(15);
            }else if($request->category_id == 'em_pauta'){
                $demandas->where('em_pauta', '1')->where('finalizada', '0')->where('entregue', '0')->where('pausado', 0)->take(15);
            }else if($request->category_id == 'pausados'){
            $demandas->where('pausado', '1')->take(15);
            }
            else if($request->category_id == 'entregue'){
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

    public function jobs(Request $request){
        $user = Auth::User();

        $search = $request->search;
        $aprovada = $request->aprovada;
        $priority = $request->category_id;
        $inTime = $request->in_tyme;
        $marca = $request->marca_id;
        $agencia = $request->agencia_id;
        $dateRange = $request->dateRange;

        $demandas = Demanda::where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->where('criador_id', $user->id)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with(['agencia' => function ($query) {
        $query->where('excluido', null);
        }])->with(['demandasReabertas' => function ($query) {
            $query->where('excluido', null);
            $query->where('finalizado', null);
        }])->withCount(['questionamentos as count_questionamentos' => function ($query) {
            $query->where('visualizada_col', 0)->where('excluido', null);
        }])->withCount(['notificacoes as count_notificacoes' => function ($query) use ($user) {
            $query->where('visualizada', 0)->where('usuario_id', $user->id)->where('clicado', null);
        }])->orderBy('id', 'DESC');

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

        // if($month){
        //     $demandas->whereMonth('inicio', '=', $month);
        // }

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
        }else{
            $dateRange = '';
        }


        if($priority){
            $demandas->where('prioridade', $priority);
        }

        if($marca != '0' && $marca){
            $demandas->whereHas('marcas', function($query)  use($marca){
                $query->where('marcas.id', $marca);
                $query->where('marcas.excluido', null);
            });
        }

        if($agencia != '0' && $agencia){
            $demandas->whereHas('agencia', function($query)  use($agencia){
                $query->where('agencias.id', $agencia);
                $query->where('agencias.excluido', null );
            });
        }

        $demandas = $demandas->paginate(15)->withQueryString();

         foreach ($demandas as $demanda) {
        //     if ($demanda->finalizada == 1) {
        //         $porcentagem = 100;
        //     } else {
        //         // Obter o total de prazosDaPauta finalizados da demanda
        //         $totalFinalizados = $demanda->prazosDaPauta()->whereNotNull('finalizado')->count();
            
        //         // Obter o total de prazosDaPauta não finalizados da demanda
        //         $totalNaoFinalizados = $demanda->prazosDaPauta()->whereNull('finalizado')->count();
               
        //         // Calcular a porcentagem com base nos prazosDaPauta finalizados e não finalizados da demanda
        //         $totalPrazos = $totalFinalizados + $totalNaoFinalizados;
        //         if ($totalPrazos == 0) {
        //             $porcentagem = 0;
        //         } elseif ($totalFinalizados == 0) {
        //             $porcentagem = 10;
        //         } else {
        //             $porcentagem = round(($totalFinalizados / $totalPrazos) * 95);
        //         }
        //     }
        //     // Adicionar a porcentagem como um atributo da demanda
        //     $demanda->porcentagem = $porcentagem;

        //     //ajustar final quando estiver reaberta

            $demandasReabertas = $demanda->demandasReabertas;
            if ($demandasReabertas->count() > 0) {
                $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                $demanda->final = $sugerido;
            }
        }

        // $brands = Marca::where('excluido', null)->get();
        $brands = User::where('id', $user->id)->with('marcas')->first();
        // $agencies = Agencia::where('excluido', null)->get();
        $agencies = $user->colaboradoresAgencias()->where('excluido', null)->get();
    
        return view('Dashboard/jobs', [
            'demandas' => $demandas,
            'search' => $search,
            'priority' => $priority,
            'aprovada' => $aprovada,
            'inTime' => $inTime,
            'brands' => $brands['marcas'],
            'marca' => $marca,
            'agencies' => $agencies,
            'agencia' => $agencia,
            'dateRange' => $dateRange
        ]);
            
    }

    public function create(){
        $user = Auth::User();
        $dataAtual = Carbon::now();

        $userInfos = User::where('id', $user->id)->where('excluido', null)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with(['colaboradoresAgencias' => function ($query) {
            $query->where('excluido', null);
            }])->first();


        return view('Dashboard/criar', [
            'userInfos' => $userInfos,
            'dataAtual' => $dataAtual
        ]);
        
    }

    public function createAction(Request $request){
        $user = Auth::User();
        
        $validator = Validator::make($request->all(),[
            'titulo' => 'required|min:3',
            'agencia' => 'required',
            'inicio' => 'required',
            'final' => 'required',
            'marcas' => 'required',
            'prioridade' => 'required',
            ],[
            'titulo.required' => 'Preencha o campo título.',
            'titulo.min' => 'O campo título deve ter pelo menos 3 caracteres.',
            'agencia.required' => 'Preencha o campo agencia.',
            'inicio.required' => 'Preencha o campo data inicial.',
            'final.required' => 'Preencha o campo data final.',
            'marcas.required' => 'Preencha o campo setor.',
            'prioridade.required' => 'Preencha o campo prioridade.',
        ]
    );
        
        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){

            $cor = null;
            if($request->prioridade == '1'){
                $cor = '#3dbb3d';
            }else if($request->prioridade == '5'){
                $cor = '#f9bc0b';
            }else if($request->prioridade == '7'){
                $cor = '#fb3232';
            }else if($request->prioridade == '10'){
                $cor = '#000';
            }

            $newJob = new Demanda();
            $newJob->titulo = $request->titulo .' '.$request->id;
            $newJob->criador_id = $user->id;
            $newJob->briefing = '<p><strong>Metas e objetivos</strong></p><p><em>Em uma frase, descrever o que precisamos resolver, qual o problema a ser resolvido? E qual o objetivo, onde queremos chegar?</em></p><p><strong>Pe&ccedil;as necess&aacute;rias</strong></p><p><em><strong>&nbsp;</strong>Existe mais de uma pe&ccedil;a para ser produzida? Este &eacute; o momento de descrev&ecirc;-la.</em></p><p><strong>Formato (Item para selecionar impresso ou digital, e ainda campo para palavras.)&nbsp;</strong></p><p><em>Existe alguma formata&ccedil;&atilde;o especial (com dobra, com faca especial....)? Como o arquivo deve ser entregue (JPG, PNG, v&iacute;deo, PDF impress&atilde;o, PDF edit&aacute;vel, etc.)</em></p><p><strong>Dimens&otilde;es (n&atilde;o &eacute; item obrigat&oacute;rio)</strong></p><p><em>Medidas (cm ou px), quando necess&aacute;rio.</em></p><p><strong>Descri&ccedil;&atilde;o</strong></p><p><em>Descreva sua interpreta&ccedil;&atilde;o do briefing, citando todos os itens das etapas anteriores. Traga exemplos, deixe mais claro suas expectativas e objetivos.</em></p>';
            $newJob->agencia_id = $request->agencia;
            $newJob->inicio = $request->inicio;
            $newJob->final = $request->final;
            $newJob->prioridade = $request->prioridade;
            $newJob->cor = $cor;
            $newJob->etapa_1 = 1;
            $newJob->criado = date('Y-m-d H:i:s');
            $newJob->save();

            $marcasIds = $request->marcas;

            foreach($marcasIds as $item){
                $demandaMarcas = new DemandaMarca();
                $demandaMarcas->marca_id = $item;
                $demandaMarcas->demanda_id = $newJob->id;
                $demandaMarcas->save();
            }

            // return back()->with('success', 'Etapa 1 criada' );  
            return redirect()->route('Job.criar_etapa_2', ['id' => $newJob->id])->with('success', 'Etapa 1 criada com sucesso!');

        }

    }

    public function createStage2($id){
        $user = Auth::User();
        $demanda = Demanda::where('id', $id)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->first();
        
        $marcas = $user->marcas()->whereNull('excluido')->get();

        $marcasIds = array();
 
        foreach($demanda['marcas'] as $marca){
            array_push($marcasIds, $marca->id);
        }
 
        $agencia = Agencia::where('id', $demanda->agencia_id)->where('excluido', null)->first();
        if($demanda){
            if($demanda->etapa_2 == 0){
                return view('Dashboard/criar-etapa-2', [
                    'demanda' => $demanda,
                    'marcas' => $marcas,
                    'marcasIds' => $marcasIds,
                    'agencia' => $agencia,
                ]);
            }else{
                return redirect('/dashboard');
            }
        }
        
    }

    public function createActionStage2(Request $request, $id){
        $user = Auth::User();
        
        $validator = Validator::make($request->all(),[
            'titulo' => 'required|min:3',
            'agencia' => 'required',
            'inicio' => 'required',
            'final' => 'required',
            'marcas' => 'required',
            'prioridade' => 'required',
            'briefing' => 'required|min:3',       
             
            ],[
            'titulo.required' => 'Preencha o campo título.',
            'titulo.min' => 'O campo título deve ter pelo menos 3 caracteres.',
            'agencia.required' => 'Preencha o campo agencia.',
            'inicio.required' => 'Preencha o campo data inicial.',
            'final.required' => 'Preencha o campo data final.',
            'marcas.required' => 'Preencha o campo setor.',
            'prioridade.required' => 'Preencha o campo prioridade.',
            'briefing.required' => 'Preencha o campo briefing.',
            'briefing.min' => 'O campo briefing deve ter pelo menos 3 caracteres.',
        ]
    );
        
        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){

            $cor = null;
            if($request->prioridade == '1'){
                $cor = '#3dbb3d';
            }else if($request->prioridade == '5'){
                $cor = '#f9bc0b';
            }else if($request->prioridade == '7'){
                $cor = '#fb3232';
            }else if($request->prioridade == '10'){
                $cor = '#000';
            }

            $demanda = Demanda::where('excluido', null)->find($id);

            if($request->titulo){
                $demanda->titulo = $request->titulo;
            }
            
            if($request->drive){
                $demanda->drive = $request->drive;
            }

            if($request->inicio){
                $demanda->inicio = $request->inicio;
            }

            if($request->final){
                $demanda->final = $request->final;
            }

            if($request->prioridade){
                $demanda->prioridade = $request->prioridade;
            }

            if($request->cor){
                $demanda->cor = $cor;
            }

            if($request->agencia){
                $demanda->agencia_id = $request->agencia;
            }

            if($request->marcas){
                
                $marcasIds = $request->marcas;
                DemandaMarca::where('demanda_id', $id)->whereNotIn('marca_id', $marcasIds)->delete();
                
                foreach($marcasIds as $item){
                    $demandaMarca = DemandaMarca::updateOrCreate([
                        'marca_id' => $item,
                        'demanda_id' => $id
                    ], [
                        'marca_id' => $item,
                        'demanda_id' => $demanda->id,
                    ]);
                
                }
            }

            if ($request->hasFile('arquivos')) {
                $arqs = $request->file('arquivos');
                
                foreach($arqs as $item){
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
            
                    $newPostPhoto = new DemandaImagem();
                    $newPostPhoto->demanda_id =  $demanda->id;
                    $newPostPhoto->imagem = $photoName;
                    $newPostPhoto->usuario_id = $user->id;
                    $newPostPhoto->criado = date('Y-m-d H:i:s');
                    $newPostPhoto->save();
                }
            }

            $demanda->etapa_2 = 1;
            $demanda->save();

            $newTimeLine = new LinhaTempo();
            $newTimeLine->demanda_id = $demanda->id;
            $newTimeLine->status = 'Job cadastrado';
            $newTimeLine->code = 'criado';
            $newTimeLine->usuario_id = $user->id;
            $newTimeLine->criado = date('Y-m-d H:i:s');
            $newTimeLine->save();


            // if($request->all('input') !== null){
            //     $usuarioDemandaData = $request->all('input');
            //     if($usuarioDemandaData['input'] !== null){
            //          foreach($usuarioDemandaData['input'] as $item){
            //             $demandaUsuario = DemandaUsuario::updateOrCreate([
            //                 'usuario_id' => $item['usuario_id'],
            //                 'demanda_id' => $demanda->id,
            //             ], [
                            
            //                 'tarefa' => $item['tarefa'],
            //                 'inicio' => $item['inicio'],
            //                 'status' => 'aberto',
            //                 'final' => $item['final'],
            //                 'usuario_id' => $item['usuario_id'],
            //                 'demanda_id' => $demanda->id,
                            
            //             ]);
            //         }
            //     }
               
            // }

            //notificar criador

            $criadorNotificacao = new Notificacao();
            $criadorNotificacao->demanda_id = $demanda->id;
            $criadorNotificacao->usuario_id = $user->id;
            $criadorNotificacao->conteudo = 'Novo job foi criado.';
            $criadorNotificacao->criado = date('Y-m-d H:i:s');
            $criadorNotificacao->visualizada = '0';
            $criadorNotificacao->tipo = 'criada';
            $criadorNotificacao->save();

            //send e-mail

            $actionLink = route('Job', ['id' => $demanda->id]);
            $bodyEmail = 'Seu novo job foi criado com sucesso. Acesse pelo link logo abaixo.';
            $titleEmail = 'Novo job criado';

             //notificar agencia

            $agenciaNotificacao = new Notificacao();
            $agenciaNotificacao->demanda_id = $demanda->id;
            $agenciaNotificacao->agencia_id = $request->agencia;
            $agenciaNotificacao->conteudo = 'Novo job foi criado.';
            $agenciaNotificacao->criado = date('Y-m-d H:i:s');
            $agenciaNotificacao->visualizada = '0';
            $agenciaNotificacao->tipo = 'criada';
            $agenciaNotificacao->save();
            
            //criador

            // Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $user->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($request, $user) {
            //     $message->from('envios@fmfm.com.br')
            //     ->to($user->email)
            //     ->bcc('agenciacriareof@gmail.com')
            //     ->subject('Novo job criado');
            // });

            //agencia

            $agencies = Agencia::where('id', $request->agencia)->with(['agenciasUsuarios' => function ($query) {
            $query->where('excluido', null);
            $query->select('email', 'nome');
            }])->first();

            // foreach($agencies['agenciasUsuarios'] as $item){
            //     Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $item->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($request, $item) {
            //         $message->from('envios@fmfm.com.br')
            //         ->to($item->email)
            //         ->bcc('agenciacriareof@gmail.com')
            //         ->subject('Novo job criado');
            //     });
            // }
       
            return redirect()->route('Job', ['id' => $demanda->id])->with('success', 'Job criado com sucesso!');
        }

    }

    public function edit($id){
        $user = Auth::User();
        $demanda = Demanda::where('id', $id)->where('criador_id', $user->id)->with('imagens')->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->first();
        
        $marcas = $user->marcas()->whereNull('excluido')->get();


        $marcasIds = array();
 
        foreach($demanda['marcas'] as $marca){
            array_push($marcasIds, $marca->id);
        }
 
        $agencia = Agencia::where('id', $demanda->agencia_id)->where('excluido', null)->first();
        if($demanda){
            return view('Dashboard/editar', [
                'demanda' => $demanda,
                'marcas' => $marcas,
                'marcasIds' => $marcasIds,
                'agencia' => $agencia,
            ]);
        }
        return redirect('/dashboard');
       
    }

    public function editAction(Request $request, $id){
        $user = Auth::User();
       
        $cor = null;
        if($request->prioridade == '1'){
            $cor = '#3dbb3d';
        }else if($request->prioridade == '5'){
            $cor = '#f9bc0b';
        }else if($request->prioridade == '7'){
            $cor = '#fb3232';
        }else if($request->prioridade == '10'){
            $cor = '#000';
        }

        $validator = Validator::make($request->all(),[
            'titulo' => 'required|min:3',
            'agencia' => 'required',
            'inicio' => 'required',
            'final' => 'required',
            'marcas' => 'required',
            'prioridade' => 'required',
            'briefing' => 'required|min:3'
            ],[
                'titulo.required' => 'Preencha o campo título.',
                'titulo.min' => 'O campo título deve ter pelo menos 3 caracteres.',
                'agencia.required' => 'Preencha o campo agencia.',
                'inicio.required' => 'Preencha o campo data inicial.',
                'final.required' => 'Preencha o campo data final.',
                'marcas.required' => 'Preencha o campo setor.',
                'prioridade.required' => 'Preencha o campo prioridade.',
                'briefing.required' => 'Preencha o campo briefing.',
                'briefing.min' => 'O campo briefing deve ter pelo menos 3 caracteres.',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            // $hasAlterationCount = LinhaTempo::where('demanda_id', $id)->where('code', 'jobEditado')->count();
            // $newTimeLine = new LinhaTempo();
            // $newTimeLine->demanda_id = $id;
            // $newTimeLine->usuario_id = $user->id;
            // $newTimeLine->criado = date('Y-m-d H:i:s');

            // if($hasAlterationCount == 0){
            //     $newTimeLine->status = 'Job editado 1';
            //     $newTimeLine->code = 'jobEditado';
            //     $newTimeLine->save();
            // }else{
            //     $newTimeLine->status = 'Job editado '.($hasAlterationCount + 1);
            //     $newTimeLine->code = 'jobEditado';
            //     $newTimeLine->save();
            // }
           
            
            $demanda = Demanda::where('id', $id)->where('excluido', null)->first();

            if($request->titulo){
                $demanda->titulo = $request->titulo;
            }

            if($request->drive){
                $demanda->drive = $request->drive;
            }

            // if($request->agencia){
            //     $demanda->agencia_id = $request->agencia;
            // }

             if($request->prioridade){
                $demanda->prioridade = $request->prioridade;
                $demanda->cor = $cor;
            }

            if($request->inicio){
                $demanda->inicio = $request->inicio;
            }

            if($request->final){
                $demanda->final = $request->final;
            }

            if($request->marcas){
                
                $marcasIds = $request->marcas;
                DemandaMarca::where('demanda_id', $id)->whereNotIn('marca_id', $marcasIds)->delete();
                
                foreach($marcasIds as $item){
                    $demandaMarca = DemandaMarca::updateOrCreate([
                        'marca_id' => $item,
                        'demanda_id' => $id
                    ], [
                        'marca_id' => $item,
                        'demanda_id' => $demanda->id,
                    ]);
                
                }
            }

            if ($request->hasFile('arquivos')) {
                $arqs = $request->file('arquivos');
                foreach($arqs as $item){
                    $extension = $item->extension();
                    $file = $item->getClientOriginalName();
                    $fileName = pathinfo($file, PATHINFO_FILENAME);
                    $destImg = public_path('assets/images/files');
                    $photoName =  '';
                    $i = 1;

                    while(file_exists($destImg . '/' . $photoName)){
                        $photoName = $fileName . '_' . $i . '.' . $extension;
                        $i++;
                    }
                    $item->move($destImg, $photoName);
        
                    $newPostPhoto = new DemandaImagem();
                    $newPostPhoto->demanda_id =  $demanda->id;
                    $newPostPhoto->usuario_id =  $user->id;
                    $newPostPhoto->imagem = $photoName;
                    $newPostPhoto->criado = date('Y-m-d H:i:s');
                    $newPostPhoto->save();
                }
            }
            
            if($request->briefing){
                $demanda->briefing = $request->briefing;
            }

            $demanda->save();

            return back()->with('success', 'Job editado.' );  

        }
    }

    public function copy($id){
        $user = Auth::User();
        $demanda = Demanda::where('id', $id)->where('criador_id', $user->id)->with('imagens')->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->first();
        
        $marcas = $user->marcas()->whereNull('excluido')->get();

        $marcasIds = array();
 
        foreach($demanda['marcas'] as $marca){
            array_push($marcasIds, $marca->id);
        }

        
        $agencias = $user->colaboradoresAgencias()->whereNull('excluido')->get();

        if($demanda){
            return view('Dashboard/copiar', [
                'demanda' => $demanda,
                'marcas' => $marcas,
                'marcasIds' => $marcasIds,
                'agencias' => $agencias,
            ]);
        }
        return redirect('/dashboard');
       
    }

    public function copyAction(Request $request){
        $user = Auth::User();
        
        $validator = Validator::make($request->all(),[
            'titulo' => 'required|min:3',
            'agencia' => 'required',
            'inicio' => 'required',
            'final' => 'required',
            'marcas' => 'required',
            'prioridade' => 'required',
            'briefing' => 'required|min:3',       
            'input.*.tarefa' => 'required',
            'input.*.inicio' => 'required',
            'input.*.usuario_id' => 'required',
            'input.*.final' => 'required',    
            ],[
            'titulo.required' => 'Preencha o campo título.',
            'titulo.min' => 'O campo título deve ter pelo menos 3 caracteres.',
            'agencia.required' => 'Preencha o campo agencia.',
            'inicio.required' => 'Preencha o campo data inicial.',
            'final.required' => 'Preencha o campo data final.',
            'marcas.required' => 'Preencha o campo setor.',
            'prioridade.required' => 'Preencha o campo prioridade.',
            'briefing.required' => 'Preencha o campo briefing.',
            'briefing.min' => 'O campo briefing deve ter pelo menos 3 caracteres.',
            'input.*.tarefa.required' => 'Preencha o campo tarefa.',
            'input.*.usuario_id.required' => 'Preencha o campo usuario',
            'input.*.inicio.required' => 'Preencha o campo inicio',
            'input.*.final.required' => 'Preencha o campo final.',
        ]);
        
        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){

            $cor = null;
            if($request->prioridade == '1'){
                $cor = '#3dbb3d';
            }else if($request->prioridade == '5'){
                $cor = '#f9bc0b';
            }else if($request->prioridade == '7'){
                $cor = '#fb3232';
            }else if($request->prioridade == '10'){
                $cor = '#000';
            }

            $newJob = new Demanda();
            $newJob->titulo = $request->titulo;
            if($request->drive){
                $newJob->drive = $request->drive;
            }
            $newJob->briefing = $request->briefing;
            $newJob->criador_id = $user->id;
            $newJob->agencia_id = $request->agencia;
            $newJob->inicio = $request->inicio;
            $newJob->final = $request->final;
            $newJob->prioridade = $request->prioridade;
            $newJob->cor = $cor;
            $newJob->criado = date('Y-m-d H:i:s');
            $newJob->save();

            $newTimeLine = new LinhaTempo();
            $newTimeLine->demanda_id = $newJob->id;
            $newTimeLine->status = 'Job cadastrado';
            $newTimeLine->code = 'criado';
            $newTimeLine->usuario_id = $user->id;
            $newTimeLine->criado = date('Y-m-d H:i:s');
            $newTimeLine->save();

            if ($request->hasFile('arquivos')) {
                $arqs = $request->file('arquivos');
                
                foreach($arqs as $item){
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
            
                    $newPostPhoto = new DemandaImagem();
                    $newPostPhoto->demanda_id =  $newJob->id;
                    $newPostPhoto->imagem = $photoName;
                    $newPostPhoto->usuario_id = $user->id;
                    $newPostPhoto->criado = date('Y-m-d H:i:s');
                    $newPostPhoto->save();
                }
            }

            $marcasIds = $request->marcas;

            foreach($marcasIds as $item){
                $demandaMarcas = new DemandaMarca();
                $demandaMarcas->marca_id = $item;
                $demandaMarcas->demanda_id = $newJob->id;
                $demandaMarcas->save();
            }

            //notificar criador

            $criadorNotificacao = new Notificacao();
            $criadorNotificacao->demanda_id = $newJob->id;
            $criadorNotificacao->usuario_id = $user->id;
            $criadorNotificacao->conteudo = 'Novo job foi criado.';
            $criadorNotificacao->criado = date('Y-m-d H:i:s');
            $criadorNotificacao->visualizada = '0';
            $criadorNotificacao->tipo = 'criada';
            $criadorNotificacao->save();

            //send e-mail

            $actionLink = route('Job', ['id' => $newJob->id]);
            $bodyEmail = 'Seu novo job foi criado com sucesso. Acesse pelo link logo abaixo.';
            $titleEmail = 'Novo job criado';

            //notificar agencia

            $agenciaNotificacao = new Notificacao();
            $agenciaNotificacao->demanda_id = $newJob->id;
            $agenciaNotificacao->agencia_id = $request->agencia;
            $agenciaNotificacao->conteudo = 'Novo job foi criado.';
            $agenciaNotificacao->criado = date('Y-m-d H:i:s');
            $agenciaNotificacao->visualizada = '0';
            $agenciaNotificacao->tipo = 'criada';
            $agenciaNotificacao->save();
            
            //criador

            // Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $user->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($request, $user) {
            //     $message->from('envios@fmfm.com.br')
            //     ->to($user->email)
            //     ->bcc('agenciacriareof@gmail.com')
            //     ->subject('Novo job criado');
            // });

            //agencia

            $agencies = Agencia::where('id', $request->agencia)->with(['agenciasUsuarios' => function ($query) {
            $query->where('excluido', null);
            $query->select('email', 'nome');
            }])->first();

            // foreach($agencies['agenciasUsuarios'] as $item){
            //     Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $item->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($request, $item) {
            //         $message->from('envios@fmfm.com.br')
            //         ->to($item->email)
            //         ->bcc('agenciacriareof@gmail.com')
            //         ->subject('Novo job criado');
            //     });
            // }
       
            return back()->with('success', 'Job copiado' );  
        }

    }

    public function reOpenJob(Request $request, $id){
        $user = Auth::User();
        $newTimeLine = new LinhaTempo();
        $newTimeLine->demanda_id = $id;
        $newTimeLine->usuario_id = $user->id;
        $titleEmail = '';
        $newTimeLine->criado = date('Y-m-d H:i:s');
       

        $hasReopenCount = LinhaTempo::where('demanda_id', $id)->where('code', 'reaberto')->count();
        //10
        if($hasReopenCount == 0){
            $newTimeLine->status = "Reaberto 1";
            $newTimeLine->code = "reaberto";
            $newTimeLine->save();
            $titleEmail = "Reaberto 1";

        }else{
            $newTimeLine->status = "Reaberto ".($hasReopenCount + 1);
            $newTimeLine->code = "reaberto";
            $newTimeLine->save();
            $titleEmail = "Reaberto ".($hasReopenCount + 1);
        }
        //change select

        $demanda = Demanda::where('id', $id)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->first();

        $demandaReaberta = new DemandaReaberta();
        $demandaReaberta->demanda_id = $id;
        $demandaReaberta->iniciado = date('Y-m-d H:i:s');
        $demandaReaberta->status =  $newTimeLine->status;
        $demandaReaberta->sugerido = $request->sugerido_reaberto;
        $demandaReaberta->save();

        $demanda->em_pauta = 0;
        $demanda->finalizada = 0;
        $demanda->entregue = 0;
        $demanda->recebido = 0;
        $demanda->em_alteracao = 0;
        $demanda->entregue_recebido = 0;
        $demanda->save();

        $actionLink = route('Job', ['id' => $id]);
        $bodyEmail = 'O job '.$id . ' foi reaberto.'. '<br/>'.  'Acesse pelo link logo abaixo.';

        $agencies = Agencia::where('id', $demanda->agencia_id)->with(['agenciasUsuarios' => function ($query) {
            $query->where('excluido', null);
            $query->select('email', 'nome');
        }])->first();

        $agenciaNotificacao = new Notificacao();
        $agenciaNotificacao->agencia_id = $demanda->agencia_id;
        $agenciaNotificacao->demanda_id = $demanda->id;
        $agenciaNotificacao->conteudo = 'Job reaberto.';
        $agenciaNotificacao->criado = date('Y-m-d H:i:s');
        $agenciaNotificacao->visualizada = '0';
        $agenciaNotificacao->tipo = 'reaberto';
        $agenciaNotificacao->save();

        // foreach($agencies['agenciasUsuarios'] as $item){
        //     Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $item->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($item, $titleEmail, $id) {
        //         $message->from('envios@fmfm.com.br')
        //         ->to($item->email)
        //         ->bcc('agenciacriareof@gmail.com')
        //         ->subject('O job '. $id . ' foi o reaberto');
                
        //         // $message->from('agenciacriareof@gmail.com');
        //         // $message->to($item->email)->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
        //     });
        // }

        return back()->with('success', 'Job reaberto' );  
    }

    public function finalize($id){
        $user = Auth::User();
        $titleEmail = '';

        $hasFinalizeCount = LinhaTempo::where('demanda_id', $id)->where('code', 'finalizado')->count();
        $demanda = Demanda::where('id', $id)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->first();
        
        $lineTime = new LinhaTempo();
        $lineTime->demanda_id = $id;
        $lineTime->usuario_id = $user->id;
        $lineTime->criado = date('Y-m-d H:i:s');

        if($hasFinalizeCount == 0){
            $lineTime->status = 'Finalizado 1';
            $lineTime->code = 'finalizado';
            $lineTime->save();
            $titleEmail = 'Finalizado 1';
        }else{
            $lineTime->status = 'Finalizado '.($hasFinalizeCount + 1);
            $lineTime->code = 'finalizado';
            $lineTime->save();
            $titleEmail = 'Finalizado '.($hasFinalizeCount + 1);
        }

        $actionLink = route('Job', ['id' => $id]);
        $bodyEmail = 'O job '.$id . ' foi finalizado com sucesso.'. '<br/>'.  'Acesse pelo link logo abaixo.';

        $agencies = Agencia::where('id', $demanda->agencia_id)->with(['agenciasUsuarios' => function ($query) {
            $query->where('excluido', null);
            $query->select('email', 'nome');
        }])->first();

        //demandas reabertas
        $demandasReabertas = DemandaReaberta::where('demanda_id', $id)->get();

        foreach($demandasReabertas as $item){
            if($item->finalizado == null){
                $item->finalizado = date('Y-m-d H:i:s');
                $item->save();
            }
        }

        // // criar a data atual
        // $dataAtual = Carbon::now();

        // // converter para o fuso horário da América/São_Paulo
        // $dataAtual->setTimezone('America/Sao_Paulo');

        // // criar a data final
        // $dataFinal = Carbon::createFromFormat('Y-m-d H:i:s', $demanda->final);

        // // verificar se este trabalho foi reaberto
        // $verifyReOpenJob = DemandaReaberta::where('demanda_id', $id)->orderBy('id', 'DESC')->first();

        // if ($verifyReOpenJob) {
        //     // converter a data final para o fuso horário da América/São_Paulo
        //     $dataFinal = Carbon::createFromFormat('Y-m-d H:i:s', $verifyReOpenJob->sugerido);
        //     $dataFinal->setTimezone('America/Sao_Paulo');
        // }

        // // comparar as datas
        // if ($dataAtual->greaterThan($dataFinal)) {
        //     $demanda->atrasada = 1;
        // } else {
        //     $demanda->atrasada = 0;
        // }

        $demanda->finalizada = 1;
        $demanda->entregue = 0;
        $demanda->em_pauta = 0;
        $demanda->save();
        
        $agenciaNotificacao = new Notificacao();
        $agenciaNotificacao->agencia_id = $demanda->agencia_id;
        $agenciaNotificacao->demanda_id = $demanda->id;
        $agenciaNotificacao->conteudo = 'Job finalizado.';
        $agenciaNotificacao->criado = date('Y-m-d H:i:s');
        $agenciaNotificacao->visualizada = '0';
        $agenciaNotificacao->tipo = 'finalizado';
        $agenciaNotificacao->save();

        // foreach($agencies['agenciasUsuarios'] as $item){
        //     Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $item->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($item, $titleEmail, $id) {
        //         $message->from('envios@fmfm.com.br')
        //         ->to($item->email)
        //         ->bcc('agenciacriareof@gmail.com')
        //         ->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
               
        //         // $message->from('agenciacriareof@gmail.com');
        //         // $message->to($item->email)->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
        //     });
        // }

        return back()->with('success', 'Job finalizado com sucesso.' );  

        //notificar
    }

    public function pause($id){
        $user = Auth::User();
        $titleEmail = 'Congelado';

        $demanda = Demanda::where('id', $id)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->first();

        $actionLink = route('Job', ['id' => $id]);
        $bodyEmail = 'O job '.$id . ' foi congelado.'. '<br/>'.  'Acesse pelo link logo abaixo.';

        $agencies = Agencia::where('id', $demanda->agencia_id)->with(['agenciasUsuarios' => function ($query) {
            $query->where('excluido', null);
            $query->select('email', 'nome');
        }])->first();

        $agenciaNotificacao = new Notificacao();
        $agenciaNotificacao->agencia_id = $demanda->agencia_id;
        $agenciaNotificacao->demanda_id = $demanda->id;
        $agenciaNotificacao->conteudo = 'Job congelado.';
        $agenciaNotificacao->criado = date('Y-m-d H:i:s');
        $agenciaNotificacao->visualizada = '0';
        $agenciaNotificacao->tipo = 'congelado';

        // foreach($agencies['agenciasUsuarios'] as $item){
        //     Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $item->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($item, $titleEmail, $id) {
        //         $message->from('envios@fmfm.com.br')
        //         ->to($item->email)
        //         ->bcc('agenciacriareof@gmail.com')
        //         ->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
               
        //         // $message->from('agenciacriareof@gmail.com');
        //         // $message->to($item->email)->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
        //     });
        // }


        $demanda->pausado = 1;
        $demanda->save();
        $agenciaNotificacao->save();

        return back()->with('success', 'Job pausado com sucesso.' );  

    }

    public function resume(Request $request, $id){
        $user = Auth::User();
        $titleEmail = 'Retomado';

        $demanda = Demanda::where('id', $id)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->first();

        $actionLink = route('Job', ['id' => $id]);
        $bodyEmail = 'O job '.$id . ' foi retomado com sucesso.'. '<br/>'.  'Acesse pelo link logo abaixo.';

        $agencies = Agencia::where('id', $demanda->agencia_id)->with(['agenciasUsuarios' => function ($query) {
            $query->where('excluido', null);
            $query->select('email', 'nome');
        }])->first();

        $agenciaNotificacao = new Notificacao();
        $agenciaNotificacao->agencia_id = $demanda->agencia_id;
        $agenciaNotificacao->demanda_id = $demanda->id;
        $agenciaNotificacao->conteudo = 'Job retomado.';
        $agenciaNotificacao->criado = date('Y-m-d H:i:s');
        $agenciaNotificacao->visualizada = '0';
        $agenciaNotificacao->tipo = 'retomado';

        // foreach($agencies['agenciasUsuarios'] as $item){
        //     Mail::send('notify-job', ['action_link' => $actionLink, 'nome' => $item->nome, 'body' => $bodyEmail, 'titulo' => $titleEmail], function($message) use ($item, $titleEmail, $id) {
        //         $message->from('envios@fmfm.com.br')
        //         ->to($item->email)
        //         ->bcc('agenciacriareof@gmail.com')
        //         ->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
               
        //         // $message->from('agenciacriareof@gmail.com');
        //         // $message->to($item->email)->subject('O job '. $id . ' alterou o status para: ' . $titleEmail);
        //     });
        // }

        $demandasReabertasCount = DemandaReaberta::where('demanda_id', $id)->count();
        $demandasReabertas = DemandaReaberta::where('demanda_id', $id)->orderBy('id', 'desc')->first();

        if($demandasReabertasCount == 0){
            if($request->newFinalDate != null){
                $demanda->final = $request->newFinalDate;
            }
        }else{
            if($request->newFinalDate != null){
                $demandasReabertas->sugerido = $request->newFinalDate;
            }
        }

        //pautas com nova datas que n foram concluidas

        $demandaPautas = DemandaTempo::where('demanda_id', $id)->where('finalizado', 0)->get();
        foreach($demandaPautas as $item){
            $item->aceitar_agencia = 0;
            $item->aceitar_colaborado = 0;
            $item->save();
        }

        $demanda->pausado = 0;
        $demanda->save();
        $agenciaNotificacao->save();

        return back()->with('success', 'Job retomado com sucesso.' );  

    }

    public function delete($id){

        $demanda = Demanda::where('id', $id)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->first();
        if($demanda){
            $demanda->excluido = date('Y-m-d H:i:s');
            $demanda->save();
            $deleteNotifications = Notificacao::where('demanda_id', $id)->delete();
            return back()->with('success', 'Job excluído com sucesso.' );  
        }else{
            return back()->with('error', 'Job não pode ser excluído.' );  
        }

    }

    public function getJobsByDate(Request $request){
        $date = Carbon::createFromFormat('Y-m-d\TH:i', $request->final); 
        $final = $date->toDateString(); 
        $demanda = Demanda::whereDate('final', $final)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->count();
        if($demanda >= 3){
           return response()->json($demanda);
       }else{
           return false;
       }
    }

    public function acceptTime(Request $request, $id){
        $user = Auth::User();
        $demandaPrazo = DemandaTempo::find($id);
        $demanda = Demanda::select('id', 'final')->where('id', $demandaPrazo->demanda_id)->first();
        $countDemandasReabertas = DemandaReaberta::where('demanda_id', $demanda->id)->count();
        $demandasReaberta = DemandaReaberta::where('demanda_id', $demanda->id)->orderByDesc('id')->first();
        //prazo for maior que prazo final
        if($demanda){
            if($countDemandasReabertas == 0){
                if (strtotime($demandaPrazo->sugerido) > strtotime($demanda->final)) {
                    // $request->sugeridoComment é maior que $demanda->final
                    $demanda->final = $demandaPrazo->sugerido;
                    $demanda->save();
                }
            }else{
                if (strtotime($demandaPrazo->sugerido) > strtotime($demandasReaberta->sugerido)) {
                    // $request->sugeridoComment é maior que $demanda->final
                    $demandasReaberta->sugerido = $demandaPrazo->sugerido;
                    $demandasReaberta->save();
                }
            }
        }

        //pegar numero
        if (preg_match('/\d+/', $demandaPrazo->status, $matches)) {
            $lastNumber = $matches[0];
        }
        
        $demandaPrazo->aceitar_colaborador = 1;
        $demandaPrazo->save();

        $notificacao = new Notificacao();
        $notificacao->demanda_id = $demandaPrazo->demanda_id;
        $notificacao->criado = date('Y-m-d H:i:s');
        $notificacao->visualizada = '0';
        if($demandaPrazo->code_tempo === 'em-pauta'){
            $notificacao->conteudo = $user->nome . ' aceitou o seu prazo da ' . strtolower($demandaPrazo->status) .'.';
        }else if($demandaPrazo->code_tempo === 'alteracao'){
            $notificacao->conteudo = $user->nome . ' aceitou o seu prazo da alteração  ' . $lastNumber .'.';
        }
        $notificacao->tipo = 'criada';
        $notificacao->agencia_id = $demandaPrazo->agencia_id;
        $notificacao->save();
       
        return back()->with('success', 'Prazo aceito.'); 
    }

    public function receiveAlteration(Request $request, $id){
        $user = Auth::User();
        $demanda = Demanda::where('id', $id)->where('etapa_1', 1)->where('etapa_2', 1)->where('excluido', null)->first();
        $demandaTempoCount = DemandaTempo::where('demanda_id', $demanda->id)->where('code_tempo', 'alteracao')->count();
        if($demanda){
            $demanda->entregue_recebido = 1;

            $newTimeLine = new LinhaTempo();
            $newTimeLine->demanda_id = $id;
            $newTimeLine->usuario_id = $user->id;
            $newTimeLine->code = 'recebida-alteracao';
            if($demandaTempoCount > 0){
                $newTimeLine->status = "Alteração recebida";
            }else{
                $newTimeLine->status = "Pauta recebida";
            }
            $newTimeLine->criado = date('Y-m-d H:i:s');
            
            $newTimeLine->save();
            $demanda->save();


            $notificacao = new Notificacao();
            $notificacao->demanda_id = $demanda->id;
            $notificacao->criado = date('Y-m-d H:i:s');
            $notificacao->visualizada = '0';
            $notificacao->conteudo = $user->nome . ' recebeu suas pautas.';
            $notificacao->tipo = 'criada';
            $notificacao->agencia_id = $demanda->agencia_id;
            $notificacao->save();

            return back()->with('success', 'Alteração recebida.'); 

        }
    }

    public function stages(){
        $user = Auth::User();
        $demandas = Demanda::where('criador_id', $user->id)->where('etapa_1', 1)->where('etapa_2', 0)->where('excluido', null)->with(['agencia' => function ($query) {
            $query->where('excluido', null);
        }])->get();

        if($demandas){
            return view('Dashboard/etapas', [
                'demandas' => $demandas,
           ]);
        }

        return redirect('/dashboard');

    }

    public function delteStage1($id)
    {
        $demanda = Demanda::where('id', $id)->first();
        if($demanda){
            $demanda->delete();
            return back()->with('success', 'Job removido.'); 
        }

        return back()->with('error', 'Esse job não pode ser removido.'); 

    }

}
