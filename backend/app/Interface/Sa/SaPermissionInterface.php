<?php

namespace App\Interface\Sa;

use Illuminate\Http\Request;
use App\Models\Sa\Permission;

interface SaPermissionInterface
{
    public function index();
    public function create();
    public function store(Request $request);
    public function show(Permission $permission);
    public function edit(Permission $permission);
    public function update(Request $request, Permission $permission);
    public function destroy(Permission $permission);
}
