<?php

declare(strict_types=1);

namespace Henrik\Session;

use Exception;
use Henrik\Contracts\Session\SessionInterface;
use Henrik\Contracts\Session\SessionSegmentInterface;

/**
 * Class CSRFToken.
 */
class CSRFToken implements CSRFTokenInterface, SessionSegmentInterface
{
    public const CSRF_SEGMENT_KEY = '_csrf';

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
        $this->session->setSegment($this);
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

    public function getSegmentName(): string
    {
        return self::CSRF_SEGMENT_KEY;
    }
}
