<?php

namespace Henrik\Session;

interface CSRFHashInterface
{
    /**
     * @param string $value
     *
     * @return bool
     */
    public function isValid(string $value): bool;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return void
     */
    public function regenerateValue(): void;
}