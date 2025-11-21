<?php
try {
    $mysqlClient = new PDO(
        'mysql:host=localhost;dbname=airbnb;charset=utf8',
        'root',
        '13062007'
    );
} catch (PDOException $e){
    die($e->getMessage());
}

$query = $mysqlClient->prepare("SELECT * FROM listings");
$query->execute();
$data = $query->fetchAll();
var_dump($data)


?>

<h1>AirBNB</h1>
<?php foreach($data as $element){  ?>
    <div>
        <img src="<?php echo $element['picture_url']; ?>" 
             alt="Image" width="300" height="200">

        <h1><?php echo $element['name']; ?></h1>

        <p>
            <?php 
                echo $element['price'] . " / nuit - " 
                    . $element['neighbourhood_group_cleansed'] 
                    . " - " . $element['review_scores_value'] . "/5";
            ?>
        </p>
            <p>    <img src="<?php echo $element['host_thumbnail_url']; ?>" 
             alt="Image" width="50" height="50"> <?php echo $element['host_name']; ?>   </p>
    </div>
<?php } ?>