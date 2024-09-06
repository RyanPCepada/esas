<?php
// Include config file
require_once "../../config.php";

// Define variables and initialize with empty values
$firstName = $middleName = $lastName = $age = $birthday = $gender = $instiEmail = $phoneNumber = $department = $course = $year = $street = $barangay = $municipality = $province = $zipcode = $club_id = $status = "";
$firstName_err = $middleName_err = $lastName_err = $age_err = $birthday_err = $gender_err = $instiEmail_err = $phoneNumber_err = $department_err = $course_err = $year_err = $street_err = $barangay_err = $municipality_err = $province_err = $zipcode_err = $club_id_err = "";

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
        $sql = "INSERT INTO tbl_registration (student_id, firstName, middleName, lastName, age, birthday, gender, instiEmail, phoneNumber, department, course, year, street, barangay, municipality, province, zipcode, profilePic, status, club_id) 
                VALUES (:student_id, :firstName, :middleName, :lastName, :age, :birthday, :gender, :instiEmail, :phoneNumber, :department, :course, :year, :street, :barangay, :municipality, :province, :zipcode, :profilePic, :status, :club_id)";

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

        // Add status with default value "pending" after profilePic
        $status = 'pending';
        $stmt->bindParam(":status", $status);

        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records created successfully. Redirect to landing page
            echo "<script>alert('Registration successful!');</script>";
            echo "<script>window.location.href = '/esas/esas_student/clubs.php';</script>";
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

