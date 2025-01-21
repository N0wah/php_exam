<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_exam";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all articles for sale, ordered by publish_date descending
$sql = "SELECT article.*, user.username FROM article JOIN user ON article.id_author = user.id ORDER BY publish_date DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>N/A Company</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="./logofonblanc.png">


</head>
<body>
    <nav>
    <div class="menu">
        <a href="home.php" class="logo">
            <img src="./Logo.png" alt="">
        </a>
        <div class="navigation">
            <ul>
                <li><a href="#accueil">Accueil</a></li>
                <li><a href="#produit">Produit</a></li>
                <li><a href="./sell.php">Vendre</a></li>
            </ul>
            <div class="profile-section"><div class="profile-page"><a href="account.php"><span class="material-symbols-outlined">
person
</span></a></div>
            <div class="panier"><a href="cart.php"><span class="material-symbols-outlined">
shopping_basket
</span></a></div></div>
            
        </div>
    </div>
    </nav>
    <main>
    <section class="sec_1" id="accueil">
        <div class="photo1er">
            <img src="./model1.jpg" alt="">
            <div class="lecture">
            <h1>Revendez, dénichez et donnez </h1>
            <p>une seconde vie à vos vêtements préférés <br> la mode entre particuliers n’a jamais été aussi simple !</p>
            <button>Découvrez</button>
            </div>
            
        </div>


        <div class="photo3eme">
            <div class="photo">

            
                <img src="./montre1.jpg" id="photo1" alt=""><div class="lecture2">
                <p>Montre de<br>
                Collection</p>
                <button>Découvrez</button>
                </div>
            </div>
            <div class="photo">
                
            
                <img src="./chaussure1.jpg" id="photo2" alt=""><div class="lecture2">
                <p>Chaussure Édition <br>LIMITÉ</p>
                <button>Découvrez</button>
                </div>
            </div>
             
            <div class="photo">
                
                <img src="./sac1.png" id="photo3" alt="">
                <div class="lecture2">
                <p>Sac de Luxe <br> </p>
                <button id="b-photo3">Découvrez</button>
                </div>
            </div>
            </div> 
        </div>
    </section>
    <h2 id="Titre">Les Produits de nos Utilisateurs</h2>
    <hr id="line1">
    <div class="container" id="produit">
        <div class="boxcentrer">
        <?php if ($result->num_rows > 0): ?>
            <?php while($article = $result->fetch_assoc()): ?>
                <div id="box">      
                    <div class="insidebox">
                    <?php if ($article['img_link']): ?>
                        <img src="<?php echo htmlspecialchars($article['img_link']); ?>" alt="Article Image" style="max-width: 200px;">
                    <?php endif; ?>
                    </div>   
                <h3><?php echo htmlspecialchars($article['name']); ?></a></h3>
                    <p><?php echo htmlspecialchars($article['description']); ?></p>
                    <p><?php echo htmlspecialchars($article['price']); ?> €</p> 
                    <a href="detail.php?id=<?php echo htmlspecialchars($article['id']); ?>"><p>Détails</p></a>  
                </div>  
                        
            <?php endwhile; ?>
        <?php else: ?>
            <p>No articles for sale.</p>
        <?php endif; ?>
        </div>
    </div>
    </main>
    <footer class="footer">
    <div class="footer-container">
                <div class="footer-section social">
            <h2>Suivez-nous</h2>
            <div class="social-icons">
                <div><a href="#"><i class="fab fa-facebook-f"></i></a>
</div>                <div><a href="#"><i class="fab fa-twitter"></i></a>
   </div>             <div><a href="#"><i class="fab fa-instagram"></i></a>
 </div>               <div><a href="#"><i class="fab fa-linkedin-in"></i></a></div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 NA Company. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>