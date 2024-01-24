<?php
 if(isset($_GET["id"])){
        try{
            include_once("includes/conn.php");
        
            $id = $_GET["id"];
            $sql = "DELETE FROM `testimonials` WHERE id=?" ;
            $stmt = $conn-> prepare($sql);
            $stmt-> execute([$id]);

            echo " Testimonial deleted";

        }catch(PDOException $e){
        echo "Connection Failed ". $e->getMessage();
        }
 }

?>