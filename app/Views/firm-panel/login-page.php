<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="<?= base_url() ?>assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>assets/libs/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"><a href="<?= base_url('login') ?>"><img class="logo-img" src="<?= base_url() ?>assets/images/logo.png" alt="logo"></a><span class="splash-description">Giriş yapmak için kullanıcı bilgilerinizi girin.</span></div>
            <div class="card-body">
                
                <div class="form-group">
                    <input class="form-control form-control-lg" id="eMailAddress" type="text" placeholder="E-Posta Adresi" autocomplete="off">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" id="password" type="password" placeholder="Parola">
                </div>
                <div class="form-group">
                    <label class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox"><span class="custom-control-label">Bana Hatırlat</span>
                    </label>
                </div>
                <button type="button" onclick="loginAjax()" class="btn btn-primary btn-lg btn-block">Oturum Aç</button>
                
            </div>
            <div class="card-footer bg-white p-0  ">
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Yeni Hesap Oluştur</a></div>
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Parolamı Unuttum</a>
                </div>
            </div>
        </div>
    </div>
  
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="<?= base_url() ?>assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>

    <script>

        function sendAjaxRequest2(url,param1,param2) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {param1: param1, param2: param2},
                    success: function(response) {

                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });
        }

        function loginAjax(){

            var eMailAddress = $('#eMailAddress').val();
            var password = $('#password').val();

            sendAjaxRequest2("login", eMailAddress, password).then(function(response) {
                // İstek başarılı olduğunda burası çalışacak
                
                // RESPONSE 
                // OK
                // ERROR : HATA AÇIKLAMASI

                console.log(response);

            }).catch(function(error) {
                // İstek başarısız olduğunda burası çalışacak
                console.log("Hata: ", error);
            });

        }


    </script>

</body>
 
</html>