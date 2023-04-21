<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsuarioLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
  public function login(Request $request){
    if(Auth::check()){
      $user = Auth::User();
      if($user->tipo === 'admin'){
      return redirect('/admin');
      }
      else if($user->tipo === 'colaborador'){
        return redirect('/dashboard');
      }
      else if($user->tipo === 'agencia'){
        return redirect('/');
      }
    }else{
      return view('login');
    }
  }

  public function login_action(Request $request)
  {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'Preencha o campo email.',
        'password.required' => 'Preencha o campo senha.',
    ]);

    if (!Auth::attempt($credentials)) {
        return back()->with([
            'error' => 'As credenciais informadas são inválidas. Verifique seus dados e tente novamente.',
        ])->withInput();
    }

    $user = Auth::user();
    if ($user->excluido !== null) {
        Auth::logout();
        return back()->with([
            'error' => 'As credenciais informadas são inválidas. Verifique seus dados e tente novamente.',
        ])->withInput();
    }

    $userLog = new UsuarioLog();
    $userLog->usuario_id = $user->id;
    $userLog->criado = date('Y-m-d H:i:s');
    $userLog->save();

    if ($user->tipo === 'admin') {
        return redirect()->intended('/admin');
    } elseif ($user->tipo === 'colaborador') {
        return redirect()->intended('/dashboard');
    } elseif ($user->tipo === 'agencia') {
        return redirect()->intended('/');
    }
  }

  public function logout(){
    Auth::logout();
    return redirect('/login');
  }

  public function cadastro(Request $request){
    // if(Auth::User()){
    //     return redirect('/marcas');
    // }
    // return view('cadastro');
    $user = Auth::User();

    if(Auth::check()){
      if($user->tipo === 'admin'){
        return redirect('/admin/pautas');
      }else if($user->tipo === 'user'){
        return redirect('/marcas');
      }
    }else{
      return view('cadastro');
    }
  }

  public function cadastro_action(Request $request){
      
    $request->validate([
      'nome' => 'required',
      'email' => 'required|email|unique:usuarios',
      'password' => 'required|min:3|confirmed'
    ]);

    $data = $request->only(['nome', 'email', 'password', 'criado']);
    $data['password'] = Hash::make($data['password']);
    $data['criado'] = Carbon::now();

    $userCreate = User::create($data);

    return redirect('/login');
  }
    
}
