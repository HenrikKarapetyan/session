<?php

namespace Henrik\Session;

interface CSRFTokenInterface
{
    /**
     * Gets the value of the CSRF token.
     *
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * @return void
     */
    public function regenerateValue(): void;

    /**
     * @param string $value
     *
     * @return bool
     */
    public function isValid(string $value): bool;
}