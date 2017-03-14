<?php
/**
 * Unicorn.php
 * Created date: 14.03.2017 16:06
 *
 * @author     Bulent KOCAMAN
 * @copyright  2017 Bulent - bulent
 */

namespace UnicornApp;


use PDO;

/**
 * Class Unicorn
 * @package UnicornApp
 */
class Unicorn
{
    CONST CSV_FILE = 'files/unicorn.csv';

    public $csv_data;

    public $data;

    private $table_name = 'csv_table';

    /**
     * Unicorn constructor.
     */
    public function __construct()
    {
        $this->csv_data = array_map('str_getcsv', file(self::CSV_FILE));
    }

    /**
     * @return $this
     */
    public function prepare()
    {
        $fields = array();
        $i = 0;
        foreach($this->csv_data as $val){
            if($i == 0){
                $fields['keys'] = $val;
            }else{
                $fields['values'][] = array_combine($fields['keys'], $val);
            }
            $i++;
        }

        $this->data = $fields;
        return $this;
    }

    /**
     * @return PDO
     */
    private function getConnection()
    {
        $host = '127.0.0.1';
        $user_name = 'root';
        $pass = '123456';
        $db_name = 'unicorn';
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

        $pdo = new PDO("mysql:host=$host;dbname=$db_name", $user_name, $pass, $pdo_options);
        $pdo->exec("set names utf8");
        return $pdo;
    }

    /**
     * @return array
     */
    public function insertDataToDB()
    {
        $conn = $this->getConnection();
        $sql = "INSERT INTO ".$this->table_name." SET name = :name, surname = :surname, age = :age, something = :something";

        $insert_id = array();
        foreach ($this->data['values'] as $value){
            $insert_ = $conn->prepare($sql);
            $insert_->execute($value);
            $insert_id[] = $conn->lastInsertId();
        }

        return $insert_id;
    }
}

$unicorn = new Unicorn();
$ids = $unicorn->prepare()->insertDataToDB();

echo '<pre>';

    echo "INSERT IDS; \n";
    var_dump($ids);

echo '</pre>';






