<?php

namespace App\Interface\Hr;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

interface HrEmployeeInterface
{
    public function index();
    public function create();
    public function store(Request $requestData);
    public function show(HrEmployee $hrEmployee);
    public function edit(HrEmployee $hrEmployee);
    public function update(Request $requestData, HrEmployee $hrEmployee);
    public function destroy(HrEmployee $hrEmployee);
    public function allEmployee();
    public function restore();
}
