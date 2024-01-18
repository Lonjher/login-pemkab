<?php

    $db_host = 'localhost';
    $db_name = 'db_login_pemkab';
    $db_user = 'root';
    $db_pass = '';

    // Koneksi Database
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    // if($conn){
    //     echo "Koneksi berhasil";
    // }else {
    //     die("Koneksi gagal".mysqli_connect_error());
    // }

    // define('DB_HOST', 'localhost');
    // define('DB_USER', 'root');
    // define('DB_PASS', '');
    // define('DB_NAME', 'db_login_pemkab');
    // define('DB_USER_TBL', 'users');

    // Konfigurasi Google
    define('GOOGLE_CLIENT_ID', '720596392903-3q2kr30eqg0j186sts1567bdf42h4q5h.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET', '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');
    define('GOOGLE_REDIRECT_URI', 'http://localhost/login-pemkab/login.php');

    // Memanggil Library
    require_once 'vendor/autoload.php';
    
    // Inisiasi google client
    $client = new Google_Client();
    
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->addScope('email');
    $client->addScope('profile');
    
    if(isset($_GET['code'])){
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // echo "<pre>";
        // print_r($token);
        // echo "</pre>";
        
        // Start session
        // if(!session_id()){
        //     session_start();
        // }

        if(!isset($token["error"])){
            // $client->setAccessToken($token['access_token']);

            // Inisisasi google service oauth2
            $service = new Google_Service_Oauth2($token['access_token']);
            $profile = $service->userinfo->get();

            // echo "<pre>";
            // print_r($profile);
            // echo "</pre>";

            $user_name      = $profile['name'];
            $user_email     = $profile['email'];
            $g_id = $profile['id'];
            $picture = $profile['picture'];

            $query_check = 'SELECT * FROM users WHERE oauth_id = "'.$g_id.'"';
            $run_query_check = mysqli_query($conn, $query_check);
            // $d = mysqli_fetch_array($run_query_check);

            // echo "<pre>";
            // print_r($user_name);
            // print_r($user_email);
            // print_r($g_id);
            // print_r($picture);
            // echo "</pre>";

            if($run_query_check){
                echo "Data sudah tersedia";
            }else {

                $query_insert = 'INSERT INTO users (fullname, email, picture, ouauth_id, last_login) value ("'.$user_name.'", "'.$user_email.'", "'.$picture.'", "'.$g_id.'")';
                $run_query_insert = mysqli_query($conn, $query_insert);
                if($run_query_insert){
                    echo "Data berhasil disimpan!";
                }
            }
            echo "Login berhasil";
        }else {
            echo "Login Gagal";
        }


    }
    
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="container w-50">
            <main>
                <form action="" class="container p-4 border rounded shadow mt-4" >
                    <div class="container text-center">
                        <h2 class="fw-bold">LOGIN</h2>
                        <small>Please login before we continue</small>
                    </div>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control h-25" id="floatingInput" placeholder="john@example.com" >
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control h-25" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="container mt-3 d-flex justify-content-center">
                        <a href="#" class="btn btn-success px-5">Login</a>
                    </div>
                    <div class="container d-flex gap-2 justify-content-center">
                        <a class="bi bi-google btn shadow text-white bg-primary mt-3" href="<?php echo $client->createAuthUrl(); ?>"> Login with Google</a>
                        <a class="bi bi-github btn shadow text-white bg-dark mt-3" href="#" > Login with Github</a>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>