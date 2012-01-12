<?php

namespace Auth;

use Auth\Adapter;
use Auth\AuthenticationException;
use Auth\Result;

class Auth
{

    /**
     *
     * @var \Auth\Adapter
     */
    protected $adapter;

    /**
     *
     * @var \Symfony\Component\HttpFoundation\Session
     */
    protected $session;

    public function __construct(\Auth\Adapter $adapter = null, \Symfony\Component\HttpFoundation\Session $session = null)
    {
        $this->adapter = $adapter;
        $this->session = $session;
    }

    /**
     *
     * @return Auth\Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     *
     * @param Auth\Adapter $adapter
     * @return Auth 
     */
    public function setAdapter(\Auth\Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     *
     * @return Symfony\Component\HttpFoundation\Session 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     *
     * @param Symfony\Component\HttpFoundation\Session $session
     * @return Auth 
     */
    public function setSession(\Symfony\Component\HttpFoundation\Session $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     *
     * @return \Auth\Result
     */
    public function authenticate()
    {
        /** @var $result \Auth\Result */
        $result = $this->getAdapter()->authenticate();

        if ($this->hasIdentity())
        {
            $this->clearIdentity();
        }

        if ($result->isValid())
        {
            $this->getSession()->set('identity', $result->getIdentity());
        }

        return $result;
    }

    /**
     * Returns true if the identity is available in the session...
     * @return boolean
     */
    public function hasIdentity()
    {
        return $this->getSession()->has('identity');
    }

    /**
     * Returns the identity from the session or null if the identity is not available
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->getSession()->get('identity');
    }

    /**
     * Clears the identity from the session.
     * @return void
     */
    public function clearIdentity()
    {
        $this->getSession()->remove('identity');
    }

}

