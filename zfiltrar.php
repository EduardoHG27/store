<?php include 'includes/session.php'; ?>
<?php 
include("keys.php");
?>
<?php 
// Include configuration file  

$id = $_POST['select'];



if(isset($_GET['category']))
{
    $slug = $_GET['category'];
}
else
{
    $slug=$_SESSION['slug'];
}



if($id=='1')
{
    $_SESSION['FILTRO']='price DESC';
    header('location: category1.php?category='.$slug.'&page=1');

}
else if($id=='2')
{
$_SESSION['FILTRO']='price ASC';
header('location: category1.php?category='.$slug.'&page=1');
}
else
{
    $_SESSION['FILTRO']='existences DESC';
    header('location: category1.php?category='.$slug.'&page=1');
}





?>
