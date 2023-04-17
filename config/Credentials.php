<?php

namespace config;

class Credentials
{
    private $servername;
    private $database_name;
    private $user_authenticator;
    private $user_employe;
    private $user_directeur;

    private $user_authenticator_password;
    private $user_employe_password;
    private $user_directeur_password;

    /**
     * @return string
     */
    public function getServername()
    {
        return $this->servername;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->database_name;
    }

    /**
     * @return string
     */
    public function getUserAuthenticator()
    {
        return $this->user_authenticator;
    }

    /**
     * @return string
     */
    public function getUserEmploye()
    {
        return $this->user_employe;
    }

    /**
     * @return string
     */
    public function getUserDirecteur()
    {
        return $this->user_directeur;
    }

    /**
     * @return string
     */
    public function getUserAuthenticatorPassword()
    {
        return $this->user_authenticator_password;
    }

    /**
     * @return string
     */
    public function getUserEmployePassword()
    {
        return $this->user_employe_password;
    }

    /**
     * @return string
     */
    public function getUserDirecteurPassword()
    {
        return $this->user_directeur_password;
    }

    /**
     * @param $servername
     * @param $database_name
     * @param $user_employe
     * @param $user_directeur
     * @param $user_employe_password
     * @param $user_directeur_password
     */
    public function __construct()
    {
        $this->servername = 'localhost';
        $this->database_name = 'gestion_zoo';
        $this->user_employe = 'employe';
        $this->user_directeur = 'directeur';
        $this->user_employe_password = 'test123';
        $this->user_directeur_password = 'testsuper123';
    }


}