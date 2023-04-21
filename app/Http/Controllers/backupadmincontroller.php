<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demanda;
use App\Models\User;
use App\Models\Setor;
use App\Models\SetorUsuario;
use App\Models\DemandaImagem;
use App\Models\DemandaUsuario;
use App\Models\DemandaSetor;
use App\Models\Marca;
use App\Models\Notificacao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
Use Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminDemandasController extends Controller
{   
    public function index(Request $request){
        $user = Auth::User();
        $demandas = Demanda::where('criador_id', $user->id)->with('marca')->take(8)->get();
        return view('Admin/index', [
            'demandas' => $demandas
        ]);
    }
    
    public function pautas(Request $request){
        $user = Auth::User();

        $search = $request->search;
        $aprovada = $request->aprovada;
        $priority = $request->category_id;
        $marca = $request->marca_id;
        $date = $request->dateInput;
        $month =  substr($request->monthInput, -2);  
        $fullMoth = $request->monthInput;
        $yearMoth =  substr($request->monthInput, 0, 4);

        $demandas = Demanda::where('criador_id', $user->id);


        if($search){
            $demandas->where('titulo', 'like', "%$search%");
        }

        if($month){
            $demandas->whereMonth('inicio', '=', $month);
        }

        if($aprovada){
            $demandas->where('aprovada', $aprovada);
        }

        if($yearMoth){
            $demandas->whereYear('inicio', '=', $yearMoth);
        }

        if($date){
            $demandas->whereDate('inicio', $date);
        }

        if($priority){
            $demandas->where('prioridade', $priority);
        }

        if($marca != 0){
            $demandas->where('marca_id', $marca);
        }

        $demandas = $demandas->paginate(25)->withQueryString();

        foreach($demandas as $key => $item){
            $demandas->inicio = $item->inicio = Carbon::createFromFormat('Y-m-d H:i:s', $item->inicio)->format('d/m/Y');
            $demandas->final = $item->final = Carbon::createFromFormat('Y-m-d H:i:s', $item->final)->format('d/m/Y');
            // $demandas[$key]['usuarios'] = $item->usuarios()->get();
            // $demandas[$key]['criador'] = User::where('id', $item->criador_id)->first();
            $demandas[$key]['marca'] = $item->marca()->first();

        }

        // foreach($demandas as $key => $item){
        //     $demandas[$key]['slug'] = Marca::select('slug')->where('id', $item->marca_id)->first();
        // }

        $brands = Marca::all();

        return view('Admin/pautas', [
            'demandas' => $demandas,
            'search' => $search,
            'month' => $fullMoth,
            'date' => $date,
            'priority' => $priority,
            'aprovada' => $aprovada,
            'brands' => $brands,
            'marca' => $marca
        ]);
            
    }

    public function create(Request $request){
        $user = Auth::User();

        $marcas = Marca::select('id', 'nome')->get();
        return view('Admin/criar', [
            'marcas' => $marcas
        ]);
        
    }

    public function createAction(Request $request){
        $user = Auth::User();

        $validator = Validator::make($request->all(),[
                'titulo' => 'required|min:3',
                'marca' => 'required',
                'inicio' => 'required',
                'final' => 'required',
                'setores' => 'required',
                'prioridade' => 'required',
                'briefing' => 'required|min:3'
                ],[
                    'titulo.required' => 'Preencha o campo título.',
                    'titulo.min' => 'O campo título deve ter pelo menos 3 caracteres.',
                    'marca.required' => 'Preencha o campo marca.',
                    'inicio.required' => 'Preencha o campo data inicial.',
                    'final.required' => 'Preencha o campo data final.',
                    'setores.required' => 'Preencha o campo setor.',
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
                $cor = '#44a2d2';
            }else if($request->prioridade == '5'){
                $cor = '#f9bc0b';
            }else if($request->prioridade == '10'){
                $cor = '#fb3232';
            }

            $newJob = new Demanda();
            $newJob->titulo = $request->titulo;
            $newJob->briefing = $request->briefing;
            $newJob->criador_id = $user->id;
            $newJob->marca_id = $request->marca;
            $newJob->inicio = $request->inicio;
            $newJob->final = $request->final;
            $newJob->prioridade = $request->prioridade;
            $newJob->cor = $cor;
            $newJob->save();

            if ($request->hasFile('arquivos')) {
                $arqs = $request->file('arquivos');
                foreach($arqs as $item){
                    $extension = $item->extension();
                    $photoName = md5(time().rand(0,9999)).'.'.$extension;
                    $destImg = public_path('assets/images/files');
                    $item->move($destImg, $photoName);
        
                    $newPostPhoto = new DemandaImagem();
                    $newPostPhoto->demanda_id =  $newJob->id;
                    $newPostPhoto->imagem = $photoName;
                    $newPostPhoto->criado = date('Y-m-d H:i:s');
                    $newPostPhoto->save();
                }
            }


            $setoresIds = $request->setores;

            $setoresUsuarios = SetorUsuario::whereIn('setor_id', $setoresIds)->get();
           
            //salvando usuario_id e demanda_id
            
            foreach($setoresUsuarios as $item){
                $demanadaUsuario = new DemandaUsuario();
                $demanadaUsuario->usuario_id = $item->usuario_id;
                $demanadaUsuario->demanda_id = $newJob->id;
                $demanadaUsuario->save();
            }

            //setores
           
            //demanda_setores
            foreach($setoresIds as $item){
                $demandaSetores = new DemandaSetor();
                $demandaSetores->setor_id = $item;
                $demandaSetores->demanda_id = $newJob->id;
                $demandaSetores->save();
            }

            // //notificações
            // //user logado
            
            // $previewContent = substr($newJob->briefing, 0, 150).'...'; 
            // $notificacaoCriado = new Notificacao();
            // $notificacaoCriado->usuario_id = $user->id;
            // $notificacaoCriado->demanda_id = $newJob->id;
            // $notificacaoCriado->marca_id = $newJob->marca_id;
            // $notificacaoCriado->conteudo = $previewContent;
            // $notificacaoCriado->tipo = 'criado';
            // $notificacaoCriado->criado = date('Y-m-d H:i:s');
            // $notificacaoCriado->save();

            return back()->with('success', 'Pauta adicionada' );  

        }

    }

    public function edit($id){
        $user = Auth::User();
        $demanda = Demanda::where('id', $id)->where('criador_id', $user->id)->with('imagens')->with('setores')->first();
        $marcas = Marca::select('id', 'nome')->get();
        $setores = Setor::where('marca_id', $demanda->marca_id)->get();

        $setoresIds = array();
        foreach($demanda['setores'] as $setor){
            array_push($setoresIds, $setor->id);
        }

        if($demanda){
            return view('Admin/editar', [
                'demanda' => $demanda,
                'marcas' => $marcas,
                'setores' => $setores,
                'setoresIds' => $setoresIds
            ]);
        }
        return redirect('/admin/pautas');
       
    }

    public function editAction(Request $request, $id){

        $validator = Validator::make($request->all(),[
            'titulo' => 'required|min:3',
            'marca' => 'required',
            'inicio' => 'required',
            'final' => 'required',
            'setores' => 'required',
            'prioridade' => 'required',
            'briefing' => 'required|min:3'
            ],[
                'titulo.required' => 'Preencha o campo título.',
                'titulo.min' => 'O campo título deve ter pelo menos 3 caracteres.',
                'marca.required' => 'Preencha o campo marca.',
                'inicio.required' => 'Preencha o campo data inicial.',
                'final.required' => 'Preencha o campo data final.',
                'setores.required' => 'Preencha o campo setor.',
                'prioridade.required' => 'Preencha o campo prioridade.',
                'briefing.required' => 'Preencha o campo briefing.',
                'briefing.min' => 'O campo briefing deve ter pelo menos 3 caracteres.',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            
            $demanda = Demanda::find($id);

            if($request->titulo){
                $demanda->titulo = $request->titulo;
            }

            if($request->marca){
                $demanda->marca_id = $request->marca;
            }

            if($request->inicio){
                $demanda->inicio = $request->inicio;
            }

            if($request->final){
                $demanda->final = $request->final;
            }

            if($request->setores){

                $setoresIds = $request->setores;

                $setoresUsuarios = SetorUsuario::whereIn('setor_id', $setoresIds)->get();
             
                //salvando usuario_id e demanda_id
                
                $deleteDemandaUsuario = DemandaUsuario::where('demanda_id', $demanda->id)->delete();

                foreach($setoresUsuarios as $item){
                    $demanadaUsuario = new DemandaUsuario();
                    $demanadaUsuario->usuario_id = $item->usuario_id;
                    $demanadaUsuario->demanda_id = $demanda->id;
                    $demanadaUsuario->save();
                }

                //setores
            
                //demanda_setores
                
                $deleteDemandaSetor = DemandaSetor::where('demanda_id', $demanda->id)->delete();

                foreach($setoresIds as $item){
                    $demandaSetores = new DemandaSetor();
                    $demandaSetores->setor_id = $item;
                    $demandaSetores->demanda_id = $demanda->id;
                    $demandaSetores->save();
                }
            }

            if ($request->hasFile('arquivos')) {
                $arqs = $request->file('arquivos');
                foreach($arqs as $item){
                    $extension = $item->extension();
                    $photoName = md5(time().rand(0,9999)).'.'.$extension;
                    $destImg = public_path('assets/images/files');
                    $item->move($destImg, $photoName);
        
                    $newPostPhoto = new DemandaImagem();
                    $newPostPhoto->demanda_id =  $demanda->id;
                    $newPostPhoto->imagem = $photoName;
                    $newPostPhoto->criado = date('Y-m-d H:i:s');
                    $newPostPhoto->save();
                }
            }
            
            if($request->briefing){
                $demanda->briefing = $request->briefing;
            }

            $demanda->save();

            return back()->with('success', 'Job editado' );  

        }
    }

    public function getSetores($id){
        if($id){
             $empData['data'] = Setor::orderby("nome","asc")
                ->select('id','nome')
                ->where('marca_id',$id)
                ->get();
            
            return response()->json($empData);
        }else{
            return false;
        }
    }

    public function delete(Request $request, $id){
        $demanda = Demanda::find($id);
        
        if($demanda){
            
            $arqs = DemandaImagem::where('demanda_id', $id)->get();

            foreach($arqs as $item){
                File::delete(public_path("assets/images/files/".$item["imagem"]));
                $item->delete();
            }

            $demanda->delete();

            return back()->with('success', 'Job excluido com sucesso.' );  
        }
        return back();  
    }

}
