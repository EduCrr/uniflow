<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demanda;
use App\Models\User;
use App\Models\Agencia;
use App\Models\Marca;
use App\Models\InformacaoUsuario;
use App\Models\Estado;
use App\Models\Cidade;
use App\Models\MarcaUsuario;
use App\Models\AgenciaUsuario;
use App\Models\AgenciaColaborador;
use App\Models\UsuarioLog;
use Carbon\Carbon;
use App\Models\LinhaTempo;
use App\Models\DemandaTempo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DemandasExport;
use App\Exports\DemandasExportJobs;
use App\Exports\DemandasExportPrazos;
use App\Models\DemandaMarca;
use App\Models\AdminAgencia;

class AdminDemandasController extends Controller
{   
    public function index(Request $request){
        $user = Auth::User();
        
        $demandas = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with(['agencia' => function ($query) {
            $query->where('excluido', null);
        }])->with(['demandasReabertas' => function ($query) {
            $query->where('finalizado', null);
            $query->where('excluido', null);
        }])->orderBy('id', 'DESC')->paginate(15);

        foreach($demandas as $key => $item){
            $demandasReabertas = $item->demandasReabertas;
            if ($demandasReabertas->count() > 0) {
                $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                $item->final = $sugerido;
            }
           
        }
       
        $emPautaCount = Demanda::where('em_pauta', '1')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->count();
        $finalizadosCount =  Demanda::where('finalizada', '1')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->count();
        $pendentesCount = Demanda::where('finalizada', '0')->where('em_pauta', '0')->where('entregue', '0')->where('em_alteracao', '0')->where('finalizada', '0')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->count();
        
        $currentYear = date('Y');
        $logsCountByMonth = [];

        for ($month = 1; $month <= 12; $month++) {
            $logsCountByMonth[$month] = UsuarioLog::whereYear('criado', $currentYear)->whereMonth('criado', $month)->count();
        }
        
        $jobsPerMonths = [];

        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        for ($i = 1; $i <= 12; $i++) {
            $month = $meses[$i - 1];
            $jobsCriados = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->whereYear('criado', Carbon::now()->year)->whereMonth('criado', $i)->count();

            $jobsPerMonths[] = [
                'month' => $month,
                'jobs' => $jobsCriados,
            ];
        }

        return view('Admin/index', [
            'demandas' => $demandas,
            'emPautaCount' => $emPautaCount,
            'finalizadosCount' => $finalizadosCount,
            'pendentesCount' => $pendentesCount,
            'logsCountByMonth' => $logsCountByMonth,
            'jobsPerMonths' => $jobsPerMonths
        ]);
    }
    
    public function findOne($id){
    
        $demanda = Demanda::where('id', $id)->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->with('demandasReabertas')->with('imagens')->with('criador')->with('prazosDaPauta.agencia')->with(['marcas' => function ($query) {
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

            $demandaAtrasadas = DemandaTempo::where('demanda_id', $id)->where('atrasada', 1)->where('finalizado', '!=', null)->count();
            $demandaEmPrazo = DemandaTempo::where('demanda_id', $id)->where('atrasada', 0)->where('finalizado', '!=', null)->count();

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
            return view('Admin/job', [
                'demanda' => $demanda,
                'user' => $user,
                'lineTime' => $lineTime,
                'demandaAtrasadas' => $demandaAtrasadas,
                'demandaEmPrazo' => $demandaEmPrazo
            ]);

        }else{
            return redirect('/login')->with('warning', 'Esse job não está disponível.' );
        }
        
    }

    public function jobs(Request $request){
        $user = Auth::User();

        $search = $request->search;
        $aprovada = $request->aprovada;
        $inTime = $request->in_tyme;
        $priority = $request->category_id;
        $marca = $request->marca_id;
        $endDate = $request->endDateInput;
        $agencia = $request->agencia_id;
        $dateRange = $request->dateRange;

        $demandas = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->with(['marcas' => function ($query) {
            $query->where('excluido', null);
            }])->with(['agencia' => function ($query) {
            $query->where('excluido', null);
            }])->with(['demandasReabertas' => function ($query) {
                $query->where('finalizado', null);
                $query->where('excluido', null);
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

        $demandas = $demandas->paginate(15)->withQueryString();

        foreach($demandas as $key => $demanda){
            $demandasReabertas = $demanda->demandasReabertas;
            if ($demandasReabertas->count() > 0) {
                $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                $demanda->final = $sugerido;
            }
        }

        $brands = Marca::where('excluido', null)->get();

        $agencies = Agencia::where('excluido', null)->get();

        return view('Admin/jobs', [
            'demandas' => $demandas,
            'search' => $search,
            'inTime' => $inTime,
            'priority' => $priority,
            'aprovada' => $aprovada,
            'brands' => $brands,
            'marca' => $marca,
            'agencies' => $agencies,
            'endDate' => $endDate,
            'agencia' => $agencia,
            'dateRange' => $dateRange
        ]);
            
    }
   
    public function agency(){
        return view('Admin/Agencia/adicionar', [
        ]);
    }

    public function agencyCreate(Request $request){
       
        $validator = Validator::make($request->all(),[
            'nome' => 'required|min:3',
            'logo' => 'mimes:jpg,jpeg,png,bmp'
            
            ],[
                'nome.required' => 'Preencha o campo nome.',
                'logo.mimes' => 'Somente imagens jpeg, jpg, png e bmp são permitidas',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            $createAgency = new Agencia();
            $createAgency->nome = $request->nome;
            $createAgency->criado = date('Y-m-d H:i:s');
           
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $extension = $request->file('logo')->extension();
               
                $dest = public_path('assets/images/agency');
                $photoName = md5(time().rand(0,9999)).'.'.$extension;
        
                $img = Image::make($logo->getRealPath());
                $img->fit(128, 128)->save($dest.'/'.$photoName);

                $createAgency->logo = $photoName;
                
            }

            $createAgency->save();

            return back()->with('success', 'Agência criada com sucesso.' );  

        }

    }

    public function brand(){
      
        return view('Admin/Marca/adicionar', [
                       
        ]);
    }


    public function brandCreate(Request $request){
     
        $validator = Validator::make($request->all(),[
            'nome' => 'required|min:3',
            'logo' => 'mimes:jpg,jpeg,png,bmp'
            ],[
                'nome.required' => 'Preencha o campo nome.',
                'logo.mimes' => 'Somente imagens jpeg, jpg, png e bmp são permitidas',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            $createBrand = new Marca();
            $createBrand->nome = $request->nome;
            $createBrand->criado = date('Y-m-d H:i:s');
            $createBrand->cor = $request->cor;
           
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $extension = $request->file('logo')->extension();
               
                $dest = public_path('assets/images/brands');
                $photoName = md5(time().rand(0,9999)).'.'.$extension;
        
                $img = Image::make($logo->getRealPath());
                $img->save($dest.'/'.$photoName);

                $createBrand->logo = $photoName;
                
            }

            $createBrand->save();

            return back()->with('success', 'Marca criada com sucesso.' );  

        }

    }

    public function getCityByStatesAdmin($id){
        if($id){
            $empData['data'] = Cidade::orderby("nome","asc")
               ->select('id','nome')
               ->where('estado_id',$id)
               ->get();
           
           return response()->json($empData);
       }else{
           return false;
       }
    }

    public function user(){
        $agencias = Agencia::where('excluido', null)->get();
        $marcas = Marca::where('excluido', null)->get();
        $estados = Estado::all();
        return view('Admin/Usuario/adicionar', [
            'agencias' => $agencias,
            'marcas' => $marcas,
            'estados' => $estados    
        ]);
    }

    public function userCreate(Request $request){
        $validator = Validator::make($request->all(),[
            'nome' => 'required',
            'email' => 'required|email|unique:usuarios',
            'tipo' => 'required',
            'password' => 'nullable|min:3|confirmed',
            'password_confirmation' => 'nullable|min:3',
            'estado_id' => 'required',
            'cidade_id' => 'required',
            
            ],[
                'nome.required' => 'Preencha o campo nome.',
                'email.required' => 'Preencha o campo email.',
                'email.unique' => 'Este endereço de e-mail já está sendo usado.',
                'tipo.required' => 'Preencha o campo tipo.',
                'password.min' => 'A senha deve ter pelo menos 3 caracteres.',
                'password_confirmation.min' => 'As senhas devem ser iguais.',
                'password.confirmed' => 'As senhas devem ser iguais.',
                'agencia.required' => 'Preencha o campo agência.',
                'estado_id.required' => 'Preencha o campo estado.',
                'cidade_id.required' => 'Preencha o campo cidade.',

            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){

            $createUser = new User();
            $createUser->nome = $request->nome;
            $createUser->email = $request->email;
            $createUser->tipo = $request->tipo;
            $createUser->criado = date('Y-m-d H:i:s');

            if($request->password && $request->password_confirmation){
                if($request->password === $request->password_confirmation){
                    $newPassword = Hash::make($request->password);
                    $createUser->password = $newPassword;
                }
            }
           
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $extension = $request->file('logo')->extension();
               
                $dest = public_path('assets/images/users');
                $photoName = md5(time().rand(0,9999)).'.'.$extension;
        
                $img = Image::make($logo->getRealPath());
                $img->fit(128, 128)->save($dest.'/'.$photoName);
                $createUser->avatar = $photoName;
                
            }

            $createUser->save();

            $createInfoUser = new InformacaoUsuario();
            $createInfoUser->usuario_id = $createUser->id;
            $createInfoUser->cidade_id = $request->cidade_id;
            $createInfoUser->estado_id = $request->estado_id;
            $createInfoUser->save();

            if($request->tipo === 'colaborador'){
                foreach($request->marcas as $item){
                    $brandsUser = new MarcaUsuario();
                    $brandsUser->marca_id = $item;
                    $brandsUser->usuario_id = $createUser->id;
                    $brandsUser->save();
                }

                foreach($request->agencias_colaboradores as $item){
                    $agencyColaborador = new AgenciaColaborador();
                    $agencyColaborador->agencia_id = $item;
                    $agencyColaborador->usuario_id = $createUser->id;
                    $agencyColaborador->save();
                }

            }else if($request->tipo === 'agencia'){
                $agencyUser = new AgenciaUsuario();
                $agencyUser->usuario_id = $createUser->id;
                $agencyUser->agencia_id = $request->agencia_id;
                $agencyUser->save();
                
                if($request->adminAg == true){
                    $adminAg = new AdminAgencia();
                    $adminAg->usuario_id =  $createUser->id;
                    $adminAg->save();
                }

            }

            return back()->with('success', 'Usuário criado com sucesso.' );  

        }

    }

    public function agencysAll(Request $request){
        $search = $request->search;

        $agencias = Agencia::where('excluido', null);

        if($search){
            $agencias->where('nome', 'like', "%$search%");
        }
        
        $agencias = $agencias->paginate(25)->withQueryString();
        return view('Admin/Agencia/index', [
            'agencias' => $agencias,
            'search' => $search,  
        ]);
    }

    public function brandsAll(Request $request){
        $search = $request->search;

        $marcas = Marca::where('excluido', null);

        if($search){
            $marcas->where('nome', 'like', "%$search%");
        }
        
        $marcas = $marcas->paginate(25)->withQueryString();


        return view('Admin/Marca/index', [
            'marcas' => $marcas,
            'search' => $search,  
        ]);
    }

    public function usersAll(Request $request){
        $search = $request->search;
        
        $usuarios = User::where('excluido', null);
        
        if($search){
            $usuarios->where('nome', 'like', "%$search%");
        }
        
        $usuarios = $usuarios->paginate(25)->withQueryString();

        return view('Admin/Usuario/index', [
            'usuarios' => $usuarios,
            'search' => $search,  
        ]);
    }
    
    public function agencyEdit($id){
        
        $agencia = Agencia::where('id', $id)->where('excluido', null)->first();
      
        return view('Admin/Agencia/agencia', [
            'agencia' => $agencia        
        ]);
    }

    public function brandEdit($id){
        $marca = Marca::where('id', $id)->where('excluido', null)->first();
      
        return view('Admin/Marca/marca', [
            'marca' => $marca        
        ]);
    }
    
    public function userEdit($id){
        $usuario = User::where('id', $id)->where('excluido', null)->with('marcas')->with('usuariosAgencias')->with('colaboradoresAgencias')->withCount(['adminUserAgencia as count_userAg' => function ($query) {
            $query->where('excluido', null);
        }])->first();
        $idsBrands = [];
        $idsAgencys  = [];

        foreach($usuario['marcas'] as $marca){
            array_push($idsBrands, $marca->id);
        }
        foreach($usuario['colaboradoresAgencias'] as $ag){
            array_push($idsAgencys, $ag->id);
        }

        $cidades = Cidade::select('id', 'nome', 'estado_id')->where('estado_id', $usuario['estado'][0]->id)->get();
        $estados = Estado::all();
        $agencias = Agencia::where('excluido', null)->get();
        $marcas = Marca::where('excluido', null)->get();
        return view('Admin/Usuario/usuario', [
            'user' => $usuario,
            'estados' => $estados,
            'cidades' => $cidades,
            'agencias' => $agencias,
            'marcas' => $marcas,
            'idsBrands' => $idsBrands,
            'idsAgencys' => $idsAgencys       
        ]);
    }

    public function agencyEditAction(Request $request, $id){
        
        $validator = Validator::make($request->all(),[
            'nome' => 'required|min:3',
            'logo' => 'mimes:jpg,jpeg,png,bmp'
            
            ],[
                'nome.required' => 'Preencha o campo nome.',
                'logo.mimes' => 'Somente imagens jpeg, jpg, png e bmp são permitidas',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            
            $agencia = Agencia::where('id', $id)->where('excluido', null)->first();

            if($id){

                if($request->nome){
                    $agencia->nome = $request->nome;
                }

                if ($request->hasFile('logo')) {
                    File::delete(public_path("/assets/images/agency/".$agencia->logo));
                    $dest = public_path('assets/images/agency');
                    $extension = $request->file('logo')->extension();
                    $photoName = md5(time().rand(0,9999)).'.'.$extension;
            
                    $img = Image::make($request->logo->getRealPath());
                    $img->fit(128, 128)->save($dest.'/'.$photoName);
                    $agencia->logo = $photoName;
                }

                $agencia->save();
            }else{
                 return back()->with('error', 'Agência não foi encontrada.' );  
            }

             return back()->with('success', 'Agência editada com sucesso.' );  

        }
    }

    public function brandEditAction(Request $request, $id){
         $validator = Validator::make($request->all(),[
            'nome' => 'required|min:3',
            'cor' => 'required',
            'logo' => 'mimes:jpg,jpeg,png,bmp'
            
            ],[
                'nome.required' => 'Preencha o campo nome.',
                'cor.required' => 'Preencha o campo cor.',
                'logo.mimes' => 'Somente imagens jpeg, jpg, png e bmp são permitidas',
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }

        if(!$validator->fails()){
            
            $marca = Marca::where('id', $id)->where('excluido', null)->first();

            if($id){

                if($request->nome){
                    $marca->nome = $request->nome;
                }

                if($request->cor){
                    $marca->cor = $request->cor;
                }

                if ($request->hasFile('logo')) {
                    File::delete(public_path("/assets/images/brands/".$marca->logo));
                    $dest = public_path('assets/images/brands');
                    $extension = $request->file('logo')->extension();
                    $photoName = md5(time().rand(0,9999)).'.'.$extension;
            
                    $img = Image::make($request->logo->getRealPath());
                    $img->save($dest.'/'.$photoName);
                    $marca->logo = $photoName;
                }

                $marca->save();

            }else{
                 return back()->with('error', 'Essa marca não foi encontrada.' );  
            }

             return back()->with('success', 'Marca editada com sucesso.' );  

        }
    }

    public function userEditAction(Request $request, $id){
        $user = User::where('id', $id)->where('excluido', null)->first();
        $errorDemanda = false;
        $verifyErrorMarca = '';
        $verifyErrorAg = '';
        $validator = Validator::make($request->all(),[
            'nome' => 'required|min:3',
            'password' => 'nullable|min:3|confirmed',
            'password_confirmation' => 'nullable|min:3',
            'estado_id' => 'required',
            'cidade_id' => 'required',
            'avatar' => 'mimes:jpg,jpeg,png',
           
            ],[
                'nome.required' => 'Preencha o campo nome.',
                'nome.min' => 'O campo nome deve ter pelo menos 3 caracteres.',
                'password.min' => 'A senha deve ter pelo menos 3 caracteres.',
                'password_confirmation.min' => 'As senhas devem ser iguais.',
                'password.confirmed' => 'As senhas devem ser iguais.',
                'cidade_id.required' => 'Preencha o campo cidade.',
                'avatar.mimes' => 'Somente imagens jpeg, jpg e png são permitidas.',
                'estado_id.required' => 'Preencha o campo estado.',
                
               
            ]
        );

        if($validator->fails()) {
            return back()->with('error', $validator->messages()->all()[0])->withInput();
        }else{

          
            $infoUser = InformacaoUsuario::where('usuario_id', $user->id)->first();

            if($user){
                if($request->nome){
                    $user->nome = $request->nome;
                    $user->save();
                }

                if($request->password && $request->password_confirmation){
                    if($request->password === $request->password_confirmation){
                        $newPassword = Hash::make($request->password);
                        $user->password = $newPassword;
                        $user->save();
                    }
                }

                if($request->estado_id){
                    $infoUser->estado_id = $request->estado_id;
                    $infoUser->save();
                }

                if($request->cidade_id){
                    $infoUser->cidade_id = $request->cidade_id;
                    $infoUser->save();
                }
                
                if ($request->hasFile('avatar')) {
                    $avatar = $request->file('avatar');
                    $extension = $request->file('avatar')->extension();
                    if($user->avatar !== 'default.jpg'){
                        File::delete(public_path("/assets/images/users/".$user->avatar));
                    }
                    $dest = public_path('assets/images/users');
                    $photoName = md5(time().rand(0,9999)).'.'.$extension;
            
                    $img = Image::make($avatar->getRealPath());
                    $img->fit(128, 128)->save($dest.'/'.$photoName);

                    $user->avatar = $photoName;
                    $user->save();
                    
                }

                if($user->tipo === 'colaborador'){

                    //verificacao e jobs
                   
                    if($request->marcas){
                        $verifyErrorMarca = $this->helpUserAdminAge($user->id, $request->marcas);
                    }

                    if($request->agencias_colaboradores){
                        $colaboradorAgencia = AgenciaColaborador::select('agencia_id')->where('usuario_id', $user->id)->get();
                                        
                        foreach($colaboradorAgencia as $item){
                            // Verifica se há demandas associadas à agência
                            $hasDemandas = Demanda::where('criador_id', $user->id)->where('excluido', null)->where('agencia_id', $item->agencia_id)->exists();
                                            
                            // Verifica se a agência não está presente em $request->agencias_colaboradores e também não está na cláusula whereNotIn em agencia_id
                            if (!$hasDemandas && !in_array($item->agencia_id, $request->agencias_colaboradores)) {
                                // Remove a agência não presente em $request->agencias_colaboradores
                                $agencia = AgenciaColaborador::where('usuario_id', $user->id)->where('agencia_id', $item->agencia_id)->delete();
                            } else if ($hasDemandas && !in_array($item->agencia_id, $request->agencias_colaboradores)) {
                                $errorDemanda = true; // Marca a ocorrência do erro
                            }
                        }

                        foreach($request->agencias_colaboradores as $ag){
                            $brandsUser = AgenciaColaborador::updateOrCreate([
                                'agencia_id' => $ag,
                                'usuario_id' => $user->id
                            ], [
                                'agencia_id' => $ag,
                                'usuario_id' => $user->id,
                            ]);
                        }
                    
                        if ($errorDemanda) {
                            $verifyErrorAg = 'error';
                        }
                    }

                }else if($user->tipo === 'agencia'){
                    $agencyUser = AgenciaUsuario::where('usuario_id', $user->id)->first();
                    $hasDemandas = Demanda::where('agencia_id', $agencyUser->agencia_id)->where('excluido', null)->exists();

                    //admin agencia fazendo verificação no input switch
                    $adminAg = AdminAgencia::where('usuario_id', $user->id)->first();
                    if($request->adminAg == false && $adminAg){
                        $adminAg->excluido = date('Y-m-d H:i:s');
                        $adminAg->save();
                    }else if($request->adminAg == true && $adminAg){
                        $adminAg->excluido = null;
                        $adminAg->save();
                    }else{
                        $newAdminAg = new AdminAgencia();
                        $newAdminAg->usuario_id = $user->id;
                        $newAdminAg->save();
                    }

                    if (!$hasDemandas) {
                        $agencyUser->agencia_id = $request->agencia;
                        $agencyUser->save();
                    }else if($hasDemandas && $request->agencia != $agencyUser->agencia_id ) {
                        $verifyErrorAg = 'error';
                    }

                    if($request->marcas){
                      $verifyErrorMarca = $this->helpUserAdminAge($user->id, $request->marcas);
                    }
                    
                }
             
                if ($verifyErrorAg === 'error' && $verifyErrorMarca === 'error') {
                    return back()->with('error-ambas', 'Existem erros nas informações fornecidas. (Marca e Agência)');
                } else if ($verifyErrorAg === 'error') {
                    return back()->with('error-ag', 'Você não pode mudar a agência, pois já existe um job cadastrado nessa marca');
                } else if ($verifyErrorMarca === 'error') {
                    return back()->with('error-ag-marca', 'Você não pode mudar de marca, pois já existe um job cadastrado nessa agência.');
                } else {
                    return back()->with('success', 'Editado com sucesso.' );  
                }

            }
        }
    }

    public function helpUserAdminAge($userId, $requestM){
        $marcasColaborador = MarcaUsuario::select('marca_id')->where('usuario_id', $userId)->get();
        $demandasByUser = Demanda::select('id')->where('criador_id', $userId)->get();
        $idsDemandas = [];
        $erroDemanda = false;

        foreach($demandasByUser as $d){
            array_push($idsDemandas, $d->id);
        }

        foreach($marcasColaborador as $item){
            // Verifica se há demandas associadas à agência
            $hasDemandas = DemandaMarca::where('marca_id', $item->marca_id)->whereIn('demanda_id', $idsDemandas)->exists();
        
            // Verifica se a agência não está presente em $request->agencias_colaboradores e também não está na cláusula whereNotIn em agencia_id
            if (!$hasDemandas && !in_array($item->marca_id, $requestM)) {
                // Remove a agência não presente em $request->agencias_colaboradores
                $marca = MarcaUsuario::where('usuario_id', $userId)->where('marca_id', $item->marca_id)->delete();
            } else if ($hasDemandas && !in_array($item->marca_id, $requestM)) {
                $erroDemanda = true; // Marca a ocorrência do erro
            }
        }

        foreach($requestM as $item){

            $brandsUser = MarcaUsuario::updateOrCreate([
            'marca_id' => $item,
            'usuario_id' => $userId
            ], [
                'marca_id' => $item,
                'usuario_id' => $userId
            ]);

        }

        if ($erroDemanda) {
           return 'error';
        }
       
    }
    
    //delete

    public function agencyDelete($id){
        $excAgency = Agencia::where('id', $id)->where('excluido', null)->first();
        if($excAgency){
            $excAgency->excluido = date('Y-m-d H:i:s');
            $excAgency->save();
            return back()->with('success', 'Agência excluida com sucesso.' );  
        }else{
            return back()->with('error', 'Esse agência não pode ser excluida.' );  
        }
    }

    public function brandDelete($id){
        $excBrand = Marca::where('id', $id)->where('excluido', null)->first();
        if($excBrand){
            $excBrand->excluido = date('Y-m-d H:i:s');
            $excBrand->save();
            return back()->with('success', 'Marca excluida com sucesso.' );  
        }else{
            return back()->with('error', 'Esse marca não pode ser excluida.' );  
        }
    }

    public function UserDelete($id){
        $excUser = User::where('id', $id)->where('excluido', null)->first();
        if($excUser){
            $excUser->excluido = date('Y-m-d H:i:s');
            $excUser->save();
            return back()->with('success', 'Usuário excluido com sucesso.' );  
        }else{
            return back()->with('error', 'Esse usuário não pode ser excluida.' );  
        }
    }

    public function agencysGraphs($id){

        $agencia = Agencia::where('id', $id)->where('excluido', null)->first();

        //média geral        
        $demandasTempos = DemandaTempo::where('agencia_id', $id)->where('finalizado', '!=', null)->get();
        $diferencasEmDias = [];

        foreach ($demandasTempos as $item) {
            $iniciado = Carbon::parse($item->iniciado);
            $finalizado = Carbon::parse($item->finalizado);
        
            // filtra os dias úteis (segunda a sexta)
            $diferencaEmDias = $finalizado->diffInDaysFiltered(function($date) {
                return !$date->isWeekend();
            }, $iniciado, true);
        
            if ($diferencaEmDias < 1) {
                $diferencaEmDias = 0.5;
            }
        
            // verifica se a demanda foi criada antes ou depois das 17h
            $iniciadoDepoisDas17h = $iniciado->gte($iniciado->copy()->setHour(17));
            if ($iniciadoDepoisDas17h) {
                // se foi criada depois das 17h, não conta o dia atual
                if ($finalizado->lt(Carbon::today())) {
                    $diferencaEmDias--;
                }
            } 
            $diferencasEmDias[] = $diferencaEmDias;
            $item->diferencaEmDias = $diferencaEmDias;
        }
        
        $media = count($diferencasEmDias) > 0 ? array_sum($diferencasEmDias) / count($diferencasEmDias) : 0;
        // $media = round($media, 0); // arredonda para o número inteiro mais próximo
        // $media = floor($media); // arredonda para baixo
        $media = number_format($media, 1); // formata o número com uma casa decimal
        if($media < 1){
            $media = number_format($media, 1); // formata o número com uma casa decimal
         }else{
          $media = round($media, 0); // arredonda para o número inteiro mais próximo
          $media = floor($media); // arredonda para baixo
         }
        //média em meses
        $currentYear = date('Y');
        $daysCountByMonth = [];

        for ($month = 1; $month <= 12; $month++) {
            $daysCountByMonth[$month] = DemandaTempo::select('criado', 'iniciado', 'finalizado')->where('agencia_id', $id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('finalizado', '!=', null)->get();
            $demandasCriadas[$month] = Demanda::select('id', 'criado', 'finalizada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->count();
            $demandasFinalizadas[$month] = Demanda::select('id', 'criado', 'finalizada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('finalizada', 1)->count();
            $demandasPrazo[$month] = Demanda::select('id', 'atrasada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('atrasada', 0)->where('finalizada', 1)->count();
            $demandasAtrasada[$month] = Demanda::select('id', 'atrasada')->where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)->whereYear('criado', $currentYear)->whereMonth('criado', $month)->where('atrasada', 1)->where('finalizada', 1)->count();
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

        //demandas infos
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
        //juntar criadas e finalizadas
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

        //demandas
        $demandas = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)
        ->whereHas('agencia', function ($query) {
            $query->where('excluido', null);
        })->with(['demandasReabertas' => function ($query) {
            $query->where('finalizado', null);
            $query->where('excluido', null);
        }])->with(['marcas' => function ($query) {
            $query->where('excluido', null);
        }])->orderBy('id', 'DESC')->paginate(25);
        //s
        foreach($demandas as $key => $item){
            $demandasReabertas = $item->demandasReabertas;
            if ($demandasReabertas->count() > 0) {
                $sugerido = $demandasReabertas->sortByDesc('id')->first()->sugerido;
                $item->final = $sugerido;
            }
           
        }

        //counts
        $demandasCount = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)->where('finalizada', 1)->whereYear('criado', $currentYear)->count();
        $demandasAtrasadasCount = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)->where('atrasada', 1)->whereYear('criado', $currentYear)->where('finalizada', 1)->count();
        $demandasEmPrazoCount = Demanda::where('excluido', null)->where('etapa_1', 1)->where('etapa_2', 1)->where('agencia_id', $id)->where('atrasada', 0)->whereYear('criado', $currentYear)->where('finalizada', 1)->count();

        //demandas Atrasadas e no prazo
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

        return view('Admin/Agencia/graficos', [
          'agencia' => $agencia,
          'media' => $media,
          'mediaMeses' => $mediaMeses,
          'resultadosDemanda' => $resultadosDemanda,
          'demandas' => $demandas,
          'demandasCount' => $demandasCount,
          'resultadosDemandaPrazos' => $resultadosDemandaPrazos,
          'demandasAtrasadasCount' => $demandasAtrasadasCount,
          'demandasEmPrazoCount' => $demandasEmPrazoCount
        ]);
    }
   
    public function exportDays($id)
    {   
        //Média de dias por mês

        $agencia = Agencia::where('id', $id)->where('excluido', null)->first();

        return Excel::download(new DemandasExport($agencia->id), $agencia->nome.'-media-de-dias.xlsx');
    
    }  

    public function exportPrazos($id)
    {   
        //Média de dias por mês

        $agencia = Agencia::where('id', $id)->where('excluido', null)->first();

        return Excel::download(new DemandasExportPrazos($agencia->id), $agencia->nome.'-atrasadas-em-prazo.xlsx');
    
    }  
    
    public function exportJobs($id)
    {
        //Jobs criados/finalizados por mês

        $agencia = Agencia::where('id', $id)->where('excluido', null)->first();

        return Excel::download(new DemandasExportJobs($agencia->id), $agencia->nome.'-demandas-criadas-finalizadas.xlsx');
    
    } 

    public function stages(){
        $demandas = Demanda::where('etapa_1', 1)->where('etapa_2', 0)->where('excluido', null)->with(['agencia' => function ($query) {
            $query->where('excluido', null);
        }])->get();

        if($demandas){
            return view('Admin/etapas', [
                'demandas' => $demandas,
           ]);
        }

        return redirect('/admin');

    }
}   