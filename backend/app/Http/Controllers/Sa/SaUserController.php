<?php

namespace App\Http\Controllers\Sa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interface\Sa\SaUserInterface;

class SaUserController extends Controller
{
    protected $saUserInterface;
    public function __construct(SaUserInterface $saUserInterface)
    {
        $this->saUserInterface = $saUserInterface;
    }
    // for creating a personal access client
    // php artisan passport:client --personal
    public function login(Request $request)
    {
        return $this->saUserInterface->login($request);
    }

    public function registration(Request $request)
    {
        return $this->saUserInterface->registration($request);
    }

    public function update(Request $request)
    {
        return $this->saUserInterface->update($request);
    }

    public function status(Request $request)
    {
        return $this->saUserInterface->status($request);
    }


    public function destroy(Request $request)
    {
        return $this->saUserInterface->destroy($request);
    }

    public function users()
    {
        return $this->saUserInterface->users();
    }
}
