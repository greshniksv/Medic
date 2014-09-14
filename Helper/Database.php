<?php
/* Support database: Mysql, Sqlite */

abstract class DatabaseType{
    const MySql=0;
    const Sqlite=1;
}

class Database{
    var $type;
    var $dblink;
    var $query;

    var $host;
    var $database;
    var $user;
    var $password;
    var $bb;

    function __construct($host,$database,$user,$password,$databaseType = 0) {

        $this->host=$host;
        $this->database=$database;
        $this->user=$user;
        $this->password=$password;
        $this->type = $databaseType;
    }

    public function Connect() {

        if($this->type==DatabaseType::MySql)
        {
            $this->dblink = new mysqli($this->host, $this->user, $this->password, $this->database);
            if ($this->dblink->connect_error) {
                die('Connect Error (' . $mysqli->connect_errno . ') '
                    . $mysqli->connect_error);
                return false;
            }
            return true;
        }

        if ($this->type == DatabaseType::Sqlite) {
            $this->dblink = new SQLiteDatabase($this->database, 0660);
            if (!file_exists($this->database)){
                die("Permission Denied!");
                return false;
            }
            return true;
        }
    }

    public function Disconnect()
    {
        if ($this->type == DatabaseType::Sqlite) {
            sqlite_close($this->dblink);
        }

        if ($this->type == DatabaseType::MySql) {
            $this->dblink->close();
        }
    }

    public function Exec($sql) {
        if ($this->type == DatabaseType::MySql) {
            if ($this->dblink->query($sql) === FALSE) {
                printf("Error: %s\n", $this->dblink->error);
                return FALSE;
            }
            return true;
        }
    }

    public function Query($sql,$buffered=true)
    {
        if ($this->type == DatabaseType::Sqlite) {
            $this->query = sqlite_query($this->dblink, $sql);
        }

        if ($this->type == DatabaseType::MySql) {
            if($buffered){
                $this->query = $this->dblink->query($sql);
            }
            else {
                $this->query = $this->dblink->unbufferedQuery($sql);
            }

            if($this->query==FALSE) echo "Error exec";
        }
    }


    public function Fetch($assoc=true,$one=false)
    {
        if ($this->type == DatabaseType::Sqlite) {
            if($assoc==true){
                $rez =  sqlite_fetch_array($this->query, SQLITE_ASSOC);
                if($one){ $stopFetch = StopFetch(); }
                return $rez;
            }
            else{
                $rez =  sqlite_fetch_array($this->query, SQLITE_NUM);
                if($one){ $stopFetch = StopFetch(); }
                return $rez;
            }
        }

        if ($this->type == DatabaseType::MySql) {

            if($assoc==true)
            {
                $rez = @$this->query->fetch_array(MYSQL_ASSOC);
                if($one){ $this->StopFetch(); }
                return $rez;
            }
            else{
                $rez = @$this->query->fetch_array(MYSQL_NUM);
                if($one){ $this->StopFetch(); }
                return $rez;
            }
        }
    }

    public function StopFetch()
    {
        if ($this->type == DatabaseType::MySql) {
            @$this->query->close();
        }

        if ($this->type == DatabaseType::Sqlite) {
            @$this->query->close();
        }
    }
}



