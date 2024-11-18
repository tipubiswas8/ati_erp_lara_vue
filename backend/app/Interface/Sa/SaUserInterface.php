<?php

namespace App\Interface\Sa;
use Illuminate\Http\Request;

interface SaUserInterface
{
    public function login(Request $requestData);
    public function registration(Request $requestData);
    public function update(Request $requestData);
    public function status(Request $requestData);
    public function destroy(Request $requestData);
    public function users();
}
