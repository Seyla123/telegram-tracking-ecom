<?php

namespace SeavSeyla\Banking\Interfaces;

interface TransactionInterface
{
    public function process(array $data);
}