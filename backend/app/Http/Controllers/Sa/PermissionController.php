<?php

namespace App\Http\Controllers\Sa;

use App\Models\Sa\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interface\Sa\SaPermissionInterface;

class PermissionController extends Controller
{

    protected $saPermissionInterface;
    public function __construct(SaPermissionInterface $saPermissionInterface)
    {
        $this->saPermissionInterface = $saPermissionInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->saPermissionInterface->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->saPermissionInterface->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->saPermissionInterface->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return $this->saPermissionInterface->show($permission);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return $this->saPermissionInterface->edit($permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        return $this->saPermissionInterface->update($request, $permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        return $this->saPermissionInterface->destroy($permission);
    }
}
