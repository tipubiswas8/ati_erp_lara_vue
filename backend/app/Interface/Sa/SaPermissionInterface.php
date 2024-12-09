<?php

namespace App\Interface\Sa;

use Illuminate\Http\Request;
use App\Models\Sa\SaPermission;

interface SaPermissionInterface
{
    public function index();
    public function create();
    public function store(Request $request);
    public function show(SaPermission $permission);
    public function edit(SaPermission $permission);
    public function update(Request $request, SaPermission $permission);
    public function destroy(SaPermission $permission);
}
