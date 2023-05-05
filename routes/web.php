<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\DemandasController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\AdminDemandasController;
use App\Http\Controllers\NotificacoesController;
use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\RespostasController;
use App\Http\Controllers\AdminAgenciaController;
use App\Http\Controllers\AuthController;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//admin
Route::middleware(['auth', 'isAdmin'])->group(function(){
    Route::get('/admin', [AdminDemandasController::class, 'index'])->name('Admin');
    Route::get('/admin/estados/{id}', [AdminDemandasController::class, 'getCityByStatesAdmin'])->name('getEstadosAdmin');
    Route::get('/admin/job/{id}', [AdminDemandasController::class, 'findOne'])->name('Admin.job');
    Route::get('/admin/jobs', [AdminDemandasController::class, 'jobs'])->name('Admin.jobs');
    Route::get('/admin/etapas', [AdminDemandasController::class, 'stages'])->name('Admin.Etapas');
    //add
    Route::get('/admin/agencia/adicionar', [AdminDemandasController::class, 'agency'])->name('Admin.agencia');
    Route::get('/admin/marca/adicionar', [AdminDemandasController::class, 'brand'])->name('Admin.marca');
    Route::get('/admin/usuario/adicionar', [AdminDemandasController::class, 'user'])->name('Admin.usuario');
    Route::post('/admin/agencia/adicionar', [AdminDemandasController::class, 'agencyCreate'])->name('Admin.agencia_adicionar');
    Route::post('/admin/usuario/adicionar', [AdminDemandasController::class, 'userCreate'])->name('Admin.usuario_adicionar');
    Route::post('/admin/marca/adicionar', [AdminDemandasController::class, 'brandCreate'])->name('Admin.marca_adicionar');

    //edit
    Route::get('/admin/agencias', [AdminDemandasController::class, 'agencysAll'])->name('Admin.agencias');
    Route::get('/admin/marcas', [AdminDemandasController::class, 'brandsAll'])->name('Admin.marcas');
    Route::get('/admin/usuarios', [AdminDemandasController::class, 'usersAll'])->name('Admin.usuarios');
    Route::get('/admin/agencia/editar/{id}', [AdminDemandasController::class, 'agencyEdit'])->name('Admin.agencia_editar');
    Route::get('/admin/marca/editar/{id}', [AdminDemandasController::class, 'brandEdit'])->name('Admin.marca_editar');
    Route::get('/admin/usuario/editar/{id}', [AdminDemandasController::class, 'userEdit'])->name('Admin.usuario_editar');
    Route::post('/admin/agencia/editar/{id}', [AdminDemandasController::class, 'agencyEditAction'])->name('Admin.agencia_editar_action');
    Route::post('/admin/usuario/editar/{id}', [AdminDemandasController::class, 'userEditAction'])->name('Admin.usuario_editar_action');
    Route::post('/admin/marca/editar/{id}', [AdminDemandasController::class, 'brandEditAction'])->name('Admin.marca_editar_action');

    //delete
    Route::get('/admin/agencia/delete/{id}', [AdminDemandasController::class, 'agencyDelete'])->name('Admin.agencia_delete_action');
    Route::get('/admin/marca/delete/{id}', [AdminDemandasController::class, 'brandDelete'])->name('Admin.marca_delete_action');
    Route::get('/admin/usuario/delete/{id}', [AdminDemandasController::class, 'userDelete'])->name('Admin.usuario_delete_action');

    //graphs
    Route::get('/admin/agencia/graficos/{id}', [AdminDemandasController::class, 'agencysGraphs'])->name('Admin.agencia_graficos');
    Route::get('admin/export/{id}', [AdminDemandasController::class, 'exportDays'])->name('admin.export');
    Route::get('admin/export/jobs/{id}', [AdminDemandasController::class, 'exportJobs'])->name('admin.export.jobs');
    Route::get('admin/export/prazos/{id}', [AdminDemandasController::class, 'exportPrazos'])->name('admin.export.prazos');

    
});

//agencia
    Route::middleware(['auth', 'isAgencia'])->group(function(){
    // Route::get('/', [HomeController::class, 'index'])->name('Home');
    Route::get('/minhas-pautas', [DemandasController::class, 'findAll'])->name('Pautas');
    Route::post('/status/demanda/{id}', [DemandasController::class, 'statusSelect'])->name('status');
    Route::get('/prioridade/agencia', [DemandasController::class, 'changeCategoryAg'])->name('Prioridade.agencia');
    Route::post('/changeStatus', [DemandasController::class, 'changeStatus'])->name('status');
    Route::post('/changeStatusPauta/{id}', [DemandasController::class, 'changeStatusPauta'])->name('Pauta.criar_tempo');
    Route::post('/editStatusPauta/{id}', [DemandasController::class, 'editStatusPauta'])->name('Pauta_editar');
    Route::post('/changeStatusEntrega/{id}', [DemandasController::class, 'changeStatusEntrega'])->name('Status.entrega');
    Route::post('/demanda/titulo/{id}', [DemandasController::class, 'changeDemandatitle'])->name('Demanda_titulo');
    Route::post('/pauta/finalizar/{id}', [DemandasController::class, 'finalizeAgenda'])->name('Pauta.finalizar_tempo');
    Route::post('/pauta/iniciar/{id}', [DemandasController::class, 'startAgenda'])->name('Pauta.iniciar_tempo');
    Route::post('/pauta/aceitar/tempo/agencia/{id}', [DemandasController::class, 'acceptTime'])->name('Pauta.Aceitar_tempo_agencia');
    Route::post('/pauta/receber/{id}', [DemandasController::class, 'receive'])->name('Pauta.receber');
    Route::post('/pauta/receber/alteracao/{id}', [DemandasController::class, 'receiveAlteration'])->name('Pauta.receber_alteracao');

});

//colaborador
Route::middleware(['auth', 'isColaborador'])->group(function () {
    Route::get('/dashboard', [ColaboradorController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/jobs', [ColaboradorController::class, 'jobs'])->name('Jobs');
    Route::get('/dashboard/etapas', [ColaboradorController::class, 'stages'])->name('Etapas');
    Route::get('/dashboard/criar/etapa/1', [ColaboradorController::class, 'create'])->name('Job.criar');
    Route::get('/dashboard/criar/job/{id}/etapa/2', [ColaboradorController::class, 'createStage2'])->name('Job.criar_etapa_2');
    Route::get('/dashboard/deletar/job/{id}/etapa/1', [ColaboradorController::class, 'delteStage1'])->name('Job.deletar_etapa_1');
    Route::get('/dashboard/job/editar/{id}', [ColaboradorController::class, 'edit'])->name('Job.editar');
    Route::get('/dashboard/job/copiar/{id}', [ColaboradorController::class, 'copy'])->name('Job.copiar');
    Route::get('/dashboard/delete/{id}', [ColaboradorController::class, 'delete'])->name('Job.delete');
    Route::post('/dashboard/criar-action', [ColaboradorController::class, 'createAction'])->name('Job.criar_action');
    Route::post('/dashboard/job/{id}/criar-action-etapa-2', [ColaboradorController::class, 'createActionStage2'])->name('Job.criar_action_stage_2');
    Route::post('/dashboard/editar/{id}', [ColaboradorController::class, 'editAction'])->name('Job.editar_action');
    Route::post('/dashboard/copiar', [ColaboradorController::class, 'copyAction'])->name('Job.copiar_action');
    Route::post('/reaberto/{id}', [ColaboradorController::class, 'reOpenJob'])->name('reaberto');
    Route::get('/prioridade', [ColaboradorController::class, 'changeCategory'])->name('prioridade');
    Route::post('/finalizar/demanda/{id}', [ColaboradorController::class, 'finalize'])->name('Finalizar_action');
    Route::post('/pausar/demanda/{id}', [ColaboradorController::class, 'pause'])->name('Pausar_action');
    Route::post('/retomar/demanda/{id}', [ColaboradorController::class, 'resume'])->name('Retomar_action');
    Route::post('/jobs/date', [ColaboradorController::class, 'getJobsByDate'])->name('Job.date');
    Route::post('/pauta/aceitar/tempo/colaborador/{id}', [ColaboradorController::class, 'acceptTime'])->name('Pauta.Aceitar_tempo_colaborador');
    Route::post('/receber/alteracoes/{id}', [ColaboradorController::class, 'receiveAlteration'])->name('Receber_alteracoes');
    Route::post('/usuarios/busca', [ColaboradorController::class, 'getUserAgency'])->name('Usuario.busca');
    Route::post('/respostas/create/{id}', [RespostasController::class, 'answerCreate'])->name('Answer.create'); 
    Route::post('/respostas/action/{id}', [RespostasController::class, 'answerAction'])->name('Answer.action'); 
    Route::post('/respostas/delete/{id}', [RespostasController::class, 'delete'])->name('Answer.delete');
    Route::get('/respostas/editar/{id}', [RespostasController::class, 'getAnswer'])->name('getAnswer');
    Route::post('/respostas/editar-form', [RespostasController::class, 'getAnswerAction'])->name('Answer.edit');


});

//agencia admin
Route::middleware(['auth', 'isAdminAgencia'])->group(function(){
    Route::get('/agencia/criar/etapa/1', [AdminAgenciaController::class, 'create'])->name('Agencia.criar');
    Route::get('/agencia/criar/job/{id}/etapa/2', [AdminAgenciaController::class, 'createStage2'])->name('Agencia.criar_etapa_2');
    Route::get('/agencia/jobs', [AdminAgenciaController::class, 'jobs'])->name('Agencia.Jobs');
    Route::get('/agencia/etapas', [AdminAgenciaController::class, 'stages'])->name('Agencia.Etapas');
    Route::get('/agencia/deletar/job/{id}/etapa/1', [AdminAgenciaController::class, 'delteStage1'])->name('Agencia.deletar_etapa_1');
    Route::get('/agencia/job/editar/{id}', [AdminAgenciaController::class, 'edit'])->name('Agencia.editar');
    Route::get('/agencia/job/copiar/{id}', [AdminAgenciaController::class, 'copy'])->name('Agencia.copiar');
    Route::get('/agencia/delete/{id}', [AdminAgenciaController::class, 'delete'])->name('Agencia.delete');
    Route::post('/agencia/criar-action', [AdminAgenciaController::class, 'createAction'])->name('Agencia.criar_action');
    Route::post('/agencia/job/{id}/criar-action-etapa-2', [AdminAgenciaController::class, 'createActionStage2'])->name('Agencia.criar_action_stage_2');
    Route::post('/agencia/editar/{id}', [AdminAgenciaController::class, 'editAction'])->name('Agencia.editar_action');
    Route::post('/agencia/copiar', [AdminAgenciaController::class, 'copyAction'])->name('Agencia.copiar_action');

    // Route::post('/agencia/reaberto/{id}', [AdminAgenciaController::class, 'reOpenJob'])->name('Agencia.reaberto');
    // Route::get('/agencia/prioridade', [AdminAgenciaController::class, 'changeCategory'])->name('Agencia;prioridade');
    // Route::post('/agencia/finalizar/demanda/{id}', [AdminAgenciaController::class, 'finalize'])->name('Agencia.Finalizar_action');
    // Route::post('/agencia/pausar/demanda/{id}', [AdminAgenciaController::class, 'pause'])->name('Agencia.Pausar_action');
    // Route::post('/agencia/retomar/demanda/{id}', [AdminAgenciaController::class, 'resume'])->name('Agencia.Retomar_action');
    // Route::post('/agencia/jobs/date', [AdminAgenciaController::class, 'getJobsByDate'])->name('Job.date');
    // Route::post('/agencia/pauta/aceitar/tempo/colaborador/{id}', [AdminAgenciaController::class, 'acceptTime'])->name('Agencia.Aceitar_tempo_colaborador');
    // Route::post('/agencia/receber/alteracoes/{id}', [AdminAgenciaController::class, 'receiveAlteration'])->name('Agencia.Receber_alteracoes');

});

Route::middleware(['auth', 'isColaboradorAgenciaAdmin'])->group(function(){
    // Route::post('/respostas/create/{id}', [RespostasController::class, 'answerCreate'])->name('Answer.create'); 
    // Route::post('/respostas/action/{id}', [RespostasController::class, 'answerAction'])->name('Answer.action'); 
    // Route::post('/respostas/delete/{id}', [RespostasController::class, 'delete'])->name('Answer.delete');
    // Route::get('/respostas/editar/{id}', [RespostasController::class, 'getAnswer'])->name('getAnswer');
    // Route::post('/respostas/editar-form', [RespostasController::class, 'getAnswerAction'])->name('Answer.edit');
});

//logado
Route::middleware(['auth'])->group(function(){
    Route::get('/', [HomeController::class, 'homeIndex'])->name('index');
    Route::get('/meu-perfil', [UsuariosController::class, 'index'])->name('Usuario');
    Route::get('/estados/{id}', [UsuariosController::class, 'getCityByStates'])->name('getEstados');
    Route::post('/usuario/{id}', [UsuariosController::class, 'edit'])->name('Usuario.editar_action');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('image-download/{id}', [DemandasController::class, 'downloadImage'])->name('download.image');
    Route::post('/imagem/delete/{id}', [DemandasController::class, 'deleteArq'])->name('Imagem.delete');
    Route::post('/comentario/action/{id}', [ComentariosController::class, 'comentaryAction'])->name('Comentario.action'); 
    Route::post('/comentario/delete/{id}', [ComentariosController::class, 'delete'])->name('Comentario.delete');
    Route::get('/comentario/editar/{id}', [ComentariosController::class, 'getComentary'])->name('getComentary');
    Route::post('/comentario/editar-form', [ComentariosController::class, 'getComentaryAction'])->name('Comentario.edit');
    Route::get('/job/{id}', [DemandasController::class, 'index'])->name('Job');
    Route::post('/imagem/upload/{id}', [DemandasController::class, 'uploadImg'])->name('Imagem.upload');
    Route::get('/notificacoes', [NotificacoesController::class, 'index'])->name('Notification');
    Route::post('/notificacao/action', [NotificacoesController::class, 'action'])->name('Notification.action');
    Route::post('/notificacao/{id}', [NotificacoesController::class, 'actionSingle'])->name('Notification.action.single');
    Route::post('/demanda/prazo/sugerido/{id}', [DemandasController::class, 'changeTime'])->name('Demanda.prazo.action');
    Route::get('/agencia/job/{id}', [AdminAgenciaController::class, 'job'])->name('Agencia.Job');

});

//login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login_action'])->name('login_action');
Route::get('/recuperar/senha', [UsuariosController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/recuperar/senha', [UsuariosController::class, 'forgotPasswordAction'])->name('forgotPassword.action');
Route::get('/recuperar/senha/{token}', [UsuariosController::class, 'showResetForm'])->name('ShowResetForm');
Route::post('/resetar/senha', [UsuariosController::class, 'resetpassword'])->name('Resetpassword');


