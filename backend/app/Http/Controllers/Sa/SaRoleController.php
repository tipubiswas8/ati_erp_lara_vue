<?php

namespace App\Http\Controllers\Sa;

use App\Http\Controllers\Controller;
use App\Models\Sa\SaRole;
use Illuminate\Http\Request;
use App\Interface\Sa\SaRoleInterface;

class SaRoleController extends Controller
{

    protected $saRoleInterface;
    public function __construct(SaRoleInterface $saRoleInterface)
    {
        $this->saRoleInterface = $saRoleInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->saRoleInterface->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->saRoleInterface->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->saRoleInterface->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(SaRole $role)
    {
        return $this->saRoleInterface->show($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaRole $role)
    {
        return $this->saRoleInterface->edit($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaRole $role)
    {
        return $this->saRoleInterface->update($request, $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaRole $role)
    {
        return $this->saRoleInterface->destroy($role);
    }

    public function status()
    {
        return $this->saRoleInterface->status();
    }
}
