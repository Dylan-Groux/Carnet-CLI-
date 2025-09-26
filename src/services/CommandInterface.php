<?php

namespace App\Services\Command;

interface CommandInterface
{
    public function getName(): string;
    public function execute(array $args): void;
}

