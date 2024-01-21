    <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                             Copyright © 2018 Concept. All rights reserved. Dashboard by <a href="https://colorlib.com/wp/">Colorlib</a>.
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="<?= base_url() ?>assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="<?= base_url() ?>assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="<?= base_url() ?>assets/libs/js/main-js.js"></script>
    <!-- chart chartist js -->
    <script src="<?= base_url() ?>assets/vendor/charts/chartist-bundle/chartist.min.js"></script>
    <!-- sparkline js -->
    <script src="<?= base_url() ?>assets/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <!-- morris js -->
    <script src="<?= base_url() ?>assets/vendor/charts/morris-bundle/raphael.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/charts/morris-bundle/morris.js"></script>
    <!-- chart c3 js -->
    <script src="<?= base_url() ?>assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/charts/c3charts/C3chartjs.js"></script>
    <script src="<?= base_url() ?>assets/libs/js/dashboard-ecommerce.js"></script>

    <script>

    function reloadDatatable(dtName,type){

        if(type == 1){
            $(`#${dtName}`).DataTable().ajax.reload(null,false);
        }
        else{
            $(`#${dtName}`).DataTable().ajax.reload();
        }
        
    
    }

    function uploadFiles(fileInputId, imagePathInputName, allowedExtensions,hashInput,uploadPlace,buttonName, startNumber = 0) {

        

        const fileInput = document.getElementById(fileInputId);
        const files = fileInput.files;
        const allowedExtensionsArray = allowedExtensions.split(',').map(ext => ext.trim().toLowerCase());
        
        var continueButton = document.getElementById(`${buttonName}`); // İlerleme düğmesinin HTML öğesini seçin

        continueButton.style.display = "none"; // Düğmeyi görünür yap


        setTimeout(() => {
            
            continueButton.style.display = "block"; // Düğmeyi görünür yap

        }, 7000);
        

        var status = 0;


        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensionsArray.includes(fileExtension)) {
                console.error("Dosya uzantısı desteklenmiyor: " + file.name);
                continue;
            }
            var uniqHashInputName = $(`#${hashInput}`).val();
            
            const formData = new FormData();
            formData.append("postFile", file);
            formData.append("acceptedFileTypes", allowedExtensionsArray.join(','));
            formData.append("type", 1);
            formData.append("uniqHash",uniqHashInputName);
            formData.append("uploadPlace",uploadPlace);
            

            fetch("/portal/ajax/upload-files", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                
                if (data.status === 'OK' && data.result.length > 0) {
                    
                    var resultVal = data.result;
                    resultVal = resultVal.split("/");
                    
                    $(`#${imagePathInputName+startNumber}`).val(resultVal[2]);

                    startNumber++;


                    // İlgili resim önizleme alanında kullanmak için path bilgisini burada işleyebilirsiniz.
                    // imagePath değişkeni, i. dosyanın dönen path bilgisini içerir.
                } else {
                    console.error("Dosya yüklenirken hata oluştu: " + file.name);
                }
            })
            .catch(error => console.log(error));

            if(i == files.length - 1) {
                status = 1;
            }
        }



        var intervalID = setInterval(() => {
            
        }, 100);

        

        console.log(startNumber);

        

        
    }
    function uploadFile(fileItem,pathItem,fileTypes){

        var files = $(`#${fileItem}`)[0].files;
        var url = "/portal/ajax/upload-file";

        if(files.length > 0){
            var fd = new FormData();

            // Append data

            fd.append('postFile',files[0]);
            fd.append('acceptedFileTypes',fileTypes);
            fd.append('type',1);

            $.ajax({
                url: url,
                method: 'post',
                data: fd,
                contentType: false,
                processData: false,
                 
                success: function(response) {
                
                    if (response.status === 'OK') {
                        var value = response.result;
                        $(`#${pathItem}`).val(value);
                        uploadFileCheck(response.result,pathItem);
                        
                        
                    
                    }
                    else{

                        uploadFileCheck(response.status,pathItem);
                        

                    }
                },

                error: function(xhr, status, error) {
                    toastr.error(error);
                
                }
            });
        }
    }

    function uploadPrivate(fileItem,pathItem,fileTypes){

        var files = $(`#${fileItem}`)[0].files;
        var url = "/portal/ajax/upload-file";

        if(files.length > 0){
            var fd = new FormData();

            // Append data

            fd.append('postFile',files[0]);
            fd.append('acceptedFileTypes',fileTypes);
            fd.append('type',2);

            $.ajax({
                url: url,
                method: 'post',
                data: fd,
                contentType: false,
                processData: false,
                 
                success: function(response) {
                
                    if (response.status === 'OK') {
                        var value = response.result;
                        $(`#${pathItem}`).val(value);
                        uploadFileCheck(response.result,pathItem);
                        
                        
                    
                    }
                    else{

                        uploadFileCheck(response.status,pathItem);
                        

                    }
                },

                error: function(xhr, status, error) {
                    toastr.error(error);
                
                }
            });
        }
    }

    function uploadImage(imageItem,pathItem){
        //var files = $('#sliderFile')[0].files;

        var files = $(`#${imageItem}`)[0].files;
        var url = "/portal/ajax/upload-image";
        
        if(files.length > 0){
            
            var fd = new FormData();
                                
            // Append data 
            fd.append('imageFile',files[0]);
                         
            $.ajax({
                url: url,
                method: 'post',
                data: fd,
                contentType: false,
                processData: false,
                 
                success: function(response) {
                
                    if (response.status === 'OK') {
                        var deger = response.result;
                        $(`#${pathItem}`).val(deger);
                        uploadImageCheck(response.result,pathItem);
                        
                        
                    
                    }
                    else{

                        uploadImageCheck(response.status,pathItem);
                        

                    }
                },

                error: function(xhr, status, error) {
                    toastr.error(error);
                
                }
            });




            
        }
    }

    function uploadImageCheck(path,pathItem){
        if(path == "NO"){
            toastr.error("Yüklemeye çalıştığınız dosya, sistemin izin vermediği bir dosya olarak algılandı. Lütfen dosyanızı kontrol ettikten sonra, işlemi tekrar yapmayı deneyin.");
        }
        else if(path == "ERROR"){
            toastr.error("Dosyanız yüklenirken sistemsel bir sorun ile karşılaşıldı. Lütfen ilerleyen zamanda tekrar deneyiniz.");
        }
        else if(path == "MAX"){
            toastr.error("Yüklediğiniz dosya, sistemin belirlediği dosya boyutundan büyük! Lütfen daha düşük boyutlu bir dosya yüklemeyi deneyin.");
        }
        else{

            toastr.warning("Resim dosyası yükleniyor. Lütfen bekleyin...");
            $(`#${pathItem}`).val(path);

            
        }
    }

    function uploadFileCheck(path,pathItem){
        if(path == "NO"){
            toastr.error("Yüklemeye çalıştığınız dosya, sistemin izin vermediği bir dosya olarak algılandı. Lütfen dosyanızı kontrol ettikten sonra, işlemi tekrar yapmayı deneyin.");
        }
        else if(path == "ERROR"){
            toastr.error("Dosyanız yüklenirken sistemsel bir sorun ile karşılaşıldı. Lütfen ilerleyen zamanda tekrar deneyiniz.");
        }
        else if(path == "MAX"){
            toastr.error("Yüklediğiniz dosya, sistemin belirlediği dosya boyutundan büyük! Lütfen daha düşük boyutlu bir dosya yüklemeyi deneyin.");
        }
        else{

            toastr.warning("Dosya yükleniyor. Lütfen bekleyin...");
            toastr.error(path);
            $(`#${pathItem}`).val(path);

            
        }
    }

    function sendAjaxRequest(url) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: url,
                method: "POST",
                data: {param1: "1"},
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    function sendAjaxRequest1(url,param1) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: url,
                method: "POST",
                data: {param1: param1},
                success: function(response) {

                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    function sendAjaxRequest5(url,param1,param2,param3,param4,param5) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: url,
                method: "POST",
                data: {param1: param1,param2: param2,param3: param3,param4: param4,param5: param5},
                success: function(response) {

                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    function sendAjaxRequestMulti(url, params, modalName, datatableName, params2 = []) {
        var requestData = {};
       

        // Parametreleri requestData nesnesine ekleyelim
        for (var i = 0; i < params.length; i++) {
            //console.log(params[i]);
            //var input = document.getElementById(params[i]);
            
            requestData[params[i]] = $(`#${params[i]}`).val();
    
        }

        for (var i = 0; i < params2.length; i++) {
            //console.log(params[i]);
            //var input = document.getElementById(params[i]);
            
            requestData[params2[i]] = $(`#${params2[i]}`).val();
    
        }

        var status = $.ajax({
            url: url,
            method: "POST",
            data: requestData,
            success: function(response) {
                console.log(response);
                if (response.status === 'OK') {
                    var result = response.result;

                    if(modalName == ""){

                    }else{
                        $(`#${modalName}`).modal('hide');
                        $(`#${datatableName}`).DataTable().ajax.reload();
                    }

                    

                    toastr.success(response.message);

                    
                } else if(response.status === "BOS"){
                    
                    var errorMessage = response.result;
                    toastr.error(errorMessage);
                    
                }
                else if(response.status === "ERROR"){
                    
                    var errorMessage = response.result;
                    toastr.error(errorMessage);
                    
                }
            },
            error: function(xhr, status, error) {
                toastr.error(error);
                
            }
        });
        
    }

    function sendAjaxRequestAuto(url, params) {
        return new Promise(function(resolve, reject) {
            var requestData = {};
       

            // Parametreleri requestData nesnesine ekleyelim
            for (var i = 0; i < params.length; i++) {
                //console.log(params[i]);
                //var input = document.getElementById(params[i]);
            
                requestData[params[i]] = $(`#${params[i]}`).val();
    
            }

            var status = $.ajax({
                url: url,
                method: "POST",
                data: requestData,
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
        
    }

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

    function sendAjaxRequest3(url,param1,param2,param3) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: url,
                method: "POST",
                data: {param1: param1, param2: param2, param3: param3},
                success: function(response) {

                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    function sendAjaxRequest4(url,param1,param2,param3,param4) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: url,
                method: "POST",
                data: {param1: param1, param2: param2, param3: param3, param4: param4},
                success: function(response) {

                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }


    </script>
</body>
 
</html>