<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Validator;

class EmployeeController extends Controller
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
        $employees = Employee::select('employees.*','occupations.name AS occupation');
        
        // Caso exista o parametro de pesquisa então adicionamos os likes na query
        if($search) $employees
        ->where('employees.firstname','like','%'.$search.'%')
        ->orWhere('employees.lastname','like','%'.$search.'%')
        ->orWhere('employees.email','like','%'.$search.'%');

        // Juntamos com a entidade ocupações
        $employees->join('occupations', 'occupations.id', '=', 'employees.occupation_id');

        // Retorna os registros com paginação
        return response()->json($employees->paginate($limit), 200);
    }


    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, Employee $employee)
    {
        // Busca a instancia pelo id
        $employee = Employee::select('employees.*','occupations.name AS occupation')
        ->where('employees.id','=',$id)
        ->join('occupations','occupations.id','=','employees.occupation_id')
        ->first();
        
        // Se a instancia não existir retornamos o status 404
        if(empty($employee)) return response()->json(['message' => 'not found'], 404);
        
        // Retorna a instancia
        return response()->json($employee, 200);
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
        $validator = Validator::make($request->all(), Employee::$rules);

        // Retorna um log dos erros caso haja falha na validação
        if($validator->fails()) return response()->json([
            'message' => "Errors occurred in data validation",
            'errors' =>  $validator->errors(),
            'status' => false
        ], 422);
        
        // Instancia a entidade criada pelo Eloquent
        $employee = new Employee;
 
        // Define os atributos da instância
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->email = $request->email;
        $employee->birthdate = $request->birthdate;
        $employee->occupation_id = $request->occupation_id;

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $employee->save();
            // mensagem de sucesso do cadastro
            $response = response()->json(['message' => 'created', 'status' => true], 201);
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
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(int $id,Request $request, Employee $employee)
    {
        // Busca a instancia pelo id
        $employee = Employee::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($employee)) return response()->json(['message' => 'not found'], 404);

        // Aplica a validação dos dados da requisição
        $validator = Validator::make($request->all(), Employee::$rules);

        // Retorna um log dos erros caso haja falha na validação
        if($validator->fails()) return response()->json([
            'message' => "Errors occurred in data validation",
            'errors' =>  $validator->errors(),
            'status' => false
        ], 422);
 
        // Define os atributos da instância
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->email = $request->email;
        $employee->birthdate = $request->birthdate;
        $employee->occupation_id = $request->occupation_id;

        try {
            // Invoca o método de salvamento da instancia no DB pelo Eloquent
            $employee->save();
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
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id,Employee $employee)
    {
        // Busca a instancia pelo id
        $employee = Employee::find($id);

        // Se a instancia não existir retornamos o status 404
        if(empty($employee)) return response()->json(['message' => 'not found'], 404);

        // Invoca o método de exclusão da instancia no DB pelo Eloquent
        $employee->delete();

        // Retorna a mensagem de sucesso da exclusão
        return response()->json(['message' => 'deleted', 'status' => true], 200);
    }
}
