<?php
require_once "connection.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Username cannot be blank";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken";
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


    // Check for password
    if (empty(trim($_POST['password']))) {
        $password_err = "Password cannot be blank";
    } elseif (strlen(trim($_POST['password'])) < 5) {
        $password_err = "Password cannot be less than 5 characters";
    } else {
        $password = trim($_POST['password']);
    }

    // Check for confirm password field
    if (trim($_POST['password']) !=  trim($_POST['confirm_password'])) {
        $password_err = "Passwords should match";
    }


    // If there were no errors, go ahead and insert into the database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $email = $_POST['email'];

        $sql = "INSERT INTO `users`(`username`, `email`, `password`) VALUES (?,'$email',?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set these parameters
            // if (isset($_POST['register'])) {
            //     $name = $_POST['name'];
            //     $email = $_POST['email'];
            //     $phonenumber = $_POST['phonenumber'];
            //     $gender = $_POST['gender'];
            //     $countrycode = $_POST['countrycode'];
            //     $sql1 = "INSERT INTO users (name, email, phonenumber, gender, countrycode) VALUES ('$name','$email','$phonenumber','$gender','$countrycode')";
            //     $a = mysqli_query($conn, $sql1);

                
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            // Try to execute the query
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "Something went wrong... cannot redirect!";
            }
            
            
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="input.css">
    <title>Markai</title>
</head>
<body style="font-family: 'Poppins', sans-serif;" class="bg-[#0F0B30]">
<!-- navbar -->
    <div class="w-full h-[70px] flex items-center justify-center px-6 fixed navbar">
        <div class="w-full h-full sm:w-[500px] md:w-[720px] lg:w-[950px] xl:w-[1280px] flex items-center justify-between">
          <p class="text-[24px] text-white font-bold font-sans cursor-pointer">Markai</p>
          <ul class="text-white hidden lg:flex">
            <li class="font-normal mx-4 text-[14px]"><a href="">Features</a></li>
            <li class="font-normal mx-4 text-[14px]"><a href="">Products</a></li>
            <li class="font-normal mx-4 text-[14px]"><a href="">Company</a></li>
            <li class="font-normal mx-4 text-[14px]"><a href="">Pricing</a></li>
            <li class="font-normal mx-4 text-[14px]"><a href="">Support</a></li>
          </ul>
          <div class="items-center text-white hidden lg:flex">
            <div class="mx-2 px-2 py-1 text-[14px] font-bold"><a href="<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {echo 'Logout';}else{echo 'Login';}?>.php"><?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {echo "Logout";}else{echo "Login";}?></a></div>
            <div class="mx-2 p-[1px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A]">
                <div class="px-4 py-1 bg-[#7f0782] hover:bg-transparent hover:transition-colors rounded-full cursor-pointer">
                <a href="register.php">Register</a>
                </div>
            </div>
          </div>
          <div class="w-[30px] h-[30px] menu_icon lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list fill-slate-200" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
              </svg>
          </div>
        </div>
    </div>

    <!-- mobile menu  -->
    <div class="w-full bg-black/40 backdrop-blur-sm h-full top-[70px] left-[-100%] fixed z-2 p-4 mobile_menu">
        <ul class="text-white text-center">
            <li class="font-normal  text-[18px] my-2 close_icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x " viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
              </svg>
            </li>
            <li class="font-normal  text-[18px] my-2"><a href="">Features</a></li>
            <li class="font-normal  text-[18px] my-2"><a href="">Products</a></li>
            <li class="font-normal  text-[18px] my-2"><a href="">Company</a></li>
            <li class="font-normal  text-[18px] my-2"><a href="">Pricing</a></li>
            <li class="font-normal  text-[18px] my-2"><a href="">Support</a></li>
            <div class=" px-2 py-1 text-[14px] font-bold text-center mb-2"><a href="<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {echo 'Logout';}else{echo 'Login';}?>.php"><?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {echo "Logout";}else{echo "Login";}?></a></div>
            <div class=" p-[1px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A] text-center">
                <div class="px-4 py-1 bg-[#7f0782] hover:bg-transparent hover:transition-colors rounded-full cursor-pointer">
                <a href="register.php">Register</a>
                </div>
            </div>
          </ul>
    </div>

<!-- let's introduce yourself -->
<div class="w-full px-3 py-[100px] text-center">
    <div class="w-full h-full sm:w-[500px] md:w-[720px] lg:w-[950px]  m-auto">
        <h1 class="font-bold text-[35px] bg-gradient-to-r from-[#00F0FF] to-[#FF007A] " style="-webkit-text-fill-color:transparent; -webkit-background-clip:text;">Regsiter</h1>
        <p class="text-white max-w-[380px] m-auto text-[13px]">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas purus odio tempor rutrum</p>

        <form action="register.php" method="post">
            <div class="sm:flex mt-12">
                <div class="p-[2px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A] m-3   sm:w-1/2 hover:from-[#FF007A] hover:to-[#00F0FF] hover:transition-colors">
                <div class="py-3 px-5 bg-[#0F0B30] rounded-full text-white  text-[15px]">
                    <input type="text" class="bg-[#0F0B30] w-full outline-none" placeholder="Your Username" name="username">
                </div>
                </div>  
                <div class="p-[2px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A] m-3 sm:w-1/2 hover:from-[#FF007A] hover:to-[#00F0FF] hover:transition-colors">
                <div class="py-3 px-5 bg-[#0F0B30] rounded-full text-white  text-[15px]">
                    <input type="email" class="bg-[#0F0B30] w-full outline-none" placeholder="Your Email Address" name="email">
                </div>
                </div>  
            </div>

            <div class="sm:flex">
                <div class="p-[2px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A] m-3   sm:w-1/2 hover:from-[#FF007A] hover:to-[#00F0FF] hover:transition-colors">
                <div class="py-3 px-5 bg-[#0F0B30] rounded-full text-white  text-[15px]">
                    <input type="password" class="bg-[#0F0B30] w-full outline-none" placeholder="Enter your password" name="password">
                </div>
                </div>  
                <div class="p-[2px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A] m-3 sm:w-1/2 hover:from-[#FF007A] hover:to-[#00F0FF] hover:transition-colors">
                <div class="py-3 px-5 bg-[#0F0B30] rounded-full text-white  text-[15px]">
                    <input type="password" class="bg-[#0F0B30] w-full outline-none" placeholder="Confirm your password" name="confirm_password">
                </div>
                </div>  
            </div>
           
            <div class="p-[2px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A] w-[140px] m-auto mt-5">
                <div class="px-4 py-1 bg-[#0F0B30] hover:bg-transparent rounded-full text-slate-400 hover:text-[#0f0b30] m-auto w-[136px] text-[22px] text-center hover:transition-colors">
                <button name="regsiter">Submit</button>
                </div>
            </div>

        </form>
    </div> 
</div>

<footer class="px-4 py-[100px] bg-[#1b173a]">
    <div class="lg:flex sm:w-[500px] md:w-[700px] lg:w-[980px] m-auto justify-between">
        <div class="w-full lg:w-[400px] lg:pr-[100px]">
            <h1 class="font-bold text-[50px] w-[190px] bg-gradient-to-r from-[#00F0FF] to-[#FF007A] " style="-webkit-text-fill-color:transparent; -webkit-background-clip:text;">Markai</h1>
            <p class="text-white  text-[13px]">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas purus odio tempor rutrum</p>
            <div class=" p-[1px] text-[14px] font-bold rounded-full bg-gradient-to-r from-[#00F0FF] to-[#FF007A] w-[120px] mt-5 text-center">
                <div class="px-4 py-1 bg-[#1b173a] hover:bg-transparent hover:transition-colors rounded-full text-white w-[118px] text-[18px]">
                <a href="">Let's go</a>
                </div>
            </div>
            <div class="flex mt-5">
                 <img src="images/linkedin.png" alt="" class="mr-2 w-[30px] h-[30px]">  
                 <img src="images/facebook.png" alt="" class="w-[30px] h-[30px]">  
                 <img src="images/cb.png" alt="" class="mx-2 w-[30px] h-[30px]">  
                 <img src="images/instagram.png" alt="" class="w-[30px] h-[30px]">  
                 <img src="images/twitter.png" alt="" class="ml-2 w-[30px] h-[30px]">  
            </div>
        </div>
        <div class="w-full lg:w-[300px] mt-12 lg:mt-0">
              <div class="flex justify-between">
                <div class="">
                <p class="text-white text-[20px]">Site</p>
                <ul>
                    <li><a href=""><p class="text-white  text-[14px]">Features</p></a></li>
                    <li><a href=""><p class="text-white  text-[14px]">Products</p></a></li>
                    <li><a href=""><p class="text-white  text-[14px]">Company</p></a></li>
                    <li><a href=""><p class="text-white  text-[14px]">Pricing</p></a></li>
                    <li><a href=""><p class="text-white  text-[14px]">Support</p></a></li>
                </ul>
                </div>
                <div class="">
                    <p class="text-white  text-[20px]">Legale</p>
                    <ul>
                        <li><a href=""><p class="text-white  text-[14px]">Privacy Policy</p></a></li>
                        <li><a href=""><p class="text-white  text-[14px]">Terms & Conditions</p></a></li>
                        <li><a href=""><p class="text-white  text-[14px]">Company Policy</p></a></li>
                    </ul>
                </div>
              </div>
              <div class="flex justify-between mt-0  md:mt-6">
                <div class="">
                    <p class="text-white  text-[20px]">Company</p>
                    <ul>
                        <li><a href=""><p class="text-white  text-[14px]">About us</p></a></li>
                        <li><a href=""><p class="text-white  text-[14px]">Our team</p></a></li>
                        <li><a href=""><p class="text-white  text-[14px]">Our Story</p></a></li>
                        <li><a href=""><p class="text-white  text-[14px]">Career</p></a></li>
                    </ul>
                    </div>
                    <div class="pl-3 sm:pl-0">
                        <p class="text-white w-[155px] text-[20px]">Follow us on</p>
                        <ul>
                            <li><a href=""><p class="text-white  text-[14px]">Twitter</p></a></li>
                            <li><a href=""><p class="text-white  text-[14px]">Facebook</p></a></li>
                            <li><a href=""><p class="text-white  text-[14px]">Linkedin</p></a></li>
                        </ul>
                    </div>
              </div>
        </div>
    </div>
</footer>

<script>
   var navbar = document.getElementsByClassName("navbar")[0];

   window.addEventListener("scroll",function(){
        if(window.scrollY > 10)
        {
            navbar.style.top = "-70px";
            navbar.style.transition = "0.5s";
            navbar.classList.add('bg-white/20');
            navbar.classList.add('backdrop-blur-sm');
        }
        if(window.scrollY > 300)
        {
            navbar.style.top = "0px";
            navbar.style.transition = "0.5s";
            navbar.classList.add('bg-white/20');
            navbar.classList.add('backdrop-blur-sm');
        }
        if(window.scrollY < 10)
        {
            navbar.style.top = "0px";
            // navbar.style.transition = "0.5s";
            navbar.classList.remove('bg-white/20');
        }
   });
</script>
<script>
    var menu_icon = document.getElementsByClassName("menu_icon")[0];
    var close_icon = document.getElementsByClassName("close_icon")[0];
    var mobile_menu = document.getElementsByClassName("mobile_menu")[0];

    menu_icon.onclick = function(){
        mobile_menu.style.left = "0px";
        mobile_menu.style.transition = "0.4s";
    };
    close_icon.onclick = function(){
        mobile_menu.style.left = "-100%";
        mobile_menu.style.transition = "0.4s";
    };
</script>
</body>
</html>