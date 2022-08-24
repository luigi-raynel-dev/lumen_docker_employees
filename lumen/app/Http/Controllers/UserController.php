<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Verifica se há o parametro de pesquisa
        $search = $request->query("q");

        // Obtém o parametro de limite por página ou o padrão da aplicação
        $limit = $request->query("per_page") ?? $_ENV['APP_PER_PAGE'];

        // Caso exista o parametro de pesquisa então...
        if($search){ 
            // Executamos a query de pesquisa com like no Eloquent
            $users = User::where('username','like','%'.$search.'%')
            ->orWhere('email','like','%'.$search.'%')
            ->paginate($limit);
        }else{
            $users = User::paginate($limit);
        }

        // Retorna os registros
        return response()->json($users, 200);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, User $user)
    {
        // Busca a instancia pelo id
        $user = User::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($user)) return response()->json(['message' => 'not found'], 404);

        // Retorna a instancia
        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Aplica a validação dos dados da requisição
        $validator = Validator::make($request->all(), User::$rules);

        // Retorna um log dos erros caso haja falha na validação
        if($validator->fails()) return response()->json([
            'message' => "Errors occurred in data validation",
            'errors' =>  $validator->errors(),
            'status' => false
        ], 422);

        // Instancia a entidade criada pelo Eloquent
        $user = new User;

        // Define os atributos da instância
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $user->save();
            // Dados de retorno do usuário
            $data['api_token'] =  $user->createToken('api_token')->accessToken;
            $data['username'] =  $user->username;
            // mensagem de sucesso do cadastro
            $response = response()->json([
                'message' => 'created', 
                'data' => $data,
                'status' => true
            ], 201);
        } catch (\Throwable $th) {
            $response = response()->json([
                'message' => "an error occurred while saving",
                'errors' => "SQL ERROR CODE: {$th->getCode()}",
                'status' => false
            ], 400);
        }
      
        return $response;
    }  

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(int $id,Request $request, User $user)
    {
        // Busca a instancia pelo id
        $user = User::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($user)) return response()->json(['message' => 'not found'], 404);

        // Pega as regras de validação
        $rules = User::$rules;

        // Verifica se o usuário está tentando alterar para outro username
        if($request->username === $user['username']){
            // Se for o mesmo, remove a regra de unicidade na validação
            $rules = str_replace("unique:users,username|","",$rules);
        }

        // Verifica se o usuário está tentando alterar para outro email
        if($request->email === $user['email']){
            // Se for o mesmo, remove a regra de unicidade na validação
            $rules = str_replace("unique:users,email|","",$rules);
        }
 
        // Aplica a validação
        $validator = Validator::make($request->all(), $rules);
        
        // Retorna um log dos erros caso haja falha na validação
        if($validator->fails()) return response()->json([
            'message' => "Errors occurred in data validation",
            'errors' =>  $validator->errors(),
            'status' => false
        ], 422);
    
        // Define os atributos da instância
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $user->save();
            // mensagem de sucesso da atualização
            $response = response()->json(['message' => 'updated', 'status' => true], 200);
        } catch (\Throwable $th) {
            // mensagem de erro
            $response = response()->json([
                'message' => "an error occurred while saving",
                'errors' => "SQL ERROR - Code: {$th->getCode()}",
                'status' => false
            ], 500);
        }

        // Retorna a mensagem
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id,User $user)
    {
        // Busca a instancia pelo id
        $user = User::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($user)) return response()->json(['message' => 'not found'], 404);

        // Invoca o método de exclusão da instancia no DB pelo Eloquent
        $user->delete();

        // Retorna a mensagem de sucesso da exclusão
        return response()->json(['message' => 'deleted', 'status' => true], 200);
    }
     
}