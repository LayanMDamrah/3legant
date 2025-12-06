<?php
    include("database.php");
?>
<?php
class Tools{
    static function cleanData($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
?>