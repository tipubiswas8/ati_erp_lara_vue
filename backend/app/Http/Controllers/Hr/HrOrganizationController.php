<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\HrOrganization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interface\Hr\HrOrganizationInterface;

class HrOrganizationController extends Controller
{
    protected $hrOrganizationInterface;
    public function __construct(HrOrganizationInterface $hrOrganizationInterface)
    {
        $this->hrOrganizationInterface = $hrOrganizationInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->hrOrganizationInterface->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->hrOrganizationInterface->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->hrOrganizationInterface->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(HrOrganization $hrOrganization)
    {
        return $this->hrOrganizationInterface->show($hrOrganization);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HrOrganization $hrOrganization)
    {
        return $this->hrOrganizationInterface->edit($hrOrganization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HrOrganization $hrOrganization)
    {
        return $this->hrOrganizationInterface->update($request, $hrOrganization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrOrganization $hrOrganization)
    {
        return $this->hrOrganizationInterface->destroy($hrOrganization);
    }

    public function restore()
    {
        return $this->hrOrganizationInterface->restore();
    }
}
