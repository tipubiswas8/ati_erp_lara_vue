<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\HrEmployee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interface\Hr\HrEmployeeInterface;

class HrEmployeeController extends Controller
{
    protected $hrEmployeeInterface;
    public function __construct(HrEmployeeInterface $hrEmployeeInterface)
    {
        $this->hrEmployeeInterface = $hrEmployeeInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->hrEmployeeInterface->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->hrEmployeeInterface->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->hrEmployeeInterface->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(HrEmployee $hrEmployee)
    {
        return $this->hrEmployeeInterface->show($hrEmployee);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HrEmployee $hrEmployee)
    {
        return $this->hrEmployeeInterface->edit($hrEmployee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HrEmployee $hrEmployee)
    {
        return $this->hrEmployeeInterface->update($request, $hrEmployee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrEmployee $hrEmployee)
    {
        return $this->hrEmployeeInterface->destroy($hrEmployee);
    }

    public function allEmployee()
    {
        return $this->hrEmployeeInterface->allEmployee();
    }
}
