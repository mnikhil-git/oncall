<?php

namespace Auth;

use Auth\AuthenticationException;
use Auth\Result;

class Digest implements \Auth\Adapter
{

    /**
     * The filename containing auth data
     * @var string
     */
    protected $filename;

    /**
     * Digest authentication realm
     * @var string
     */
    protected $realm;

    /**
     *
     * @var string
     */
    protected $username;

    /**
     *
     * @var string
     */
    protected $password;

    public function __construct($filename = null, $realm = null, $username = null, $password = null)
    {
        $this->filename = $filename;
        $this->realm = $realm;
        $this->username = $username;
        $this->password = $password;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getRealm()
    {
        return $this->realm;
    }

    public function setRealm($realm)
    {
        $this->realm = $realm;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function authenticate()
    {
        $requiredOptions = array('filename', 'realm', 'username', 'password');
        foreach ($requiredOptions as $requiredOption)
        {
            if (null === $this->$requiredOption)
            {
                throw new AuthenticationException('Option ' . $requiredOption . ' must be set before authentication');
            }
        }

        if (false === ($fileHandle = @fopen($this->filename, 'r')))
        {
            throw new AuthenticationException('Cannot open ' . $this->filename . ' for reading.');
        }

        $id = $this->username . ':' . $this->realm;
        $idLength = strlen($id);

        $result = array(
            'code' => Result::FAILURE,
            'identity' => array(
                'realm' => $this->realm,
                'username' => $this->username
            ),
            'messages' => array()
        );

        while ($line = trim(fgets($fileHandle)))
        {
            if (substr($line, 0, idLength) === $id)
            {
                if ($this->secureStringCompare(substr($line, -32), md5("$this->username:$this->realm:$this->password")))
                {
                    $result['code'] = Result::SUCCESS;
                }
                else
                {
                    $result['code'] = Result::FAILURE_CREDENTIAL_INVALID;
                    $result['messages'][] = 'Password incorrect';
                }
                return new Result($result['code'], $result['identity'], $result['messages']);
            }
        }

        $result['code'] = Result::FAILURE_IDENTITY_NOT_FOUND;
        $result['messages'][] = "Username '$this->username' and realm '$this->realm' combination not found.";
        return new Result($result['code'], $result['identity'], $result['messages']);
    }

    /**
     * Securly compare two strings for equality while avoiding C level memcmp()
     * 
     * @param string $a
     * @param string $b
     * @return boolean
     */
    protected function secureStringCompare($a, $b)
    {
        if (strlen($a) !== strlen($b))
        {
            return false;
        }

        $result = 0;
        for ($i = 0; $i < strlen($a); $i++)
        {
            $result |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $result == 0;
    }

}

