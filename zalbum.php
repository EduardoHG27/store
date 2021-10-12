

<?php 
$conn = $pdo->open();

$stmt = $conn->prepare("SELECT *,c.name as categoria,p.name as articulo FROM `products` p inner join category c on(c.id=p.category_id) order by existences ASC limit 9");
$stmt->execute();

foreach ($stmt as $row) {
    # code...
    echo '
<div class="col-lg-4 col-md-6 portfolio-item filter-'. $row['cat_slug'] .'">
    <img src="images/'. $row['photo'] .'" class="img-fluid" alt="">
    <div class="portfolio-info">
        <h4 class="page-header"><a href="category1.php?category='. $row['cat_slug'] .'&page=1">'. $row['categoria'] .'</a></h4>
        <p>'. $row['articulo'] .'</p>
        <b><del>&#36;'. $row['price'].'<del></b><br>
        <b>&#36;'. $row['price'] .'</b>
        <a href="images/'. $row['photo'] .'" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="'. $row['articulo'] .'"><i class="bx bx-plus"></i></a>
        <a href="/ecommerce_original_1/product1.php?product='. $row['slug'] .'" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
    </div>
</div>
';
}



?>
