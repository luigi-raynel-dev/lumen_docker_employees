<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DepartmentTest extends TestCase
{

    /**
     * @test -> Display a listing of the resource.
     * @index
     * @return void
     */
    public function test_that_returns_a_list_of_resource()
    {
        $this->get('/departments',$this->authenticate_before());

        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'current_page',
                'data' => ['*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at'
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
        $this->get('/departments/1',$this->authenticate_before());

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'id',
            'name',
            'created_at',
            'updated_at'
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
            'name' => 'RH'
        ];

        $this->post('/departments',$body,$this->authenticate_before());

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
            'name' => 'Edited'
        ];

        $this->put('/departments/2',$body,$this->authenticate_before());

        $this->seeStatusCode(200);
    }

    /**
     * @test -> Remove the specified resource from storage
     * @destroy
     * @return void
     */
    public function test_that_remove_the_specified_resource_from_storage()
    {
        $this->delete('/departments/10',[],$this->authenticate_before());

        $this->seeStatusCode(200);
    }
}
