<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eSAS - Officers</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #ffffff 0%, #f4f4f4 100%);
            background-attachment: fixed;
        }
        .header {
            background-color: #004d80;
            color: white;
            text-align: left;
            padding: 20px;
            padding-left: 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 2em;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 1.2em;
        }
        .nav-bar {
            background-color: #2980b9;
            overflow: hidden;
            display: flex;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .nav-bar button {
            background-color: #2980b9;
            border: none;
            color: white;
            padding: 14px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .nav-bar button:hover {
            background-color: #3498db;
        }
        
        .mission-vision h2, .mission-vision p {
            color: white;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 1), 
                        -2px -2px 10px rgba(0, 0, 0, 1); 
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .mission-vision h2{
            margin-top: 40px;
        }

        .mission-vision {
            padding: 40px 20px;
        }

        .mission-vision p {
            font-size: 24px; 
            line-height: 1.6; 
        }


        /* .slider-container {
            width: 400px;
            margin: 60px auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(166, 240, 176, 0.733);
            border-radius: 10px;
            max-height: 550px; 
            max-height: auto; 
        } */
/* 
        .slide img {
            width: auto; 
            height: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            box-sizing: border-box;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .slide.active {
            opacity: 1;
        }
        .slide img {
            width: 100%;
            border-radius: 10px;
        }
        .slider-buttons {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .prev, .next {
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            transition: background-color 0.3s;
        }
        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .content {
            padding: 20px;
            display: none;
            font-size: 24px;
        }
        .content.active {
            display: block;
        }
        .mission-vision {
            text-align: center;
            padding: 20px;
        }
        .mission-vision img {
            width: 50%;
            margin: 20px 0;
        } */


        /* .mission-vision, #csg, #sbo {
            position: relative; 
        }

        .mission-vision::before, #csg::before, #sbo::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 1; 
        }

        .mission-vision h2, .mission-vision p,
        #csg .slider-container,
        #sbo .slider-container {
            position: relative;
            z-index: 2;
        } 
            
         style="background-image: url('../images/NBSC_BLDG_FINAL_NO_WIRES_JPEG.jpg'); background-size: cover; background-position: center; height: 631px;" */

    </style>
</head>
<body>
    <div class="header">
        <h1 style="display: flex; align-items: center; gap: 10px;">
            <img src="../../assets/img/SAS_LOGO.png" style="height: .5in; display: block;">
            eSAS
        </h1>
    </div>
    <div class="nav-bar">
        <button onclick="showSection('menu')">Mission & Vision</button>
        <button onclick="showSection('csg')">CSG Officers</button>
        <button onclick="showSection('sbo')">SBO Officers</button>
    </div>

    <div>
        <h2>Vision</h2>
        <p>Northern Bukidnon State College will be a college of choice, nationally recognized for having innovative and sustainable academic programs, research, extensions, and services that cultivate educational, personal, and professional growth to meet the needs of our students, our society, and the global community.</p>
        <h2>Mission</h2>
        <p>Northern Bukidnon State College is an accessible institution of higher education that provides quality educational opportunities to develop students into socially responsible, competent, and productive professionals.</p>
    
                    <img src="../images/OFFICERS_CSG.jpeg" alt="CSG Officers">
                    <img src="../images/OFFICERS_SBO_TEP.png" alt="TEP SBO Officers">
                    <img src="../images/OFFICERS_SBO_BSBA.png" alt="BSBA SBO Officers">
    </div>
<footer style="background-color: #004d80; color: white; padding-bottom: 10px; text-align: center; font-size: 0.9em;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div class="row" style="display: flex; justify-content: space-between;">
            <div class="col-md-4" style="flex: 1; margin-bottom: 10px;">
                <h5 style="margin-bottom: 10px; font-size: 1.2em;">Contact Us</h5>
                <ul class="list-unstyled" style="list-style-type: none; padding: 0;">
                    <li>Email: <a href="mailto:sas@nbsc.edu.ph" style="color: #f1c40f; text-decoration: underline;">sas@nbsc.edu.ph</a></li>
                    <li>Phone: <a href="tel:+639276690090" style="color: #f1c40f; text-decoration: underline;">0927 669 0090</a></li>
                </ul>
            </div>
            <div class="col-md-4" style="flex: 1; margin-bottom: 10px;">
                <h5 style="margin-bottom: 10px; font-size: 1.2em;">Follow Us</h5>
                <ul class="list-unstyled" style="list-style-type: none; padding: 0;">
                    <li><a href="https://www.facebook.com/nbscstudentaffairsandservices" style="color: #f1c40f; text-decoration: underline;"><i class="fa fa-facebook-square"></i> Facebook</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;"><i class="fa fa-twitter-square"></i> Twitter</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;"><i class="fa fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
            <div class="col-md-4" style="flex: 1; margin-bottom: 10px;">
                <h5 style="margin-bottom: 10px; font-size: 1.2em;">Quick Links</h5>
                <ul class="list-unstyled" style="list-style-type: none; padding: 0;">
                    <li><a href="http://nbsc.edu.ph" style="color: #f1c40f; text-decoration: underline;">NBSC Website</a></li>
                    <li><a href="https://nbsc.edu.ph/student-affairs-services/" style="color: #f1c40f; text-decoration: underline;">SAS Website</a></li>
                    <!-- <li><a href="#" style="color: #f1c40f; text-decoration: underline;">About Us</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;">Privacy Policy</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;">Terms of Service</a></li> -->
                </ul>
            </div>
        </div>
        <hr style="border-color: rgba(255, 255, 255, 0.2);">
        <p class="mb-0" style="font-size: 1em;">©eSAS2024. All rights reserved.</p>
    </div>
</footer>


</body>
</html>
