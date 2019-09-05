<?php

namespace henrik\session;


/**
 * Class CSRFToken
 * @package henrik\session
 */
class CSRFToken
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var CSRFHash
     */
    private $csrfHash;

    /**
     * CsrfToken constructor.
     * @param Session $session
     * @param CSRFHash $csrfHash
     * @throws \Exception
     */
    public function __construct(Session $session, CSRFHash $csrfHash)
    {
        $this->session = $session;
        $this->csrfHash = $csrfHash;
        $this->session->setSegmentName("csrf");
        $this->regenerateValue();
    }

    /**
     * @throws \Exception
     */
    public function regenerateValue()
    {
        $this->session->set('value', $this->csrfHash->getValue());
    }

    /**
     * @param $value
     * @return bool
     * @throws \Exception
     */
    public function isValid($value)
    {
        return $this->csrfHash->isValid($value);
    }

    /**
     *
     * Gets the value of the CSRF token.
     *
     * @return string
     *
     */
    public function getValue()
    {
        return $this->session->get('value');
    }
}
