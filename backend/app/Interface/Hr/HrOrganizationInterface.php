<?php

namespace App\Interface\Hr;
use Illuminate\Http\Request;
use App\Models\Hr\HrOrganization;

interface HrOrganizationInterface
{
    public function index();
    public function create();
    public function store(Request $requestData);
    public function show(HrOrganization $hrOrganization);
    public function edit(HrOrganization $hrOrganization);
    public function update(Request $requestData, HrOrganization $hrOrganization);
    public function destroy(HrOrganization $hrOrganization);
    public function restore();
}
