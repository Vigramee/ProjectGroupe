<?php
try {
    $dbh = new PDO(
        'mysql:host=localhost;dbname=airbnb;charset=utf8',
        'root',
        ''
    );
} catch (PDOException $e){
    die($e->getMessage());
}

$query = $dbh->prepare("SELECT * FROM listings");
$query->execute();
$data = $query->fetchAll();


$page = isset($_GET["page"]) ? intval($_GET["page"]) : 0;

function page($token){
    global $page;
    $page = $token;
}
?>

<h1>AirBNB</h1>

<?php for($i = $page; $i < $page + 10; $i++){ ?>
    <div>
        <img src="<?php echo $data[$i]['picture_url']; ?>" 
             alt="Image" width="300" height="200">

        <h1><?php echo $data[$i]['name']; ?></h1>

        <p>
            <?php 
                echo $data[$i]['price'] . " / nuit - " 
                    . $data[$i]['neighbourhood_group_cleansed'] 
                    . " - " . $data[$i]['review_scores_value'] . "/5";
            ?>
        </p>

        <p>
            <img src="<?php echo $data[$i]['host_thumbnail_url']; ?>" 
                 alt="Image" width="50" height="50"> 
            <?php echo $data[$i]['host_name']; ?>
        </p>
    </div>
<?php } ?>
<?php for($z = 0; $z < count($data); $z += 10){ ?>
    <button>
        <a href="?page=<?php echo $z; ?>">
            <?php echo $z/10; ?>
        </a>
    </button>
<?php } ?>
