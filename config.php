
<?php
try {
    $dbh = new PDO(
        'mysql:host=localhost;dbname=airbnb;charset=utf8',
        'root',
        '13062007'
    );
} catch (PDOException $e){
    die($e->getMessage());
}


?>