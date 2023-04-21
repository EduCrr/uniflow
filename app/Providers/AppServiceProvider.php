<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Notificacao;
use App\Models\AgenciaUsuario;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale($this->app->getLocale());
        

        view()->composer('*',function($view) {
            $user = Auth::User();
            $notifications = null;
            $notificationsCount = null;
            $agenciaLogged = null;
            if(Auth::check()){
                if($user->tipo === 'colaborador'){
                    $notifications = Notificacao::where('usuario_id', $user->id)->with(['demanda' => function ($query) {
                        $query->where('excluido', null);
                        $query->select('id', 'titulo');
                        }])->orderBy('id', 'DESC')->orderBy('criado', 'DESC')->limit(15)->get(); 
                        $notificationsCount = Notificacao::where('visualizada', '0')->where('usuario_id', $user->id)->count();
                       
                    }else if($user->tipo === 'agencia'){
                    $agId = AgenciaUsuario::select('agencia_id')->where('usuario_id', $user->id)->first();
                    $notifications = Notificacao::where('agencia_id', $agId->agencia_id)->with(['demanda' => function ($query) {
                        $query->where('excluido', null);
                        $query->select('id', 'titulo');
                        }])->orderBy('id', 'DESC')->orderBy('criado', 'DESC')->limit(15)->get(); 
                    $notificationsCount = Notificacao::where('visualizada', '0')->where('agencia_id', $agId->agencia_id)->count();
                        
                    $agenciaLogged = User::select('id')->where('id', $user->id)->with('usuariosAgencias')->first();
                }
            }


            $dataAtual = Carbon::now();
            $dataAtual->second(0);

            $view->with('dataAtual', $dataAtual);
            $view->with('notificationsMenu', $notifications);
            $view->with('notificationsCount', $notificationsCount);
            $view->with('agenciaLogged', $agenciaLogged);
            $view->with('loggedUser', Auth::user());
        });
    }
}
