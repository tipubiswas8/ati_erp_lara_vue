<?php

namespace App\Interface;

interface UserInterface
{
    public function index();
    public function create();
    public function store(array $request);
    public function edit(int $id);
    public function update(int $id, array $request);
    public function delete(int $id);
}
