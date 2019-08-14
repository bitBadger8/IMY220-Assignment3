<html>
    <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Kevin du Preez">    
</head>

    <body class="container">
       <?php
            $servername = "localhost";
            $username   = "kevin";
            $password   = "8888";
            $dbname     = "dbUser";
            $connection = new mysqli($servername, $username, $password, $dbname);

            if($connection->connect_error){
                die("Connection to database failed: " . $connection->connect_error);
            }

            $sql = "SELECT * FROM tbUsers WHERE email = '".$_POST["loginName"]."'";
           
            $result = $connection->query($sql);

            if($result->num_rows == 1){
                $result = $result->fetch_assoc();
                // while($row = $result->fetch_assoc()){
                //    echo "name: ". $row["name"] . "<br>";
                //     echo "surname: ". $row["surname"] . "<br>";
                //     echo "email: ". $row["email"] . "<br>";
                //     echo "birthday: ". $row["birthday"] . "<br>";
                // }
                
                ?>
                <table class = "table table-bordered">
                    <thread>
                        <tr>
                            <td>Name</td>
                            <td><?php echo $result["name"] ?></td>
                        </tr>
                        <tr>
                            <td>Surname</td>
                            <td><?php echo $result["surname"] ?></td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td><?php echo $result["email"] ?></td>
                        </tr>
                        <tr>
                            <td>Birthday</td>
                            <td><?php echo $result["birthday"]; 
                                            $userID = $result["user_id"];?></td>
                        </tr>
                    </thread>
                </table>
                

                <?php



                    
                echo 	
                "<form action='login.php' method='POST' enctype='multipart/form-data'> 
                    <div class='form-group'>
                        <input type='file' class='form-control' name='picToUpload[]' id='picToUpload' /><br/>
                        <input type='hidden' id='loginEmail' name='loginName' value='" . $result['email']."'>
                        <input type='hidden' id='loginPass' name='loginPassw' value='" . $result['password']."'>
                        
                        
                        <input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
                    </div>
                </form>";
                    
            }else{
                echo '<div class="alert alert-danger" style = "margin-top: 1rem">
                        You are not registered on this site!
                     </div>';
            }
            ?>
                        <?php                
            
            
            $target_dir = "gallery/";
            $target_file = $target_dir . basename($_FILES["picToUpload"]["name"][0]);            
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                // var_dump($_FILES['picToUpload']);
                // echo $_FILES['picToUpload']['name'][0] . "<br>";
                // echo $_FILES['picToUpload']['type'][0]. "<br>";
                // echo $_FILES['picToUpload']['tmp_name'][0]. "<br>";
                // echo $_FILES['picToUpload']['error'][0]. "<br>";
                // echo $_FILES['picToUpload']['size'][0]. "<br>";
                
                $typeCheck = 1;
                $sizeCheck = 1;
                //check type:
                if(isset($_FILES['picToUpload']) && $_FILES['picToUpload']['type'][0] === 'image/jpeg'){
                    $typeCheck = 0;
                    // error_log("Type Okay");
                }
                else{
                    $typeCheck = 1;
                    // error_log("Type Error");
                }
                //check size:
                if(isset($_FILES['picToUpload']) && $_FILES['picToUpload']['size'][0] <= 1048576){
                    $sizeCheck = 0;
                    // error_log("Size Okay");
                }
                else{
                    $sizeCheck = 1;
                    // error_log("Size Error");
                }
                
                if($typeCheck == 0 && $sizeCheck == 0){
                    
                    if (move_uploaded_file($_FILES["picToUpload"]["tmp_name"][0], $target_file)) {
                        // echo "The file ". basename( $_FILES["fileToUpload"]["name"][0]). " has been uploaded.";
                        //Link to db:
                                
                        // echo "<h3>".basename( $_FILES["picToUpload"]["name"][0])."</h3>";
                        $sql = "INSERT INTO tbgallery (user_id, filename)
                        VALUES ('".$userID."', '".basename( $_FILES["picToUpload"]["name"][0])."')";
                        // echo $sql;
                        
                        if($connection->query($sql) === TRUE){
                            // error_log("Added to database");
                        }
                    } 
                }
                
            }
               
       ?>
            <h3>Image Gallery</h3> 
                <div class = "row imageGallery">
                    <?php 
                        $sql = "SELECT filename FROM tbgallery WHERE user_id = '".$userID."'";
                        // echo $sql;
                        $picNames = $connection->query($sql);
                        if ($picNames->num_rows > 0) {
                            // output data of each row
                            while($row = $picNames->fetch_assoc()) {
                                // echo "image: " . $row["filename"]."<br>";
                                echo "<div class='col-3' style='background-image: url(gallery/".$row["filename"].")'></div>";
                            }
                        } else {
                            // echo "0 results";
                        }
                    
                    
                    ?>
                </div>   

    </body>
</html>