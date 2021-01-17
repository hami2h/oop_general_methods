<?php


class db
{
    protected $pdo;
    private $server;
    private $username;
    private $password;
    private $dbname;
    private $tbl;


    public function __construct($server = 'localhost', $username = 'username_is_here', $password = 'password_is_here', $dbname = 'database_name')
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connection();
    }

    public function connection()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->server;dbname=$this->dbname", $this->username, $this->password);

        } catch (Exception $error) {
            die($error->getMessage());
        }

    }

    public function setTbl($tbl)
    {
        $this->tbl = $tbl;
    }

    public function selectData($name)
    {
        if (is_array($name)) {
            $names = "'" . implode("','", $name) . "'";
            $sql = $this->pdo->prepare("select $names from $this->tbl");
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public function insertData($filed, $data)
    {
        if (is_array($data)) {
            $names = "'" . implode("','", $data) . "'";
            $filed = implode(",", $filed);
            $sql = $this->pdo->prepare("insert into $this->tbl ($filed) values ($names)");
            $sql->execute();
        }
    }

    public function editData($filed, $data, $id)
    {
        foreach ($filed as $key => $val) {
            $txt[] = $val . "='" . $data[$key] . "'";
        }
        $query = implode(",", $txt);
        $sql = $this->pdo->prepare("update $this->tbl set $query" . " where id=$id");
        $sql->execute();

    }

    public function deleteData($id)
    {
        $sql = $this->pdo->prepare("delete from $this->tbl where id=$id");
        $sql->execute();
    }

    public function serachData($name, $value)
    {
        $sql = $this->pdo->prepare("select * from $this->tbl where $name='$value'");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function likeData($name, $value)
    {
        $sql = $this->pdo->prepare("select * from $this->tbl where $name LIKE '%$value%'");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}