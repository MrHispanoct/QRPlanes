<?php
class ConnectionParams {
    private $dbhost = 'localhost';
    private $dbname = 'qrplanes';
    private $dbuser = 'super';
    private $dbpass = '123456';
    private $dbport = '3306';
     
    /**
     * Get the value of dbhost
     */ 
    public function getDbhost()
    {
        return $this->dbhost;
    }

    /**
     * Get the value of dbname
     */ 
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     * Get the value of dbuser
     */ 
    public function getDbuser()
    {
        return $this->dbuser;
    }

    /**
     * Get the value of dbpass
     */ 
    public function getDbpass()
    {
        return $this->dbpass;
    }

    /**
     * Get the value of dbport
     */ 
    public function getDbport()
    {
        return $this->dbport;
    }
}
?>