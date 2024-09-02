<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$firstName = $middleName = $lastName = $age = $birthday = $gender = $instiEmail = $phoneNumber = $department = $course = $year = $street = $barangay = $municipality = $province = $zipcode = $club_id = "";
$firstName_err = $middleName_err = $lastName_err = $age_err = $birthday_err = $gender_err = $instiEmail_err = $phoneNumber_err = $department_err = $course_err = $year_err = $street_err = $barangay_err = $municipality_err = $province_err = $zipcode_err = $club_id_err = "";

// Fetch club name and club_id if club_id is provided in the query parameter
if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']);
    $sql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $stmt->execute();
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($club) {
        $clubName = $club['clubName'];
    }
    unset($stmt);
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch student_id and profilePic from students table using institutional email
    $query = "SELECT student_id, profilePic FROM tbl_students WHERE instiEmail = :institutional_email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":institutional_email", $_POST['instiEmail']);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        $student_id = $student['student_id']; // Fetch the student_id
        $profilePic = $student['profilePic'];
    } else {
        // Default profile picture if not found
        $profilePic = "PROF_PIC.png";
        $student_id = null; // Set to null if student_id is not found
    }

    // Validate firstName
    $input_firstName = trim($_POST["firstName"]);
    if (empty($input_firstName)) {
        $firstName_err = "Please enter the first name.";
    } else {
        $firstName = $input_firstName;
    }

    // Validate middleName
    $input_middleName = trim($_POST["middleName"]);
    if (empty($input_middleName)) {
        $middleName_err = "Please enter the middle name.";
    } else {
        $middleName = $input_middleName;
    }

    // Validate lastName
    $input_lastName = trim($_POST["lastName"]);
    if (empty($input_lastName)) {
        $lastName_err = "Please enter the last name.";
    } else {
        $lastName = $input_lastName;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if (empty($input_age)) {
        $age_err = "Please enter the age.";
    } elseif (!is_numeric($input_age)) {
        $age_err = "Please enter a valid age.";
    } else {
        $age = $input_age;
    }

    // Validate birthday
    $input_birthday = trim($_POST["birthday"]);
    if (empty($input_birthday)) {
        $birthday_err = "Please enter the birthday.";
    } else {
        $birthday = $input_birthday;
    }

    // Validate gender
    $input_gender = trim($_POST["gender"]);
    if (empty($input_gender)) {
        $gender_err = "Please select the gender.";
    } else {
        $gender = $input_gender;
    }

    // Validate instiEmail
    $input_instiEmail = trim($_POST["instiEmail"]);
    if (empty($input_instiEmail)) {
        $instiEmail_err = "Please enter the institutional email.";
    } elseif (!filter_var($input_instiEmail, FILTER_VALIDATE_EMAIL)) {
        $instiEmail_err = "Please enter a valid email address.";
    } else {
        $instiEmail = $input_instiEmail;
    }

    // Validate phoneNumber
    $input_phoneNumber = trim($_POST["phoneNumber"]);
    if (empty($input_phoneNumber)) {
        $phoneNumber_err = "Please enter the phone number.";
    } else {
        $phoneNumber = $input_phoneNumber;
    }

    // Validate department
    $input_department = trim($_POST["department"]);
    if (empty($input_department)) {
        $department_err = "Please enter the department.";
    } else {
        $department = $input_department;
    }

    // Validate course
    $input_course = trim($_POST["course"]);
    if (empty($input_course)) {
        $course_err = "Please enter the course.";
    } else {
        $course = $input_course;
    }

    // Validate year
    $input_year = trim($_POST["year"]);
    if (empty($input_year)) {
        $year_err = "Please enter the year.";
    } else {
        $year = $input_year;
    }

    // Validate street
    $input_street = trim($_POST["street"]);
    if (empty($input_street)) {
        $street_err = "Please enter the street.";
    } else {
        $street = $input_street;
    }

    // Validate barangay
    $input_barangay = trim($_POST["barangay"]);
    if (empty($input_barangay)) {
        $barangay_err = "Please enter the barangay.";
    } else {
        $barangay = $input_barangay;
    }

    // Validate municipality
    $input_municipality = trim($_POST["municipality"]);
    if (empty($input_municipality)) {
        $municipality_err = "Please enter the municipality.";
    } else {
        $municipality = $input_municipality;
    }

    // Validate province
    $input_province = trim($_POST["province"]);
    if (empty($input_province)) {
        $province_err = "Please enter the province.";
    } else {
        $province = $input_province;
    }

    // Validate zipcode
    $input_zipcode = trim($_POST["zipcode"]);
    if (empty($input_zipcode)) {
        $zipcode_err = "Please enter the zipcode.";
    } else {
        $zipcode = $input_zipcode;
    }

    // Validate club_id
    $input_club_id = trim($_POST["club_id"]);
    if (empty($input_club_id)) {
        $club_id_err = "Please enter the club ID.";
    } elseif (!is_numeric($input_club_id)) {
        $club_id_err = "Please enter a valid club ID.";
    } else {
        $club_id = $input_club_id;
    }

    // Check input errors before inserting into the database
    if (empty($firstName_err) && empty($middleName_err) && empty($lastName_err) && empty($age_err) && empty($birthday_err) && empty($gender_err) && empty($instiEmail_err) && empty($phoneNumber_err) && empty($department_err) && empty($course_err) && empty($year_err) && empty($street_err) && empty($barangay_err) && empty($municipality_err) && empty($province_err) && empty($zipcode_err) && empty($club_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_registered_students (student_id, firstName, middleName, lastName, age, birthday, gender, instiEmail, phoneNumber, department, course, year, street, barangay, municipality, province, zipcode, profilePic, club_id) 
                VALUES (:student_id, :firstName, :middleName, :lastName, :age, :birthday, :gender, :instiEmail, :phoneNumber, :department, :course, :year, :street, :barangay, :municipality, :province, :zipcode, :profilePic, :club_id)";

        $stmt = $pdo->prepare($sql);

        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
        $stmt->bindParam(":firstName", $firstName);
        $stmt->bindParam(":middleName", $middleName);
        $stmt->bindParam(":lastName", $lastName);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":birthday", $birthday);
        $stmt->bindParam(":gender", $gender);
        $stmt->bindParam(":instiEmail", $instiEmail);
        $stmt->bindParam(":phoneNumber", $phoneNumber);
        $stmt->bindParam(":department", $department);
        $stmt->bindParam(":course", $course);
        $stmt->bindParam(":year", $year);
        $stmt->bindParam(":street", $street);
        $stmt->bindParam(":barangay", $barangay);
        $stmt->bindParam(":municipality", $municipality);
        $stmt->bindParam(":province", $province);
        $stmt->bindParam(":zipcode", $zipcode);
        $stmt->bindParam(":profilePic", $profilePic);
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records created successfully. Redirect to landing page
            echo "<script>alert('Registration successful!');</script>";
            echo "<script>window.location.href = '/esas/esas_student/home.php';</script>";
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            // Debug: echo $stmt->errorInfo(); // Uncomment for debugging
        }
    }

    // Close statement
    unset($stmt);
}

// Close connection (if not using persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Student Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font: 14px Helvetica;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0px;
        }
        .container-fluid {
            padding: 20px;
        }
        .navbar-darkblue {
            background-color: #003366;
        }
        .navbar-darkblue .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-darkblue .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        #dashboard_navigations {
            float: flex-end;
        }
        .form-group select {
            max-height: auto !important;
            overflow-y: auto !important;
        }

    </style>
</head>
<body>
    <!-- <nav class="navbar navbar-expand-lg navbar-darkblue">
        <img src="icons/SAS_LOGO.png" height="50" class="d-inline-block align-top" alt="SAS Logo">
        <h5 class="ml-2 mb-0 text-light" id="nbsc_sas_name">Student Organization Club Membership and Information System</h5>
        <button class="navbar-toggler mt-2" type="button" data-toggle="collapse" data-target="#dashboard_navigations" aria-controls="dashboard_navigations" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="dashboard_navigations">
            <ul class="navbar-nav mr-auto"></ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
            </form>
            <!-- <a href="logout.php" class="btn btn-danger ml-3">Log out</a> --
            <a href="../logout.php" class="btn btn-danger ml-3">Log out</a>
        </div>
    </nav> -->

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($clubName)): ?>
                        <h2><?php echo htmlspecialchars($clubName); ?></h2>
                        <!-- <h2><?php echo htmlspecialchars($club_id); ?></h2> -->
                        <h4>Student Registration</h4>
                    <?php endif; ?>
                    <p class="mb-5">Please fill this form and submit to register.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="firstName" class="form-control <?php echo (!empty($firstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>" required>
                            <span class="invalid-feedback"><?php echo $firstName_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="middleName" class="form-control <?php echo (!empty($middleName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $middleName; ?>" required>
                            <span class="invalid-feedback"><?php echo $middleName_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="lastName" class="form-control <?php echo (!empty($lastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>" required>
                            <span class="invalid-feedback"><?php echo $lastName_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>" required>
                            <span class="invalid-feedback"><?php echo $age_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Birthday</label>
                            <input type="date" name="birthday" class="form-control <?php echo (!empty($birthday_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $birthday; ?>" required>
                            <span class="invalid-feedback"><?php echo $birthday_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control <?php echo (!empty($gender_err)) ? 'is-invalid' : ''; ?>">
                                <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Prefer not to say</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $gender_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Institutional Email</label>
                            <input type="email" name="instiEmail" class="form-control <?php echo (!empty($instiEmail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $instiEmail; ?>" required>
                            <span class="invalid-feedback"><?php echo $instiEmail_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phoneNumber" class="form-control <?php echo (!empty($phoneNumber_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phoneNumber; ?>" required>
                            <span class="invalid-feedback"><?php echo $phoneNumber_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" class="form-control <?php echo (!empty($department_err)) ? 'is-invalid' : ''; ?>">
                                <option value="TEP" <?php echo ($department == 'TEP') ? 'selected' : ''; ?>>TEP</option>
                                <option value="BSBA" <?php echo ($department == 'BSBA') ? 'selected' : ''; ?>>BSBA</option>
                                <option value="CCS" <?php echo ($department == 'CCS') ? 'selected' : ''; ?>>CCS</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $department_err; ?></span>
                        </div><div class="form-group">
                            <label>Course</label>
                            <select name="course" class="form-control <?php echo (!empty($course_err)) ? 'is-invalid' : ''; ?>">
                                <option value="BSEE" <?php echo ($course == 'BSEE') ? 'selected' : ''; ?>>BSEE</option>
                                <option value="BSED" <?php echo ($course == 'BSED') ? 'selected' : ''; ?>>BSED</option>
                                <option value="BSECE" <?php echo ($course == 'BSECE') ? 'selected' : ''; ?>>BSECE</option>
                                <option value="BSIT" <?php echo ($course == 'BSIT') ? 'selected' : ''; ?>>BSIT</option>
                                <option value="BSFM" <?php echo ($course == 'BSFM') ? 'selected' : ''; ?>>BSFM</option>
                                <option value="BSMM" <?php echo ($course == 'BSMM') ? 'selected' : ''; ?>>BSMM</option>
                                <option value="BSOM" <?php echo ($course == 'BSOM') ? 'selected' : ''; ?>>BSOM</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $course_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <select name="year" class="form-control <?php echo (!empty($year_err)) ? 'is-invalid' : ''; ?>">
                                <option value="1st Year" <?php echo ($year == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                                <option value="2nd Year" <?php echo ($year == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                                <option value="3rd Year" <?php echo ($year == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                                <option value="4th Year" <?php echo ($year == '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $year_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Street</label>
                            <input type="text" name="street" class="form-control <?php echo (!empty($street_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $street; ?>" required>
                            <span class="invalid-feedback"><?php echo $street_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Barangay</label>
                            <select name="barangay" class="form-control <?php echo (!empty($barangay_err)) ? 'is-invalid' : ''; ?>">
                                <option value="Agusan Canyon" <?php echo ($barangay == 'Agusan Canyon') ? 'selected' : ''; ?>>Agusan Canyon</option>
                                <option value="Alae" <?php echo ($barangay == 'Alae') ? 'selected' : ''; ?>>Alae</option>
                                <option value="Dahilayan" <?php echo ($barangay == 'Dahilayan') ? 'selected' : ''; ?>>Dahilayan</option>
                                <option value="Dalirig" <?php echo ($barangay == 'Dalirig') ? 'selected' : ''; ?>>Dalirig</option>
                                <option value="Damilag" <?php echo ($barangay == 'Damilag') ? 'selected' : ''; ?>>Damilag</option>
                                <option value="Diclum" <?php echo ($barangay == 'Diclum') ? 'selected' : ''; ?>>Diclum</option>
                                <option value="Guilang-guilang" <?php echo ($barangay == 'Guilang-guilang') ? 'selected' : ''; ?>>Guilang-guilang</option>
                                <option value="Kalugmanan" <?php echo ($barangay == 'Kalugmanan') ? 'selected' : ''; ?>>Kalugmanan</option>
                                <option value="Lindaban" <?php echo ($barangay == 'Lindaban') ? 'selected' : ''; ?>>Lindaban</option>
                                <option value="Lingion" <?php echo ($barangay == 'Lingion') ? 'selected' : ''; ?>>Lingion</option>
                                <option value="Lunocan" <?php echo ($barangay == 'Lunocan') ? 'selected' : ''; ?>>Lunocan</option>
                                <option value="Maluko" <?php echo ($barangay == 'Maluko') ? 'selected' : ''; ?>>Maluko</option>
                                <option value="Mambatangan" <?php echo ($barangay == 'Mambatangan') ? 'selected' : ''; ?>>Mambatangan</option>
                                <option value="Mampayag" <?php echo ($barangay == 'Mampayag') ? 'selected' : ''; ?>>Mampayag</option>
                                <option value="Mantibugao" <?php echo ($barangay == 'Mantibugao') ? 'selected' : ''; ?>>Mantibugao</option>
                                <option value="Minsuro" <?php echo ($barangay == 'Minsuro') ? 'selected' : ''; ?>>Minsuro</option>
                                <option value="San Miguel" <?php echo ($barangay == 'San Miguel') ? 'selected' : ''; ?>>San Miguel</option>
                                <option value="Sankanan" <?php echo ($barangay == 'Sankanan') ? 'selected' : ''; ?>>Sankanan</option>
                                <option value="Santiago" <?php echo ($barangay == 'Santiago') ? 'selected' : ''; ?>>Santiago</option>
                                <option value="Santo Niño" <?php echo ($barangay == 'Santo Niño') ? 'selected' : ''; ?>>Santo Niño</option>
                                <option value="Tankulan" <?php echo ($barangay == 'Tankulan') ? 'selected' : ''; ?>>Tankulan</option>
                                <option value="Ticala" <?php echo ($barangay == 'Ticala') ? 'selected' : ''; ?>>Ticala</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $barangay_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Municipality/City</label>
                            <select name="municipality" class="form-control <?php echo (!empty($municipality_err)) ? 'is-invalid' : ''; ?>">
                                <option value="Baungon" <?php echo ($municipality == 'Baungon') ? 'selected' : ''; ?>>Baungon</option>
                                <option value="Cabanglasan" <?php echo ($municipality == 'Cabanglasan') ? 'selected' : ''; ?>>Cabanglasan</option>
                                <option value="Damulog" <?php echo ($municipality == 'Damulog') ? 'selected' : ''; ?>>Damulog</option>
                                <option value="Dangcagan" <?php echo ($municipality == 'Dangcagan') ? 'selected' : ''; ?>>Dangcagan</option>
                                <option value="Don Carlos" <?php echo ($municipality == 'Don Carlos') ? 'selected' : ''; ?>>Don Carlos</option>
                                <option value="Impasug-ong" <?php echo ($municipality == 'Impasug-ong') ? 'selected' : ''; ?>>Impasug-ong</option>
                                <option value="Kadingilan" <?php echo ($municipality == 'Kadingilan') ? 'selected' : ''; ?>>Kadingilan</option>
                                <option value="Kalilangan" <?php echo ($municipality == 'Kalilangan') ? 'selected' : ''; ?>>Kalilangan</option>
                                <option value="Kibawe" <?php echo ($municipality == 'Kibawe') ? 'selected' : ''; ?>>Kibawe</option>
                                <option value="Kitaotao" <?php echo ($municipality == 'Kitaotao') ? 'selected' : ''; ?>>Kitaotao</option>
                                <option value="Lantapan" <?php echo ($municipality == 'Lantapan') ? 'selected' : ''; ?>>Lantapan</option>
                                <option value="Libona" <?php echo ($municipality == 'Libona') ? 'selected' : ''; ?>>Libona</option>
                                <option value="Malaybalay" <?php echo ($municipality == 'Malaybalay') ? 'selected' : ''; ?>>Malaybalay</option>
                                <option value="Malitbog" <?php echo ($municipality == 'Malitbog') ? 'selected' : ''; ?>>Malitbog</option>
                                <option value="Manolo Fortich" <?php echo ($municipality == 'Manolo Fortich') ? 'selected' : ''; ?>>Manolo Fortich</option>
                                <option value="Maramag" <?php echo ($municipality == 'Maramag') ? 'selected' : ''; ?>>Maramag</option>
                                <option value="Pangantucan" <?php echo ($municipality == 'Pangantucan') ? 'selected' : ''; ?>>Pangantucan</option>
                                <option value="Quezon" <?php echo ($municipality == 'Quezon') ? 'selected' : ''; ?>>Quezon</option>
                                <option value="San Fernando" <?php echo ($municipality == 'San Fernando') ? 'selected' : ''; ?>>San Fernando</option>
                                <option value="Sumilao" <?php echo ($municipality == 'Sumilao') ? 'selected' : ''; ?>>Sumilao</option>
                                <option value="Talakag" <?php echo ($municipality == 'Talakag') ? 'selected' : ''; ?>>Talakag</option>
                                <option value="Valencia" <?php echo ($municipality == 'Valencia') ? 'selected' : ''; ?>>Valencia</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $municipality_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Province</label>
                            <select name="province" class="form-control <?php echo (!empty($province_err)) ? 'is-invalid' : ''; ?>">
                                <option value="Abra" <?php echo ($province == 'Abra') ? 'selected' : ''; ?>>Abra</option>
                                <option value="Albay" <?php echo ($province == 'Albay') ? 'selected' : ''; ?>>Albay</option>
                                <option value="Antique" <?php echo ($province == 'Antique') ? 'selected' : ''; ?>>Antique</option>
                                <option value="Apayao" <?php echo ($province == 'Apayao') ? 'selected' : ''; ?>>Apayao</option>
                                <option value="Aurora" <?php echo ($province == 'Aurora') ? 'selected' : ''; ?>>Aurora</option>
                                <option value="Basilan" <?php echo ($province == 'Basilan') ? 'selected' : ''; ?>>Basilan</option>
                                <option value="Batanes" <?php echo ($province == 'Batanes') ? 'selected' : ''; ?>>Batanes</option>
                                <option value="Batangas" <?php echo ($province == 'Batangas') ? 'selected' : ''; ?>>Batangas</option>
                                <option value="Benguet" <?php echo ($province == 'Benguet') ? 'selected' : ''; ?>>Benguet</option>
                                <option value="Biliran" <?php echo ($province == 'Biliran') ? 'selected' : ''; ?>>Biliran</option>
                                <option value="Bohol" <?php echo ($province == 'Bohol') ? 'selected' : ''; ?>>Bohol</option>
                                <option value="Bukidnon" <?php echo ($province == 'Bukidnon') ? 'selected' : ''; ?>>Bukidnon</option>
                                <option value="Bulacan" <?php echo ($province == 'Bulacan') ? 'selected' : ''; ?>>Bulacan</option>
                                <option value="Cagayan" <?php echo ($province == 'Cagayan') ? 'selected' : ''; ?>>Cagayan</option>
                                <option value="Camarines Norte" <?php echo ($province == 'Camarines Norte') ? 'selected' : ''; ?>>Camarines Norte</option>
                                <option value="Camarines Sur" <?php echo ($province == 'Camarines Sur') ? 'selected' : ''; ?>>Camarines Sur</option>
                                <option value="Camiguin" <?php echo ($province == 'Camiguin') ? 'selected' : ''; ?>>Camiguin</option>
                                <option value="Capiz" <?php echo ($province == 'Capiz') ? 'selected' : ''; ?>>Capiz</option>
                                <option value="Catanduanes" <?php echo ($province == 'Catanduanes') ? 'selected' : ''; ?>>Catanduanes</option>
                                <option value="Cavite" <?php echo ($province == 'Cavite') ? 'selected' : ''; ?>>Cavite</option>
                                <option value="Cebu" <?php echo ($province == 'Cebu') ? 'selected' : ''; ?>>Cebu</option>
                                <option value="Cotabato" <?php echo ($province == 'Cotabato') ? 'selected' : ''; ?>>Cotabato</option>
                                <option value="Davao de Oro" <?php echo ($province == 'Davao de Oro') ? 'selected' : ''; ?>>Davao de Oro</option>
                                <option value="Davao del Norte" <?php echo ($province == 'Davao del Norte') ? 'selected' : ''; ?>>Davao del Norte</option>
                                <option value="Davao del Sur" <?php echo ($province == 'Davao del Sur') ? 'selected' : ''; ?>>Davao del Sur</option>
                                <option value="Davao Occidental" <?php echo ($province == 'Davao Occidental') ? 'selected' : ''; ?>>Davao Occidental</option>
                                <option value="Davao Oriental" <?php echo ($province == 'Davao Oriental') ? 'selected' : ''; ?>>Davao Oriental</option>
                                <option value="Dinagat Islands" <?php echo ($province == 'Dinagat Islands') ? 'selected' : ''; ?>>Dinagat Islands</option>
                                <option value="Eastern Samar" <?php echo ($province == 'Eastern Samar') ? 'selected' : ''; ?>>Eastern Samar</option>
                                <option value="Guimaras" <?php echo ($province == 'Guimaras') ? 'selected' : ''; ?>>Guimaras</option>
                                <option value="Ifugao" <?php echo ($province == 'Ifugao') ? 'selected' : ''; ?>>Ifugao</option>
                                <option value="Iloilo" <?php echo ($province == 'Iloilo') ? 'selected' : ''; ?>>Iloilo</option>
                                <option value="Isabela" <?php echo ($province == 'Isabela') ? 'selected' : ''; ?>>Isabela</option>
                                <option value="Kalinga" <?php echo ($province == 'Kalinga') ? 'selected' : ''; ?>>Kalinga</option>
                                <option value="Laguna" <?php echo ($province == 'Laguna') ? 'selected' : ''; ?>>Laguna</option>
                                <option value="Lanao del Norte" <?php echo ($province == 'Lanao del Norte') ? 'selected' : ''; ?>>Lanao del Norte</option>
                                <option value="Lanao del Sur" <?php echo ($province == 'Lanao del Sur') ? 'selected' : ''; ?>>Lanao del Sur</option>
                                <option value="La Union" <?php echo ($province == 'La Union') ? 'selected' : ''; ?>>La Union</option>
                                <option value="Leyte" <?php echo ($province == 'Leyte') ? 'selected' : ''; ?>>Leyte</option>
                                <option value="Maguindanao del Norte" <?php echo ($province == 'Maguindanao del Norte') ? 'selected' : ''; ?>>Maguindanao del Norte</option>
                                <option value="Maguindanao del Sur" <?php echo ($province == 'Maguindanao del Sur') ? 'selected' : ''; ?>>Maguindanao del Sur</option>
                                <option value="Marinduque" <?php echo ($province == 'Marinduque') ? 'selected' : ''; ?>>Marinduque</option>
                                <option value="Masbate" <?php echo ($province == 'Masbate') ? 'selected' : ''; ?>>Masbate</option>
                                <option value="Misamis Occidental" <?php echo ($province == 'Misamis Occidental') ? 'selected' : ''; ?>>Misamis Occidental</option>
                                <option value="Misamis Oriental" <?php echo ($province == 'Misamis Oriental') ? 'selected' : ''; ?>>Misamis Oriental</option>
                                <option value="Mountain Province" <?php echo ($province == 'Mountain Province') ? 'selected' : ''; ?>>Mountain Province</option>
                                <option value="Negros Occidental" <?php echo ($province == 'Negros Occidental') ? 'selected' : ''; ?>>Negros Occidental</option>
                                <option value="Negros Oriental" <?php echo ($province == 'Negros Oriental') ? 'selected' : ''; ?>>Negros Oriental</option>
                                <option value="Northern Samar" <?php echo ($province == 'Northern Samar') ? 'selected' : ''; ?>>Northern Samar</option>
                                <option value="Nueva Ecija" <?php echo ($province == 'Nueva Ecija') ? 'selected' : ''; ?>>Nueva Ecija</option>
                                <option value="Nueva Vizcaya" <?php echo ($province == 'Nueva Vizcaya') ? 'selected' : ''; ?>>Nueva Vizcaya</option>
                                <option value="Occidental Mindoro" <?php echo ($province == 'Occidental Mindoro') ? 'selected' : ''; ?>>Occidental Mindoro</option>
                                <option value="Oriental Mindoro" <?php echo ($province == 'Oriental Mindoro') ? 'selected' : ''; ?>>Oriental Mindoro</option>
                                <option value="Palawan" <?php echo ($province == 'Palawan') ? 'selected' : ''; ?>>Palawan</option>
                                <option value="Pangasinan" <?php echo ($province == 'Pangasinan') ? 'selected' : ''; ?>>Pangasinan</option>
                                <option value="Quirino" <?php echo ($province == 'Quirino') ? 'selected' : ''; ?>>Quirino</option>
                                <option value="Rizal" <?php echo ($province == 'Rizal') ? 'selected' : ''; ?>>Rizal</option>
                                <option value="Romblon" <?php echo ($province == 'Romblon') ? 'selected' : ''; ?>>Romblon</option>
                                <option value="Samar" <?php echo ($province == 'Samar') ? 'selected' : ''; ?>>Samar</option>
                                <option value="Sarangani" <?php echo ($province == 'Sarangani') ? 'selected' : ''; ?>>Sarangani</option>
                                <option value="Sorsogon" <?php echo ($province == 'Sorsogon') ? 'selected' : ''; ?>>Sorsogon</option>
                                <option value="South Cotabato" <?php echo ($province == 'South Cotabato') ? 'selected' : ''; ?>>South Cotabato</option>
                                <option value="Southern Leyte" <?php echo ($province == 'Southern Leyte') ? 'selected' : ''; ?>>Southern Leyte</option>
                                <option value="Sulu" <?php echo ($province == 'Sulu') ? 'selected' : ''; ?>>Sulu</option>
                                <option value="Surigao del Norte" <?php echo ($province == 'Surigao del Norte') ? 'selected' : ''; ?>>Surigao del Norte</option>
                                <option value="Surigao del Sur" <?php echo ($province == 'Surigao del Sur') ? 'selected' : ''; ?>>Surigao del Sur</option>
                                <option value="Tawi-Tawi" <?php echo ($province == 'Tawi-Tawi') ? 'selected' : ''; ?>>Tawi-Tawi</option>
                                <option value="Tarlac" <?php echo ($province == 'Tarlac') ? 'selected' : ''; ?>>Tarlac</option>
                                <option value="Zambales" <?php echo ($province == 'Zambales') ? 'selected' : ''; ?>>Zambales</option>
                                <option value="Zamboanga del Norte" <?php echo ($province == 'Zamboanga del Norte') ? 'selected' : ''; ?>>Zamboanga del Norte</option>
                                <option value="Zamboanga del Sur" <?php echo ($province == 'Zamboanga del Sur') ? 'selected' : ''; ?>>Zamboanga del Sur</option>
                                <option value="Zamboanga Sibugay" <?php echo ($province == 'Zamboanga Sibugay') ? 'selected' : ''; ?>>Zamboanga Sibugay</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $province_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Zipcode</label>
                            <input type="text" name="zipcode" class="form-control <?php echo (!empty($zipcode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $zipcode; ?>" required>
                            <span class="invalid-feedback"><?php echo $zipcode_err; ?></span>
                        </div>
                        <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($club_id); ?>">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="#" onclick="window.history.back();" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- <footer class="navbar-darkblue text-white mt-1 p-4 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <ul class="list-unstyled">
                    <li>Email: sas@nbsc.edu.ph</li>
                    <li>Phone: 0927 669 0090</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Follow Us</h5>
                <ul class="list-unstyled">
                    <li><a href="https://www.facebook.com/nbscstudentaffairsandservices" class="text-white"><i class="fa fa-facebook-square"></i> Facebook</a></li>
                    <li><a href="#" class="text-white"><i class="fa fa-twitter-square"></i>Twitter</a></li>
                    <li><a href="#" class="text-white"><i class="fa fa-instagram"></i>Instagram</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="http://nbsc.edu.ph" class="text-white">NBSC Website</a></li>
                    <li><a href="https://nbsc.edu.ph/student-affairs-services/" class="text-white">SAS Website</a></li>
                    <li><a href="#" class="text-white">About Us</a></li>
                    <li><a href="#" class="text-white">Privacy Policy</a></li>
                    <li><a href="#" class="text-white">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <p class="mb-0">© 2024 Student Organization Club Membership and Information System. All rights reserved.</p>
    </div>
</footer> -->

</html>
