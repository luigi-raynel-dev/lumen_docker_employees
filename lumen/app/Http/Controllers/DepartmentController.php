<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Validator;

class DepartmentController extends Controller
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
            $departments = Department::where('name','like','%'.$search.'%')
            ->paginate($limit);
        }else{
            $departments = Department::paginate($limit);
        }

        // Retorna os registros
        return response()->json($departments, 200);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, Department $department)
    {
        // Busca a instancia pelo id
        $department = Department::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($department)) return response()->json(['message' => 'not found'], 404);

        // Retorna a instancia
        return response()->json($department, 200);
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
        $validator = Validator::make($request->all(), Department::$rules);

        // Retorna um log dos erros caso haja falha na validação
        if($validator->fails()) return response()->json([
            'message' => "department {$request->name} already exists",
            'errors' =>  $validator->errors(),
            'status' => false
        ], 422);

        // Instancia a entidade criada pelo Eloquent
        $department = new Department;
 
        // Define os atributos da instância
        $department->name = $request->name;

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $department->save();
            // mensagem de sucesso do cadastro
            $response = response()->json(['message' => 'created','status' => true], 201);
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
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(int $id,Request $request, Department $department)
    {
        // Busca a instancia pelo id
        $department = Department::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($department)) return response()->json(['message' => 'not found'], 404);
 
        // Se houver a edição do nome verificamos se esse novo nome já existe
        if($department['name'] !== $request->name){
            $validator = Validator::make($request->all(), Department::$rules);
    
            if($validator->fails()) return response()->json([
                'message' => "department {$request->name} already exists",
                'errors' =>  $validator->errors(),
                'status' => false
            ], 422);
        }

        // Define os atributos da instância
        $department->name = $request->name;

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $department->save();
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
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id,Department $department)
    {
        // Busca a instancia pelo id
        $department = Department::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($department)) return response()->json(['message' => 'not found'], 404);

        // Invoca o método de exclusão da instancia no DB pelo Eloquent
        $department->delete();

        // Retorna a mensagem de sucesso da exclusão
        return response()->json(['message' => 'deleted', 'status' => true], 200);
    }
}
