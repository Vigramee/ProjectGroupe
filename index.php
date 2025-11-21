<?php
require 'config.php';



if (isset($_POST['add'])) {

    $insert = $dbh->prepare("
        INSERT INTO listings(name, picture_url, price, neighbourhood_group_cleansed,
                             review_scores_value, host_thumbnail_url, host_name)
        VALUES(:name, :pic, :price, :ng, :score, :host_pic, :host_name)
    ");

    $insert->execute([
        ':name'      => $_POST['name'],
        ':pic'       => $_POST['picture_url'],
        ':price'     => $_POST['price'],
        ':ng'        => $_POST['neigh'],
        ':score'     => $_POST['score'],
        ':host_pic'  => $_POST['host_picture'],
        ':host_name' => $_POST['host_name']
    ]);
}


$columns = ['price', 'name', 'neighbourhood_group_cleansed', 'review_scores_value'];
$sort = (isset($_GET['sort']) && in_array($_GET['sort'], $columns)) ? $_GET['sort'] : 'name';

$order = (isset($_GET['order']) && $_GET['order'] === 'desc') ? 'DESC' : 'ASC';


$perPage = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;

$offset = $page * $perPage;

$query = $dbh->prepare("SELECT COUNT(*) FROM listings");
$query->execute();
$total = $query->fetchColumn();
$totalPages = ceil($total / $perPage);



$sql = "SELECT * FROM listings ORDER BY $sort $order LIMIT :offset, :perpage";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AirBNB</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>AirBNB</h1>


<h2>Ajouter une nouvelle annonce</h2>
<form method="POST" class="form-section">

    Nom : <input type="text" name="name" required><br><br>

    URL Image : <input type="text" name="picture_url" required><br><br>

    Prix : <input type="number" name="price" required><br><br>

    Quartier : <input type="text" name="neigh" required><br><br>

    Score : <input type="number" name="score" min="0" max="5" required><br><br>

    URL Photo hôte : <input type="text" name="host_picture" required><br><br>

    Nom hôte : <input type="text" name="host_name" required><br><br>

    <button type="submit" name="add">Ajouter</button>

</form>

<form method="GET" class="form-section">
    <label>Trier par :</label>

    <select name="sort">
        <option value="name" 
            <?php if ($sort=='name') echo 'selected'; ?>>Nom</option>

        <option value="price" 
            <?php if ($sort=='price') echo 'selected'; ?>>Prix</option>

        <option value="neighbourhood_group_cleansed" 
            <?php if ($sort=='neighbourhood_group_cleansed') echo 'selected'; ?>>
            Quartier
        </option>

        <option value="review_scores_value" 
            <?php if ($sort=='review_scores_value') echo 'selected'; ?>>
            Score
        </option>
    </select>

    <select name="order">
        <option value="asc" <?php if ($order=='ASC') echo 'selected'; ?>>Croissant</option>
        <option value="desc" <?php if ($order=='DESC') echo 'selected'; ?>>Décroissant</option>
    </select>

    <input type="hidden" name="page" value="<?php echo $page; ?>">

    <button type="submit">Trier</button>
</form>

<br><hr><br>


<?php foreach ($data as $d): ?>
    <div style="margin-bottom:20px;" class="listing">
        <img src="<?php echo htmlspecialchars($d['picture_url']); ?>"
             width="300" height="200">
        <h2><?php echo htmlspecialchars($d['name']); ?></h2>
        <p>
            <?php echo $d['price']; ?> € / nuit —  
            <?php echo htmlspecialchars($d['neighbourhood_group_cleansed']); ?>  
            — <?php echo $d['review_scores_value']; ?>/5
        </p>
        <div class="host-info">
            <img src="<?php echo htmlspecialchars($d['host_thumbnail_url']); ?>"
                 width="50" height="50">
            <span><?php echo htmlspecialchars($d['host_name']); ?></span>
        </div>

    </div>
    <hr>
<?php endforeach; ?>

<div style="margin-top:20px;" class="pagination">
    <?php for ($p = 0; $p < $totalPages; $p++): ?>
        <a href="?page=<?php echo $p; ?>&sort=<?php echo $sort; ?>&order=<?php echo strtolower($order); ?>">
            <button <?php if ($p == $page) echo "disabled"; ?>>
                Page <?php echo $p + 1; ?>
            </button>
        </a>
    <?php endfor; ?>
</div>

</body>
</html>
