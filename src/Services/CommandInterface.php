<?php

namespace App\Services;

interface CommandInterface
{
    public function getName(): string;
    public function execute(array $args): void;
    public function getDescription(): string;
}

