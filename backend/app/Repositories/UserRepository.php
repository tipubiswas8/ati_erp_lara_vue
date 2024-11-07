<?php

namespace App\Repositories;

use App\Interface\UserInterface;

class UserRepository implements UserInterface
{
    public function __construct() {}

    public function index() {}
    public function create() {}
    public function store(array $request) {}
    public function edit(int $id) {}
    public function update(int $id, array $request) {}
    public function delete(int $id) {}
}
