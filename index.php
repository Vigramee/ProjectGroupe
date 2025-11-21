<?php
require "config.php";


$page = 0;


$validSort = ["name", "price", "note", "neighbourhood_group_cleansed"];
$sort = isset($_GET["sort"]) && in_array($_GET["sort"], $validSort)
        ? $_GET["sort"] 
        : "name";

switch ($sort) {
    case "price":
        $orderSql = " ORDER BY price ASC ";
        break;
    case "note":
        $orderSql = " ORDER BY review_scores_value DESC ";
        break;
    case "neighbourhood_group_cleansed":
        $orderSql = " ORDER BY neighbourhood_group_cleansed ASC ";
        break;
    default:
        $orderSql = " ORDER BY name ASC ";
}

$query = $dbh->prepare("SELECT * FROM listings $orderSql");
$query->execute();
$data = $query->fetchAll();

function page($token){
    global $page;
    $page = $token;
}
?>

<h1>AirBNB</h1>


<div style="margin-bottom:15px;">
    <a href="?sort=name"><button>Trier par nom</button></a>
    <a href="?sort=price"><button>Trier par prix</button></a>
    <a href="?sort=note"><button>Trier par note</button></a>
    <a href="?sort=neighbourhood_group_cleansed"><button>Trier par quartier</button></a>
</div>

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
        <a href="?page=<?php echo $z; ?>&sort=<?php echo $sort; ?>">
            <?php echo $z/10; ?>
        </a>
    </button>
<?php } ?>
