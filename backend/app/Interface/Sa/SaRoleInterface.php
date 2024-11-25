<?php

namespace App\Interface\Sa;
use Illuminate\Http\Request;
use App\Models\Sa\Role;

interface SaRoleInterface
{
    public function index();
    public function create();
    public function store(Request $request);
    public function show(Role $role);
    public function edit(Role $role);
    public function update(Request $request, Role $role);
    public function destroy(Role $role);
}
