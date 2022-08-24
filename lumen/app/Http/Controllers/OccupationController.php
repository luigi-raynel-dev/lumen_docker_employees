<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;
use Validator;

class OccupationController extends Controller
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

        // Executamos a query de pesquisa com like no Eloquent
        $occupations = Occupation::select('occupations.*','departments.name AS department');
        
        // Caso exista o parametro de pesquisa então adicionamos o like na query
        if($search) $occupations->where('occupations.name','like','%'.$search.'%');

        // Juntamos com a entidade departamentos
        $occupations->join('departments', 'departments.id', '=', 'occupations.department_id');

        // Retorna os registros com paginação
        return response()->json($occupations->paginate($limit), 200);
    }


    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Occupation  $occupation
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, Occupation $occupation)
    {
        // Busca a instancia pelo id
        $occupation = Occupation::select('occupations.*','departments.name AS department')
        ->where('occupations.id','=',$id)
        ->join('departments','departments.id','=','occupations.department_id')
        ->first();
        
        // Se a instancia não existir retornamos o status 404
        if(empty($occupation)) return response()->json(['message' => 'not found'], 404);
        
        // Retorna a instancia
        return response()->json($occupation, 200);
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
        $validator = Validator::make($request->all(), Occupation::$rules);

        // Retorna um log dos erros caso haja falha na validação
        if($validator->fails()) return response()->json([
            'message' => "Errors occurred in data validation",
            'errors' =>  $validator->errors(),
            'status' => false
        ], 422);

        // Instancia a entidade criada pelo Eloquent
        $occupation = new Occupation;
 
        // Define os atributos da instância
        $occupation->name = $request->name;
        $occupation->department_id = $request->department_id;

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $occupation->save();
            // mensagem de sucesso do cadastro
            $response = response()->json(['message' => 'created', 'status' => true], 201);
        } catch (\Throwable $th) {
            $response = response()->json([
                'message' => "an error occurred while saving",
                'errors' => "SQL ERROR - Code: {$th->getCode()}",
                'status' => false
            ], 400);
        }

        // Retorna a mensagem
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Occupation  $occupation
     * @return \Illuminate\Http\Response
     */
    public function update(int $id,Request $request, Occupation $occupation)
    {
        // Busca a instancia pelo id
        $occupation = Occupation::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($occupation)) return response()->json(['message' => 'not found'], 404);

        // Aplica a validação dos dados da requisição
        $validator = Validator::make($request->all(), Occupation::$rules);

        // Retorna um log dos erros caso haja falha na validação
        if($validator->fails()) return response()->json([
            'message' => "Errors occurred in data validation",
            'errors' =>  $validator->errors(),
            'status' => false
        ], 422);
 
        // Define os atributos da instância
        $occupation->name = $request->name;
        $occupation->department_id = $request->department_id;

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $occupation->save();
            // mensagem de sucesso da atualização
            $response = response()->json(['message' => 'updated', 'status' => true], 200);
        } catch (\Throwable $th) {
            $response = response()->json([
                'message' => "an error occurred while saving",
                'errors' => "SQL ERROR CODE: {$th->getCode()}",
                'status' => false
            ], 400);
        }

        // Retorna a mensagem
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param  \App\Models\Occupation  $occupation
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id,Occupation $occupation)
    {
        // Busca a instancia pelo id
        $occupation = Occupation::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($occupation)) return response()->json(['message' => 'not found'], 404);

        // Invoca o método de exclusão da instancia no DB pelo Eloquent
        $occupation->delete();

        // Retorna a mensagem de sucesso da exclusão
        return response()->json(['message' => 'deleted', 'status' => true], 200);
    }
}
