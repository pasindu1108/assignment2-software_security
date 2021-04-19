<script>
    function myFunction() 
    {
       var x = document.getElementById("myDIV");
       if (x.style.display === "none") {
        x.style.display = "block";
            } else {
        x.style.display = "none";
        }
    }

  function reply_click(clicked_id)
  {
      
    if(clicked_id == '1'){

        document.getElementById("courseid").value = clicked_id;
        document.getElementById("Price").value = "RS.30000.00";
        document.getElementById("date").value = "Monday - Friday / 3 pm - 5 pm";
    } else if(clicked_id == '2'){
        document.getElementById("courseid").value = clicked_id;
        document.getElementById("Price").value = "RS.36000.00";
        document.getElementById("date").value = "Monday - Wednesday / 3 pm - 5 pm";
    } else if(clicked_id == '3'){
        document.getElementById("courseid").value = clicked_id;
        document.getElementById("Price").value = "RS.40000.00";
        document.getElementById("date").value = " Saturday - Sunday / 3 pm - 5 pm";
    }
  }
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$google_redirect_url = 'http://localhost:10080/auth/redirect.php';

//start session
session_start();

//include google api files
include_once 'lib/vendor/autoload.php';

// New Google client
$gClient = new Google_Client();
$gClient->setApplicationName('ApplicationName');
$gClient->setAuthConfigFile('client_secret_281893744936-mhu99ofaqcnmc4t67ca4spe9p0ec3eh3.apps.googleusercontent.com.json');
$gClient->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$gClient->addScope(Google_Service_Oauth2::USERINFO_EMAIL);

// New Google Service
$google_oauthV2 = new Google_Service_Oauth2($gClient);

// LOGOUT?
if (isset($_REQUEST['logout'])) 
{
	unset($_SESSION["auto"]);
	unset($_SESSION['token']);
	$gClient->revokeToken();
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL)); //redirect user back to page
}

// GOOGLE CALLBACK?
if (isset($_GET['code'])) 
{
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
    return;
}

// PAGE RELOAD?
if (isset($_SESSION['token'])) 
{
    $gClient->setAccessToken($_SESSION['token']);
}

// Autologin?
if(isset($_GET["auto"]))
{
	$_SESSION['auto'] = $_GET["auto"];
}

// LOGGED IN?
if ($gClient->getAccessToken()) // Sign in
{
	//For logged in user, get details from google using access token
	try {
		$user = $google_oauthV2->userinfo->get();
		$user_id              = $user['id'];
		$user_name            = filter_var($user['givenName'], FILTER_SANITIZE_SPECIAL_CHARS);
		$email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
		$gender               = filter_var($user['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
		$profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);
		$profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
		$personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";
		$_SESSION['token']    = $gClient->getAccessToken();
		
	
        //HTML code (View course details / Course registration / Assignment Submission)
        echo ' <section id="home" class="video-section js-height-full">
        <br /><br /><br /><br /><br /><a  style = "position:absolute; left:30px; top:200px;" href="?logout=1" class="btn btn-primary wow slideInLeft">Log Out</a><br />
        <div style = "position:absolute; left:30px; top:280px;border-style: dotted;background-color: black;">
        <a href="'.$profile_url.'" style = ""  target="_blank"><img src="'.$profile_image_url.'?sz=40" /></a><br /><br />
        <input type="text" value="Name - '.$user_name.'" disabled style= "width:270px; color: black; " /><br />
        <input type="text" value="Email - '.$email.'" disabled  style= "width:270px; color: black; "/>
        </div>


        <div style = "position:absolute; left:780px; top:540px;">
              <form action="submit.php" method="post" enctype="multipart/form-data" >
                   <label for="" style = "background-color: Black;">Upload Your Assignments</label>
                   <input type="file" name="file" >
                   <br>
                   <input style = "background-color: DodgerBlue;color: Black;" type="submit" name="submit" value="submit" >
              </form>
        </div>

        <div style = "position:absolute; left:780px; top:280px;">
              <button onclick="myFunction()" class="btn"  style = "background-color: DodgerBlue;"><i class="fa fa-bars"></i> Courses</button>
              <p> </p>
              <div id="myDIV" style="display:none">
                  <table class="data" cellpadding="8" border="1">
                         <thead>
                            <tr>
                                <th class="first" style="width:120px;color: Black;"> Course Name </th>
                                <th class="" style = "color: Black;"> Description </th>
                                <th class="" style = "color: Black;">Action </th>
                            </tr>
                        </thead>
                        <tbody>';
        
        //Check whether user is registered or not
        $db = mysqli_connect('localhost', 'root', '', 'test');
        $sql = "SELECT * FROM registrations WHERE email = '$email'";
        $result = mysqli_query($db, $sql);
        $rows = mysqli_num_rows($result);

        if ($rows > 0) {
        

        echo '
                            <tr>
                               <td class="" style = "color: Blue;" >Java Programming</td>
                               <td class="">Basic Programming about JAVA</td>
                               <td style = "height: 40px; width: 60px"> <button id="1" onClick="reply_click(this.id)" data-toggle="modal" data-target="#myModal2">View</button></td>
                            </tr>

                            <tr>
                               <td class=""  style = "color: Blue;">Project Management</td>
                               <td class="">Project Management Certification</td>
                               <td style = "height: 40px; width: 60px"> <button id="2" onClick="reply_click(this.id)" data-toggle="modal" data-target="#myModal2">View</button> </td>
                            </tr>

                            <tr>
                               <td class=""  style = "color: Blue;">Web Developing</td>
                               <td class="">Web Development</td>
                               <td style = "height: 40px; width: 60px"> <button id="3" onClick="reply_click(this.id)" data-toggle="modal" data-target="#myModal2">View</button> </td>
                            </tr>
        ';

          


         } else {
     


        echo '
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Registration</button>
        
                            <tr>
                                <td class=""  style = "color: Blue;">Java Programming</td>
                                <td class="">Basic Programming about JAVA</td>
                                <td style = "height: 40px; width: 60px"> <a href="">Register</a> </td>
                            </tr>

                            <tr>
                                <td class=""  style = "color: Blue;">Project Management</td>
                                <td class="">Project Management Certification</td>
                                <td style = "height: 40px; width: 60px"> <a href="">Register</a> </td>
                            </tr>

                            <tr>
                                <td class=""  style = "color: Blue;">Web Developing</td>
                                <td class="">Web Development</td>
                                <td style = "height: 40px; width: 60px"> <a href="">Register</a> </td>
                            </tr>
            ';
   
        }

        echo '
                           </tbody>
                      </table>
                    </div>
                 </div>
           </section>
           
        ';



       echo ' 
       
           <div class="modal fade" id="myModal" role="dialog">
               <div class="modal-dialog">

                   <!-- Modal content-->
                       <div class="modal-content">
                            <div class="modal-header">
                            </div>
  
                                <form method="post" id="coursematerials_data" name="coursematerials_data" >
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-body">
                           
                         

                                                <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">Email <span class="required">*</span></label>
                                                                <input required="true"
                                                                    type="text" name="email" id="email"
                                                                    class="form-control" value="'.$email.'" readonly>
                                                                <div class="help-block with-errors"></div>
                                                            </div>
                                                    </div>
                                                </div>

                                                               

                                                <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="">Name <span class="required">*</span></label>
                                                                <input required="true"
                                                                    type="text" name="name" id="name"
                                                                    class="form-control">
                                                                <div class="help-block with-errors"></div>
                                                            </div>
                                                    </div>
                                                </div>




                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group pull-right">
                                                            <button type="submit" class="btn btn-success" name="save" id="save">
                                                                <span class="glyphicon glyphicon-send"></span>&nbsp;&nbsp; Submit
                                                            </button> &nbsp;
                                                        
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>


                                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                 </div>
              </div>
        </div>
        
    ';



        echo ' 
        
                        <div class="modal fade" id="myModal2" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            </div>
  

                                                <form method="post" id="coursematerials_data2" name="coursematerials_data2" >
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-body">
                                                                    
                            

                                                                        <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group">
                                                                                        <label for="">Course ID <span class="required">*</span></label>
                                                                                        <input required="true"
                                                                                        type="text" name="courseid" id="courseid"
                                                                                        class="form-control" readonly>
                                                                                        <div class="help-block with-errors"></div>
                                                                                    </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <div class="form-group">
                                                                                        <label for="">Price <span class="required">*</span></label>
                                                                                        <input required="true"
                                                                                        type="text" name="Price" id="Price"
                                                                                         class="form-control" readonly>
                                                                                        <div class="help-block with-errors"></div>
                                                                                    </div>
                                                                                </div>
                                                                         </div>


                                                                       <div class="row">
                                                                                <div class="col-sm-12">
                                                                                     <div class="form-group">
                                                                                         <label for="">Date/Time <span class="required">*</span></label>
                                                                                         <input required="true"
                                                                                        type="text" name="date" id="date"
                                                                                         class="form-control" readonly>
                                                                                        <div class="help-block with-errors"></div>
                                                                                   </div>
                                                                               </div>
                                                                          </div>
                                                                                                            

                                                                        <div class="row">
                                                                                <div class="col-sm-12">
                                                                                   <div class="form-group pull-right">
                                                                                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                </a>
                                                                               </div>
                                                                               </div>
                                                                        </div>


                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                            </div>';



            //Save user details in the database
            $db = mysqli_connect('localhost', 'root', '', 'test');

                // initialize variables
                $name = "";
                $email = "";
                $id = 0;
                $update = false;

                if (isset($_POST['save'])) {
                    $name = $_POST['name'];
                    $email = $_POST['email'];

                    mysqli_query($db, "INSERT INTO registrations (email, name) VALUES ('$email', '$name')"); 
                    header('location: redirect.php');
                }

  
                } catch (Exception $e) {
                    // The user revoke the permission for this App. Therefore reset session token	
                    unset($_SESSION["auto"]);
                    unset($_SESSION['token']);
                    header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
                }
            }
            else // Sign up
            {
                //For Guest user, get google login url
                $authUrl = $gClient->createAuthUrl();
                
                // Fast access or manual login button?
                if(isset($_GET["auto"]))
                {
                    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                }
                else
                {

	

                    echo ' <section id="home" class="video-section js-height-full">
                    <!-- <div class="overlay"></div> -->
                    <div class="home-text-wrapper relative container">
                        <div class="home-message">
                        <a class="login" href="'.$authUrl.'"><img src="google3.jpg"  width="220" height="60"/></a>
                            <p>Learning Management System</p>
                            <small>Edulogy is the ideal choice for your organization, your business and your online education system. Create your online course now with unlimited page templates, color options, and menu features.</small>
                            <div class="btn-wrapper">
                                <div class="text-center">
                                
                                </div>
                            </div><!-- end row -->
                        </div>
                    </div>
                    <div class="slider-bottom">
                        <span>Explore <i class="fa fa-angle-down"></i></span>
                    </div>
                    </section>';
                            }
                    }
                    
?>





<!doctype html>
<!--[if IE 9]> <html class="no-js ie9 fixed-layout" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js " lang="en"> <!--<![endif]-->
<head>

<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVFIMCdeleRLHej6HI1i0-SEKMqq85B-g&callback=initMap&libraries=&v=weekly"
      async
></script>


<style type="text/css">
      
      #map {
        height: 400px;
        width: 100%;
      }
</style>
    
<script>
      // Initialize and add the map
    function initMap() {
        // The location of Uluru
        const uluru = { lat: 6.8649, lng: 79.8997 };
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 4,
          center: uluru,
        });
        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
          position: uluru,
          map: map,
        });
    }
</script>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Mobile Meta -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- Site Meta -->
    <title>Dev</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

	<!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700,900" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,400i,700,700i" rel="stylesheet"> 
	
    <!-- Custom & Default Styles -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/carousel.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="style.css">


</head>
<body>  

    <!-- LOADER -->
    <div id="preloader">
        <img class="preloader" src="images/loader.gif" alt="">
    </div><!-- end loader -->
    <!-- END LOADER -->

    <div id="wrapper">
        <!-- BEGIN # MODAL LOGIN -->
        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Begin # DIV Form -->
                    <div id="div-forms">
                        <form id="login-form">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="flaticon-add" aria-hidden="true"></span>
                            </button>
                            <div class="modal-body">
                                <input class="form-control" type="text" placeholder="What you are looking for?" required>
                            </div>
                        </form><!-- End # Login Form -->
                    </div><!-- End # DIV Form -->
                </div>
            </div>
        </div>
        <!-- END # MODAL LOGIN -->

        <header class="header">
            <div class="topbar clearfix">
                <div class="container">
                    <div class="row-fluid">
                        <div class="col-md-6 col-sm-6 text-left">
                            <p>
                                <strong><i class="fa fa-phone"></i></strong> +90 543 123 45 67 &nbsp;&nbsp;
                                <strong><i class="fa fa-envelope"></i></strong> <a href="mailto:#">info@mysite.com</a>
                            </p>
                        </div><!-- end left -->
                        <div class="col-md-6 col-sm-6 hidden-xs text-right">
                            <div class="social">
                               
                            

                            </div><!-- end social -->
                        </div><!-- end left -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end topbar -->

            <div class="container">
                <nav class="navbar navbar-default yamm">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="logo-normal">
                            <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt=""></a>
                        </div>
                    </div>

                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="index.html">Home</a></li>
                        </ul>
                    </div>
                </nav><!-- end navbar -->
            </div><!-- end container -->
        </header>


        <footer class="section footer noover">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="widget clearfix">
                            <h3 class="widget-title">Subscribe Our Newsletter</h3>
                            <div class="newsletter-widget">
                                <p>You can opt out of our newsletters at any time.<br> See our <a href="#">privacy policy</a>.</p>
                                <form class="form-inline" role="search">
                                    <div class="form-1">
                                        <input type="text" class="form-control" placeholder="Enter email here..">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o"></i></button>
                                    </div>
                                </form>
                                <img src="images/payments.png" alt="" class="img-responsive">
                            </div><!-- end newsletter -->
                        </div><!-- end widget -->
                    </div><!-- end col -->

                    <div class="col-lg-3 col-md-3">
                    
                    			
                        <div id="map"></div>
	
                         
                      
                    </div><!-- end col -->
                  


                    <div class="col-lg-3 col-md-3">
                        <div class="widget clearfix">
                            <h3 class="widget-title">Popular Tags</h3>
                            <div class="tags-widget">   
                                <ul class="list-inline">
                                    <li><a href="#">course</a></li>
                                    <li><a href="#">web design</a></li>
                                    <li><a href="#">development</a></li>
                                    <li><a href="#">language</a></li>
                                    <li><a href="#">learning</a></li>
                                </ul>
                            </div><!-- end list-widget -->
                        </div><!-- end widget -->
                    </div><!-- end col -->

                    <div class="col-lg-2 col-md-2">
                        <div class="widget clearfix">
                            <h3 class="widget-title">Support</h3>
                            <div class="list-widget">   
                                <ul>
                                    <li><a href="#">Terms of Use</a></li>
                                    <li><a href="#">Copyrights</a></li>
                                </ul>
                            </div><!-- end list-widget -->
                        </div><!-- end widget -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </div><!-- end container -->
        </footer><!-- end footer -->

        <div class="copyrights">
            <div class="container">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="cop-logo">
                            <a href="#"><img src="images/logo.png" alt=""></a>
                        </div>
                    </div>

                    <div class="pull-right">
                        <div class="footer-links">
                            <ul class="list-inline">
                                <li>Design : <a href="">Pasindu Madubashana</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- end container -->
        </div><!-- end copy -->
    </div><!-- end wrapper -->

    <!-- jQuery Files -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/carousel.js"></script>
    <script src="js/animate.js"></script>
    <script src="js/custom.js"></script>
    <!-- VIDEO BG PLUGINS -->
    <script src="js/videobg.js"></script>

</body>
</html>