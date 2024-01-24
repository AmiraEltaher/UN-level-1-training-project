<?php
include_once("includes/logged.php");
include_once("includes/conn.php");

$status = false;

if(isset($_GET["id"])){
	$id=$_GET["id"];
	$status = true;
}else{
	$id=$_POST["id"];
	$title=$_POST["title"];
	$price=$_POST["price"];
	$model=$_POST["model"];
	$content=$_POST["content"];
	$auto=$_POST["auto"];
	$properties=$_POST["properties"];
	
	if (isset($_POST["Published"])){
		$published=1;
	}
	else{
        $published=0;
	}
    $oldImage=$_POST["oldImage"];
	include_once("includes/updateImage.php");
     
	$sql = "UPDATE `cars` SET `title`=? ,`content`=?,`price`=?,`model`=?,`auto`=?,`properties`=?,`image`=?,`Published`=? WHERE id=?";
	$stmt = $conn-> prepare($sql);
	$stmt-> execute([$title, $content, $price, $model, $auto,  $properties,$image_name, $published,$id]);

}
if($status)
{
	try{
	

		$sql = "SELECT * FROM `cars` WHERE id=?";
		$stmt = $conn-> prepare($sql);
		$stmt-> execute([$id]);
	
		$row =$stmt->fetch();
		$title = $row["title"];
		$content = $row["content"];
		$price = $row["price"];
		$model = $row["model"];
		$Category_id = $row["Category_id"];
		$id = $row["id"];
		$auto = $row["auto"];
	
		if($auto){
			$manual="";
			$automatic="Selected";
		}
		else{
			$manual="Selected";
			$automatic="";
		}
	
		$published = $row["Published"];
	
		if($published){
			
			$strPublished="checked";
		}
		else{
			
			$strPublished="";
		}
	
		$properties = $row["properties"];
		$image = $row["image"];
	
		}
		catch(PDOException $e){
		echo "Connection failed: " .$e->getMessage();
		}
}


	$sql = "SELECT * FROM `category`";
	$stmtCategory = $conn-> prepare($sql);
	$stmtCategory-> execute();


?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Edit / Update Car</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" class="m-auto" style="max-width:600px" enctype="multipart/form-data">
				<h3 class="my-4">Edit / Update Car</h3>
				<hr class="my-4" />
				<div class="form-group mb-3 row"><label for="title2" class="col-md-5 col-form-label">Car Title</label>
					<div class="col-md-7"><input type="text" value="<?php echo $title ?>" class="form-control form-control-lg" id="title2" name="title" required></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="content4" class="col-md-5 col-form-label">Content</label>
					<div class="col-md-7"><textarea class="form-control form-control-lg" id="content4" name="content" required><?php echo $content ?></textarea></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="price6" class="col-md-5 col-form-label">Price</label>
					<div class="col-md-7"><input type="text" value="<?php echo $price ?>" class="form-control form-control-lg" id="price6" name="price"></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="model6" class="col-md-5 col-form-label">Model</label>
					<div class="col-md-7"><input type="text" value="<?php echo $model ?>" class="form-control form-control-lg" id="model6" name="model"></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="select-option1" class="col-md-5 col-form-label">Auto / Manual</label>
					<div class="col-md-7"><select class="form-select custom-select custom-select-lg" id="select-option1" name="auto">
							<option value="1" <?php echo $automatic?>>Auto</option>
							<option value="0" <?php echo $manual?>>Manual</option>
							
						</select></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="properties6" class="col-md-5 col-form-label">Properties</label>
					<div class="col-md-7"><input type="text" value="<?php echo $properties ?>" class="form-control form-control-lg" id="properties6" name="properties"></div>
				</div>
				<hr class="my-4" />
				<div>
					<label for="image" class="col-md-5 col-form-label">Select Image</label>
					<input type="file" id="image" name="image" accept="image/*">
					<img src="../img/<?php echo $image ?>" alt="<?php echo $title ?>" style="width:300px;">
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="model7" class="col-md-5 col-form-label">Published</label>
					<div class="col-md-7"><input type="checkbox"  id="model7" name="Published" <?php echo $strPublished ?>></div>
				</div>

				<input type="hidden" name="id"  value= "<?php echo $id?>">
				<input type="hidden" name="oldImage" value= "<?php echo $image?>">



				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="select-option1" class="col-md-5 col-form-label">Category</label>
					<div class="col-md-7">
						<select class="form-select custom-select custom-select-lg" id="select-option1" name="category" >
							<option value="">Select Category</option>
							<?php
								foreach($stmtCategory->fetchAll() as $row){
								$categoryName = $row["categoryName"];
								$categoryId =    $row["id"];
								if($categoryId == $Category_id){
									$selected="selected";
								}
								else{
									$selected="";
								}							
							?>
							<option value="<?php echo $categoryId?>"  <?php echo $selected?> ><?php echo $categoryName ?></option>
							<?php
								}
							?>							
						</select>
					</div>
				</div>

				<hr class="my-4" />
				<div class="form-group mb-3 row"><label for="insert10" class="col-md-5 col-form-label"></label>
					<div class="col-md-7"><button class="btn btn-primary btn-lg" type="submit">Update</button></div>
				</div>
			</form>
		</div>
	</body>

</html>