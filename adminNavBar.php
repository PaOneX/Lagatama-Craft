<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagatama Craft</title>
</head>

<body>
    <div class="container-fluid mt-2">
        <nav class="navbar navbar-expand-lg navbar-dark bg-warning rounded-4">
            <a class="navbar-brand text-white fs-2 h1 mb-0" href="adminDashboard.php">
                <img class="me-2" src="resources/icons8-kibana-48.png" height="55" />
                Admin Dashboard</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-flex align-items-center" id="navbarSupportedContent">

                <ul class="navbar-nav mb-2 mb-lg-0">

                    <li class="nav-item col-12 col-lg-3 text-center  mt-2">
                        <a class="nav-link active btn fs-5 text-primary " aria-current="page" href="userManagement.php">User Management</a>
                    </li>

                    <li class="nav-item  col-12 col-lg-3 text-center  mt-2">
                        <a class="nav-link active btn fs-5 text-primary " aria-current="page" href="productManagement.php">Product Management</a>
                    </li>

                    <li class="nav-item  col-12 col-lg-3 text-center  mt-2">
                        <a class="nav-link active btn fs-5 text-primary " aria-current="page" href="stockManagement.php">Stock Management</a>
                    </li>

                    <li class="nav-item  col-12 col-lg-2 text-center  mt-2">
                        <a class="nav-link active btn fs-5 text-primary " aria-current="page" href="report.php">Reports</a>
                    </li>

                </ul>

            </div>
        </nav>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>