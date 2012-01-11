<?php

namespace Auth;

interface Adapter
{

    /**
     * Try to authenticate
     * 
     * @throws Auth\AuthenticationException
     * @return Auth\Result
     */
    public function authenticate();
}
