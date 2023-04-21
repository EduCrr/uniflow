<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\PasswordReset;
use App\Models\Estado;
use App\Models\Cidade;
use App\Models\InformacaoUsuario;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
class UsuariosController extends Controller
{
    public function index(Request $request){
        $user = Auth::User();
        $loggedUser = User::where('excluido', null)->where('id', $user->id)->with(['usuariosAgencias' => function ($query) {
        $query->where('excluido', null);
        }])->with(['marcas' => function ($query) {
        $query->where('excluido', null);
        }])->with('estado')->first();
        
        $estados = Estado::all();
        $cidades = Cidade::select('id', 'nome', 'estado_id')->where('estado_id', $loggedUser['estado'][0]->id)->get();
        
        if($loggedUser){
            return view('perfil-usuario', [
                'user' => $loggedUser,
                'estados' => $estados,
                'cidades' => $cidades,
               
            ]);
        }else{
            return view('login');
        }
    }

    
    public function getCityByStates($id){
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
   

    public function edit(Request $request, $id){
        $imgs = $request->file('avatar');
       
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

            $user = User::where('id', $id)->first();
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

                return back()->with('success', 'Editado com sucesso.' );  

            }
        }
    
    }

    public function forgotPassword(){
        //fazer verificação

        $checkTokens = PasswordReset::all();
        $horaAtual = Carbon::now();

        foreach($checkTokens as $item){
            $created_at = new Carbon($item->created_at);
            if ($horaAtual->greaterThan($created_at->addHour())) {
                PasswordReset::where('email', $item->email)->delete();
            } 
        }

        return view('recuperar-senha');
    }

    //enviar para o email o link
    public function forgotPasswordAction(Request $request){
        
        $request->validate([
            'email' => 'required|email|exists:usuarios,email',
          ],[
            'email.*' => 'Esse e-mail não foi encontrado!',
        ]);
        
        $checkUserExists = User::where('email', $request->email)->where('excluido', null)->exists();

        if(!$checkUserExists){
            return back()->with('error', 'Esse e-mail é inválido para recuperar a senha.' );  
        }

        $checkDateToken = PasswordReset::where('email', $request->email)->first();

        if($checkDateToken){
            return back()->with('error', 'Esse e-mail já foi feita uma solicitação para a troca da senha, entre no seu e-mail de cadastro.' );  
        }else{

            $token = \Str::random(64);
            
            $newPasswordReset = new PasswordReset();
            $newPasswordReset->email = $request->email;
            $newPasswordReset->token = $token;
            $newPasswordReset->created_at = Carbon::now();
            $newPasswordReset->save();
            
            $userName = User::select('nome')->where('email', $request->email)->first();
            $actionLink = route('ShowResetForm', ['token' => $token, 'email' => $request->email]);
            
            Mail::send('email-forgot', ['action_link' => $actionLink, 'nome' => $userName->nome], function($message) use ($request) {
                $message->from('envios@fmfm.com.br')
                ->to($request->email)
                ->bcc('agenciacriareof@gmail.com')
                ->subject('Redefinir senha');
                
                // $message->from('dudu1.6@hotmail.com');
                // $message->to($request->email)->subject('Redefinir senha');
            });
    
            return back()->with('success', 'Email foi enviado com sucesso.' ); 
        }
        
    }

    public function showResetForm(Request $request, $token = null){
        
        //fazer verificação se o token ainda existe
        $checkDateToken = PasswordReset::where('email', $request->email)->where('token', $request->token)->first();

        if($checkDateToken){
            $horaAtual = \Carbon\Carbon::now();
            $dataDoBanco = Carbon::parse($checkDateToken->created_at)->timezone('America/Sao_Paulo');

            if ($horaAtual->greaterThan($dataDoBanco->addHour())) {
                PasswordReset::where('email', $request->email)->where('token', $request->token)->delete();
                return redirect()->route('forgotPassword')->with('error', 'Token foi expirado, tente novamente' );  
            } else {
                return view('resetar-senha', [
                    'token' => $token,
                    'email' => $request->email,
                ]);
            }
        }else{
            return redirect()->route('forgotPassword')->with('error', 'Token foi expirado, tente novamente' );  

        }
    }

    public function resetpassword(Request $request){

        $request->validate([
            'email' => 'email|exists:usuarios,email',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required',
          ],[
            'email.*' => 'Esse e-mail não foi encontrado!',
            'password.required' => 'Preencha o campo senha.',
            'password.min' => 'A senha deve ter pelo menos 3 caracteres.',
            'password.confirmed' => 'As senhas devem ser iguais.',
        ]);

        $checkToken = PasswordReset::where('email', $request->email)->where('token', $request->token)->first();
        if($checkToken){
            $newPasswordUser = User::where('email', $request->email)->first();
            if($newPasswordUser){
                if($request->password && $request->password_confirmation){
                    if($request->password === $request->password_confirmation){
                        $newPassword = Hash::make($request->password);
                        $newPasswordUser->password = $newPassword;
                        $newPasswordUser->save();
                        PasswordReset::where('email', $request->email)->where('token', $request->token)->delete();
                        return redirect()->route('login')->with('success', 'Sua nova senha foi criada com sucesso!' );  
                    }
                }
            }else{
               return back()->with('error', 'Algo de inesperado aconteceu, tente novamente mais tarde' );  
            }
        }else{
            return redirect()->route('forgotPassword')->with('error', 'Token foi expirado, tente novamente' );  
        }


    }
}
