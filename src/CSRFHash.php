<?php

declare(strict_types=1);

namespace Henrik\Session;

use Exception;

/**
 * Class CSRFHash.
 */
class CSRFHash implements CSRFHashInterface
{
    /**
     * @var string $hash
     */
    private string $hash;

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function isValid(string $value): bool
    {
        if (function_exists('hash_equals')) {
            return hash_equals($value, $this->getValue());
        }

        return $value === $this->getValue();
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function getValue(): string
    {
        if (empty($this->hash)) {
            $this->regenerateValue();
        }

        return $this->hash;
    }

    /**
     * {@inheritDoc}
     */
    public function regenerateValue(): void
    {
        $this->hash = hash('sha512', RandomHashGenerator::generate());
    }
}