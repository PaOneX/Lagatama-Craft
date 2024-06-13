<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagatama Craft | Admin Sign In</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="icon" href="resources/images/hansi logo jpg.jpg" />
</head>

<body class="admin_img">
    <div class="container-fluid">
        <div class="d-flex vh-100 justify-content-center align-items-center ">
            <div class="card bg-transparent col-6 col-lg-4 shadow-lg p-3 mb-5 rounded-5 border border-light border-5">
                <div class="card-body p-3">
                    <h2 class="text-center textX3">Admin Sign In</h2>
                    <div class="row">
                        <div class="col-12 col-lg-12 mt-2">
                            <label for=" " class="mb-2 form-label textX3" >Email :</label>
                            <input type="text" class="form-control" id="email"/>
                        </div>
                        <div class="input-group col-5 col-lg-12 mt-3">
                            <label for="" class="col-12 mb-2 forl-label textX3">Password :</label>
                            <input type="password" class="form-control" placeholder="*******" id="pw1">
                            <button class="btn btn-secondary" onclick="showPassword();" id="sp"><i class="bi bi-eye-slash-fill"></i></button>
                        </div>
                        <div class="mt-4 mb-3">
                            <button class="btn btn-danger col-12 col-lg-12 fw-bold" onclick="adminSignIn();">Sign In</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script src="script.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="bootstrap.bundle.js"></script>

</body>

</html>