<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Vulnerability</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin-top: 50px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Upload a File</h1>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Select file to upload:</label>
                <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="submit">Upload File</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             
            $servername = "localhost";
            $username = "vulnuser";
            $password = "password123";
            $dbname = "file_upload_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".<br>";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.<br>";
                    $uploadOk = 0;
                }
            }

            if (file_exists($target_file)) {
                echo "Sorry, file already exists.<br>";
                $uploadOk = 0;
            }

            
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.<br>";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
                $uploadOk = 0;
            }

          
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.<br>";
           
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.<br>";

                    
                    $stmt = $conn->prepare("INSERT INTO uploads (filename, filepath) VALUES (?, ?)");
                    $stmt->bind_param("ss", $filename, $filepath);

                    $filename = basename($_FILES["fileToUpload"]["name"]);
                    $filepath = $target_file;

                    if ($stmt->execute()) {
                        echo "File metadata stored in database.<br>";
                    } else {
                        echo "Error: " . $stmt->error . "<br>";
                    }

                    $stmt->close();
                } else {
                    echo "Sorry, there was an error uploading your file.<br>";
                }
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
