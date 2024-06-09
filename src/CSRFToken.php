<?php

declare(strict_types=1);

namespace Henrik\Session;

use Exception;
use Henrik\Contracts\Session\SessionInterface;

/**
 * Class CSRFToken.
 */
class CSRFToken implements CSRFTokenInterface
{
    /**
     * CsrfToken constructor.
     *
     * @param Session  $session
     * @param CSRFHash $csrfHash
     *
     * @throws Exception
     */
    public function __construct(protected SessionInterface $session, private CSRFHashInterface $csrfHash)
    {
        $this->session->setSegmentName('csrf');
        $this->regenerateValue();
    }

    /**
     *{@inheritDoc}
     *
     * @throws Exception
     */
    public function regenerateValue(): void
    {
        $this->session->set('value', $this->csrfHash->getValue());
    }

    /**
     *{@inheritDoc}
     *
     * @throws Exception
     */
    public function isValid(string $value): bool
    {
        return $this->csrfHash->isValid($value);
    }

    /**
     *{@inheritDoc}
     */
    public function getValue(): mixed
    {
        return $this->session->get('value');
    }
}
