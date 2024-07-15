


<?php
session_start();

include('dbconnection.php');

if (strlen($_SESSION['id']) == 0 || $_SESSION['user_type_id'] != 2) {
    header('location:/fyp_ccms/index.php');
    exit();
} else {
    
    $status = '';

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $contact_number = $_POST['contact_number'];
        $cnic = $_POST['cnic'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $designation_id = $_POST['designation_id'];
        $password = $_POST['password'];
        $user_type_id = 4;
        $id = $_SESSION["id"];

        // Handle file upload
        if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
            $target_dir = "user_image/";
            $target_file = $target_dir . basename($_FILES["user_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["user_image"]["tmp_name"]);

            if ($check !== false) {
                // Allow certain file formats
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    // Save the file
                    if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
                        // Insert new user into database with image
                        $sql = mysqli_query($con, "INSERT INTO user_detail (name, contact_number, cnic, address, email, username, designation_id, user_type_id, user_image, password) VALUES ('$name', '$contact_number', '$cnic', '$address', '$email', '$username', '$designation_id', '$user_type_id', '$target_file', '$password')");
                        if ($sql) {
                            $status = 'success';
                        } else {
                            $status = 'error';
                        }
                    } else {
                        $status = 'error';
                    }
                } else {
                    $status = 'error';
                }
            } else {
                $status = 'error';
            }
        } else {
            // Insert new user into database without image
            $sql = mysqli_query($con, "INSERT INTO user_detail (name, contact_number, cnic, address, email, username, designation_id, user_type_id, password) VALUES ('$name', '$contact_number', '$cnic', '$address', '$email', '$username', '$designation_id', '$user_type_id', '$password')");
            if ($sql) {
                $status = 'success';
            } else {
                $status = 'error';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCMS | Add Customer</title>
    <link href="Watch-Guard-Tech-Logo.png" rel="icon">
    <style>


                        /* ---------------------------FOR MAIN CONTENT TO BE WRAPPED WHEN SIDEBAR EXPANDS -----------------*/

                        /* CSS for Main Content */
                        .main-content {
                            margin-left: 260px; /* Same as the sidebar width */
                            margin-top: 10px;
                            background-color: #f4ebe8; /* Warm Beige */
                            transition: margin-left 0.5s; /* Smooth transition when sidebar is toggled */
                        }

                        .sidebar.close ~ .main-content {
                            margin-left: 80px; /* Adjusted width when sidebar is closed */
                        }

                        /* -------------------------------- FOR FOOTER TO BE AT THE BOTTOM ---------------------------------------*/

                        .main-content {    
                            display: flex; /* Enable flexbox layout */
                            flex-direction: column; /* Stack items vertically */
                            min-height: 76vh; /* Ensure body covers full viewport height */
                            flex: 1; /* Allow content to grow and take up remaining space */
                        }
                        
                        .footer {
                            position: relative; /* Makes footer act as a relative element */
                            bottom: 0; /* Places footer at the bottom */
                            width: 100%; /* Stretches the footer across the entire width */
                            margin-top: 20px;
                        }

                        /* -------------------------------- FOR BAR ---------------------------------------*/
  
                        .barr {
                            /* max-width: 1200px; */
                            /* width: 100%; */
                            margin: 20px;
                            padding: 10px;
                            display: flex;
                            justify-content: space-between;
                            align-items: center; 
                            background-color: #ecf0f1;
                            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
                            border-radius: 5px;    
                            font-size: 16px;
                        }
                        
                        .barr h1 a {
                            margin-left: 15px;
                            font-size: 28px;
                            color: #2c3e50; /* Secondary Color: Navy Blue */
                        }

                        .barr h1 a:hover {
                            text-decoration: none;
                        }
                        
                        .barr nav a {
                            color: #f39c12; /* Accent Color: Gold */
                            text-decoration: none;
                        }
                        
                        .barr nav span {
                            color: #2f3640; /* Text Color: Dark Slate Gray */
                            margin-right: 20px;
                        }
                        
                        .barr nav a:hover {
                            text-decoration: none;
                        }

                        /* -------------------------------- FOR FORM ---------------------------------------*/

                        .formcontainer {
                            background-color: #ecf0f1; /* Neutral Color: Light Gray */
                            padding: 30px;
                            padding-right: 20px;
                            border-radius: 10px;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            /* width: 1000px;  width to accommodate two columns */
                            width: calc(100% - 260px); /* Dynamic width to accommodate two columns */
                            margin: 0 auto;
                            margin-left: 130px;
                            margin-bottom: 20px;
                            margin-top: 20px;
                            transition: width 0.5s;
                        }

                        .formcontainer h2 {
                            color: #2c3e50; /* Secondary Color: Navy Blue */
                            text-align: center;
                            margin-bottom: 20px;
                        }

                        .form-group {
                            margin-bottom: 15px;
                            margin-left: 5px;
                            margin-right: 5px;
                            display: inline-block;
                            width: 48%; /* Adjust width for two columns */
                            vertical-align: top; /* Align top for both columns */
                        }

                        .form-group input[type="email"],
                        .form-group input[type="tel"],
                        .form-group input[type="text"],
                        .form-group input[type="number"],
                        .form-group input[type="password"] {
                            width: 100%;
                            padding: 10px;
                            margin: 5px 0;
                            border: 1px solid #ccc;
                            border-radius: 5px;
                            box-sizing: border-box;
                        }

                        .form-group label {
                            font-weight: bold;
                            color: #2c3e50; /* Secondary Color: Navy Blue */
                            display: block; /* Ensure labels are block elements */
                        }

                        .form-group a {
                            color: #2c3e50; /* Secondary Color: Navy Blue */
                            text-decoration: none;
                            display: inline-block;
                            margin-top: 10px;
                        }

                        .form-group a:hover {
                            text-decoration: underline;
                        }

                        .form-group .checkbox label {
                            display: inline-block;
                        }

                        .btn {
                            background-color: #f39c12; /* Accent Color: Gold */
                            color: #2c3e50; /* Secondary Color: Navy Blue */
                            border: none;
                            padding: 15px;
                            margin: 10px;
                            margin-left: -5px;
                            width: 100%;
                            border-radius: 5px;
                            cursor: pointer;
                            font-weight: bold;
                        }

                        .btn:hover {
                            background-color: #e08e0b; /* Darker Gold on hover */
                        }

                        .alertdanger {
                            color: red;
                            text-align: left;
                            margin-bottom: 10px;
                        }

                        .alertsuccess {
                            color: green;
                            text-align: left;
                            margin-bottom: 10px;
                        }

   
    </style> 


</head>

<body>

    <?php include_once('sidebar&header.php'); ?>

    <div class="main-content">
        <div class="barr">
            <h1><a href="add_customer.php">Add Customer</a></h1>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <strong>/</strong>
                <span>Add Customer</span>
            </nav>
        </div>

        <div class="formcontainer">
            <h2>Add Customer</h2>
            <hr>

            <?php
            if ($status == 'success') {
                echo "<div class='alertsuccess'>Customer Added Successfully.</div>";
            } elseif ($status == 'error') {
                echo "<div class='alertdanger'>Error Adding Customer.</div>";
            }
            ?>

            <form method="post" enctype="multipart/form-data">

                <div class="form-group" >                    
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>               
                </div>
                <div class="form-group" >                    
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>               
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="cnic">CNIC</label>
                    <input type="tel" name="cnic" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="tel" name="contact_number" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control">
                    


                    


                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="form-group">
                    <label for="designation_id">Designation</label>
                    <select name="designation_id" class="form-control">
                        <option value="1">Chief Administrator</option>
                        <option value="2">System Administrator</option>
                        <option value="3">Deputy IT Manager</option>
                        <option value="4">Junior IT Administrator</option>
                        <option value="5">Branch Manager</option>
                        <option value="6">Operations Manager</option>
                        <option value="7">Field Service Technician</option>
                        <option value="8">Maintenance Technician</option>
                        <option value="9">Electrical Technician</option>
                        <option value="10">Mechanical Technician</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user_image">Profile Picture</label>
                    <input type="file" name="user_image" class="form-control">
                </div>

                
                <button type="submit" class="btn" name="submit" style="background-color: #f39c12;">Add Technician</button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    </script>

    <div class="footer">
        <?php include('footer.php'); ?>
    </div>

</body>

</html>
