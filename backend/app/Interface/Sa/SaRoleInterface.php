<?php

namespace App\Interface\Sa;
use Illuminate\Http\Request;
use App\Models\Sa\SaRole;

interface SaRoleInterface
{
    public function index();
    public function create();
    public function store(Request $request);
    public function show(SaRole $role);
    public function edit(SaRole $role);
    public function update(Request $request, SaRole $role);
    public function destroy(SaRole $role);
}
