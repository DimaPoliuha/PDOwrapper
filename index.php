<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 15.04.2018
 * Time: 16:05
 */

class DB {

    private $dbh;
    private $stmt;

    public function __construct($user, $pass, $dbname) {
        $this->dbh = new PDO(
            "mysql:host=localhost;dbname=$dbname",
            $user,
            $pass,
            array( PDO::ATTR_PERSISTENT => true )
        );
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
        return $this;
    }

    public function bind($pos, $value, $type = null) {

        if( is_null($type) ) {
            switch( true ) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($pos, $value, $type);
        return $this;
    }

    public function resultset() {
        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single() {
        $this->stmt->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
}


// Establish a connection.
$host = '127.0.0.1';
$dbname = 'shop';
$user = 'root';
//$pass = '123456';
$pass = '';

$db = new DB($user, $pass, $dbname);

// Create a new query, bind values and return a resultset.
$result = $db->query('SELECT * FROM product WHERE price > ?')
    ->bind(1, '1000')
    ->resultset();

//$temp = count($result);
//echo "<h1>$temp</h1>";

print_r($result);

// Update WHERE clause and return a resultset.
//$db->bind(1, 'def');
//$rs = $db->resultset();







/*
// Create query, bind values and return a single row.
$row = $db->query('SELECT name FROM new WHERE id > ? LIMIT ?')
    ->bind(1, 0)
    ->bind(2, 2)
    ->single();

// Update the LIMIT and get a resultset.

$db->bind(2,3);
$rs = $db->resultset();

$temp = count($row);
//echo "<h1>$temp</h1>";
foreach ($row as $item) {
}


// Create a new query, bind values and return a resultset.
$rs = $db->query('SELECT * FROM new WHERE id < ?')
    ->bind(1, '7')
    ->resultset();

$temp = count($rs);
//echo "<h1>$temp</h1>";
foreach ($rs as $r) {
    foreach ($r as $item) {
        echo "<h1>$item</h1>";
    }
}
print_r($rs);
// Update WHERE clause and return a resultset.
//$db->bind(1, 'def');
//$rs = $db->resultset();
*/

?>