<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EmployeeTest extends TestCase
{

    /**
     * @test -> Display a listing of the resource.
     * @index
     * @return void
     */
    public function test_that_returns_a_list_of_resource()
    {
        $this->get('/employees',$this->authenticate_before());

        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'current_page',
                'data' => ['*' => [
                    'id',
                    "firstname",
                    "lastname",
                    "email",
                    "birthdate",
                    "occupation_id",
                    'created_at',
                    'updated_at',
                    'occupation'
                    ],
                ],
                'per_page',
                'total'
            ]    
        );
    }

    /**
     * @test -> Display the specified resource
     * @show
     * @return void
     */
    public function test_that_returns_display_the_specified_resource()
    {
        $this->get('/employees/2',$this->authenticate_before());

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'id',
            "firstname",
            "lastname",
            "email",
            "birthdate",
            "occupation_id",
            'created_at',
            'updated_at',
            'occupation'
        ]);
    }

    /**
     * @test -> Store a newly created resource in storage
     * @store
     * @return void
     */
    public function test_that_store_a_newly_created_resource_in_storage()
    {
        $body = [
            "firstname" => "Funcionário",
            "lastname" => "Novato",
            "email" => "funcionarionovato@empresa.com",
            "birthdate" => "2004-10-18",
            "occupation_id" => 2
        ];

        $this->post('/employees',$body,$this->authenticate_before());

        $this->seeStatusCode(201);
    }

    /**
     * @test -> Update the specified resource in storage
     * @update
     * @return void
     */
    public function test_that_update_the_specified_resource_in_storage()
    {
        $body = [
            "firstname" => "Funcionário",
            "lastname" => "Antigo",
            "email" => "funcionarioantigo@empresa.com",
            "birthdate" => "1984-02-27",
            "occupation_id" => 2
        ];

        $this->put('/employees/2',$body,$this->authenticate_before());

        $this->seeStatusCode(200);
    }

    /**
     * @test -> Remove the specified resource from storage
     * @destroy
     * @return void
     */
    public function test_that_remove_the_specified_resource_from_storage()
    {
        $this->delete('/employees/11',[],$this->authenticate_before());

        $this->seeStatusCode(200);
    }
}
