
				
				
	        
							<?php

							include 'includes/session.php';
							$output = array('list'=>'');
							$results_per_page = 3;
							$conn = $pdo->open();
						
							$output['list'] .= '
							<div class="row">
							<div class="col-md-9">
							';

							try {

								$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid");
								$stmt->execute(['catid' => $_SESSION['catid']]);
								$number_of_results = $stmt->rowCount();
								$number_of_pages = ceil($number_of_results / $results_per_page);

								if (!isset($_GET['page'])) {
									$page = 1;
								} else {
									$page = $_GET['page'];
								}
								$this_page_first_result = ($page - 1) * $results_per_page;


								$inc = 3;
								$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid ORDER BY ".$_SESSION['FILTRO']." limit " . $this_page_first_result . "," . $results_per_page);
								$stmt->execute(['catid' => $_SESSION['catid']]);

								foreach ($stmt as $row) {
									$image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
									$inc = ($inc == 3) ? 1 : $inc + 1;
									if ($inc == 1) $output['list'] .="<div class='row'>";
									$output['list'] .= "<div class='col-lg-4 col-md-6'>
		       								<div class='container'>
		       									<img src='" . $image . "' width='100%' height='100%' class='thumbnail'>
		       									<h5><a href='product1.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></h5>
		       								</div>
		       								<div class='container'>
											   <b><del>&#36; " . number_format($row['price']+500, 2) . "<del></b><br>
		       									<b>&#36; " . number_format($row['price'], 2) . "</b>
		       								</div>
	       								</div>";
									if ($inc == 3) $output['list'] .= "</div>";
								}
								if ($inc == 1) $output['list'] .= "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
								if ($inc == 2) $output['list'] .= "<div class='col-sm-4'></div></div>";

								$output['list'] .='
								<div class="container">
								<br>							
								<ul class="pagination">';
								for ($page = 1; $page <= $number_of_pages; $page++) {

									if(isset($_GET['page']))
									{
									if($page == $_GET['page'])
									{
										$output['list'] .= '
										<li class="page-item active">
										<a class="page-link" href="category1.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
										</li>
											';
									}
									else
									{
										$output['list'] .='
										<li class="page-item">
										<a class="page-link" href="category1.php?category=' . $slug . '&page=' . $page . '">' . $page . '</a> 
										</li>
								';
									}
								}
								else
								{
									$output['list'] .= '
									<li>
									<a class="page-item" href="category1.php?category=&page=' . $page . '">' . $page . '</a> 
									</li>
							';
								}
								
								}
								$output['list'] .= '</ul>
								</div>
								</nav>';
							} catch (PDOException $e) {
								$output['list'] .= "There is some problem in connection: " . $e->getMessage();
							}
							
							$pdo->close();

							$output['list'] .= '</div>
							<div class="col-md-3">';
					 include 'includes/sidebar_1.php';
					 $output['list'] .= '</div>
											</div>';
							
							echo json_encode($output);
							?>