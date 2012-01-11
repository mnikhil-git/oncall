<?php

namespace Auth;

class Result
{
    const FAILURE = 0;
    const FAILURE_IDENTITY_NOT_FOUND = -1;
    const FAILURE_IDENTITY_AMBIGUOUS = -2;
    const FAILURE_CREDENTIAL_INVALID = -3;
    const FAILURE_UNCATEGORIZED = -4;
    const SUCCESS = 1;

    /**
     * Auth result code
     * @var int
     */
    protected $code;

    /**
     * Auth identity
     * @var mixed
     */
    protected $identity;

    /**
     * An array of reasons why the auth attempt was unsuccessful
     * @var array
     */
    protected $messages;

    /**
     *
     * @param int $code
     * @param mixed $identity
     * @param array $messages 
     */
    public function __construct($code, $identity, array $messages = array())
    {
        $code = (int) $code;

        $this->code = $code;
        $this->identity = $identity;
        $this->messages = $messages;
    }

    /**
     * Was the authentication attempt successful?
     * @return boolean
     */
    public function isValid()
    {
        return ($this->code > 0) ? true : false;
    }

    /**
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

}