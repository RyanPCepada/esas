<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>eSAS - Student Login</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-5 col-md-5 col-lg-4 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center my-4">
                            <h5 class="mb-0">Northern Bukidnon State College</h5>
                            <h6 class="text-muted">Student Information System</h6>
                        </div>
                        <div class="card shadow">
                            <div class="card-body py-4">
                                <div class="py-4 pt-2">
                                    <div class="text-center">
                                        <img src="../assets/img/nbsclogo.png" alt="NBSC Logo" class="img-fluid " width="100" height="132" />
                                    </div>
                                    <form id="frmnbsclogin">
                                        <div class="mb-2">
                                            <label>Student ID</label>
                                            <input name="student_id" id="inloginemail" class="form-control form-control-sm" type="text" placeholder="Enter your Student ID" required />
                                        </div>
                                        <div class="mb-4">
                                            <label>Password</label>
                                            <input name="password" id="inloginpass" class="form-control form-control-sm" type="password" placeholder="Enter your Password" required />
                                        </div>

                                        <div class="text-center mt-3">
                                            <button id="btnsubmitlogin" type="submit" class="btn btn-sm btn-primary rounded w-100">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#frmnbsclogin').submit(function(e) {
                e.preventDefault();
                btnDisable('#btnsubmitlogin', true);
                loginAction();
            });
        });

        function loginAction() {
            $.post("actions/login_action.php", {
                student_id: $('#inloginemail').val(),
                password: $('#inloginpass').val(),
            }, function(data) {
                if (data == 'success') {
                    alert('Login Successfully');
                    window.location.href = "./index.php";
                } else {
                    alert(data);
                    btnDisable('#btnsubmitlogin', false)
                }
            });
        }

        function btnDisable(element, type) {
            $(element).prop('disabled', type);
        }
    </script>
</body>

</html>