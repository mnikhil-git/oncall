<?php

namespace Auth\Adapter;

use Doctrine\ORM\EntityManager;
use Auth\AuthenticationException;
use Auth\Result;

class DoctrineAdapter implements \Auth\Adapter
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $entityManager;

    /**
     *
     * @var string
     */
    protected $userEntityName;

    /**
     *
     * @var array
     */
    protected $userEntity;

    /**
     * The database column and entity element that contains the username
     * @var string
     */
    protected $usernameColumnName;

    /**
     * The database column and entity element that contains the password
     * @var string
     */
    protected $passwordColumnName;

    /**
     * The username entered by the user
     * @var string
     */
    protected $username;

    /**
     * The password entered by the user
     * @var string
     */
    protected $password;

    function __construct(\Doctrine\ORM\EntityManager $entityManager = null, $userEntityName = null, $usernameColumnName = null, $passwordColumnName = null)
    {
        $this->setEntityManager($entityManager);
        if (!is_null($userEntityName))
        {
            $this->setUserEntityName($userEntityName);
        }

        if (!is_null($usernameColumnName))
        {
            $this->setUsernameColumnName($usernameColumnName);
        }

        if (!is_null($passwordColumnName))
        {
            $this->setPasswordColumnName($passwordColumnName);
        }
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return DoctrineAdapter 
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getUserEntityName()
    {
        return $this->userEntityName;
    }

    /**
     *
     * @param string $userEntityName
     * @return DoctrineAdapter 
     */
    public function setUserEntityName($userEntityName)
    {
        $this->userEntityName = $userEntityName;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getUsernameColumnName()
    {
        return $this->usernameColumnName;
    }

    /**
     *
     * @param string $usernameColumnName
     * @return DoctrineAdapter 
     */
    public function setUsernameColumnName($usernameColumnName)
    {
        $this->usernameColumnName = $usernameColumnName;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPasswordColumnName()
    {
        return $this->passwordColumnName;
    }

    /**
     *
     * @param string $passwordColumnName
     * @return DoctrineAdapter 
     */
    public function setPasswordColumnName($passwordColumnName)
    {
        $this->passwordColumnName = $passwordColumnName;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     * @param string $username
     * @return DoctrineAdapter 
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @param string $password
     * @return DoctrineAdapter 
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     *
     * @param array|null $returnColumns
     * @param array|null $omitColumns
     * @return stdClass 
     */
    public function getUser($returnColumns = null, $omitColumns = null)
    {
        if (is_null($this->userEntity))
        {
            return false;
        }

        $returnObject = new stdClass();

        if (null !== $returnColumns)
        {
            $availableColumns = array_keys($this->userEntity);
            foreach ((array) $returnColumns as $returnColumn)
            {
                if (in_array($returnColumn, $availableColumns))
                {
                    $returnObject->{$returnColumn} = $this->userEntity[$returnColumn];
                }
            }
            return $returnObject;
        }
        else if (null !== $omitColumns)
        {
            $omitColumns = (array) $omitColumns;
            foreach ($this->userEntity as $resultColumn => $resultValue)
            {
                if (!in_array($resultColumn, $omitColumn))
                {
                    $returnObject->{$resultColumn} = $resultValue;
                }
            }
            return $returnObject;
        }
        else
        {
            foreach ($this->userEntity as $resultColumn => $resultValue)
            {
                $returnObject->{$resultColumn} = $resultValue;
            }
            return $returnObject;
        }
    }

    /**
     *
     * @return Result 
     */
    public function authenticate()
    {
        $exception = null;
        if (is_null($this->entityManager))
        {
            $exception = 'A Doctrine Entity Manager must be specified before authentication can take place';
        }
        elseif ($this->userEntityName == '')
        {
            $exception = 'An Entity Name must be specified before authentication can take place.';
        }
        elseif ($this->usernameColumnName == '')
        {
            $exception = 'The column name that contains the user\'s identity must be specified.';
        }
        elseif ($this->passwordColumnName == '')
        {
            $exception = 'The column name that contains the user\'s password must be specified.';
        }
        elseif ($this->username == '')
        {
            $exception = 'A value for the username was not provided prior to authentication.';
        }
        elseif ($this->password === null)
        {
            $exception = 'A value for the user\'s password was not provided prior to authentication.';
        }

        if (!is_null($exception))
        {
            throw new AuthenticationException($exception);
        }

        $userRepository = $this->entityManager->getRepository($this->userEntityName);
        $queryBuilder = $userRepository->createQueryBuilder('user');
        $queryBuilder->where('user.' . $this->usernameColumnName .
                ' = :username and user.' . $this->passwordColumnName . ' = :password');
        $queryBuilder->setParameter('username', $this->username);
        $queryBuilder->setParameter('password', $this->password);
        $this->userEntity = $queryBuilder->getQuery()->getArrayResult();

        //$this->userEntity = $userRepository->findOneBy(array($this->usernameColumnName => $this->username));
        $user = $this->userEntity;
        if ($user)
        {
            return new Result(Result::SUCCESS, $this->username, array());
        }
        return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->username, array('The username and password combination you entered could not be found'));
    }

}