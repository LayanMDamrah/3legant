<?php
class Database{
    private static $db_server = "localhost";
    private static $db_user = "root";
    private static $db_pass = "";
    private static $db_name = "test";

    private static $conn = null;

    public function __construct(){
        exit("Init function is not allowed");
    }

    //To check if there is an error when we connect
    public static function connect(){
        if(self::$conn == null)
        {
            try{
                self::$conn = mysqli_connect(self::$db_server, self::$db_user, self::$db_pass, self::$db_name);
            }
            catch(mysqli_sql_exception $e){
                echo"Could not connect" . $e->getMessage() . "<br>";
            }
        }
        return self::$conn;
    }
}
?>