<?php

declare(strict_types=1);

namespace Henrik\Session;

use Exception;

/**
 * Class CSRFToken.
 */
class CSRFToken implements CSRFTokenInterface
{
    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var CSRFHash
     */
    private CSRFHash $csrfHash;

    /**
     * CsrfToken constructor.
     *
     * @param Session  $session
     * @param CSRFHash $csrfHash
     *
     * @throws Exception
     */
    public function __construct(Session $session, CSRFHash $csrfHash)
    {
        $this->session  = $session;
        $this->csrfHash = $csrfHash;
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
