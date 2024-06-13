function changeView(){

    var signInBox = document.getElementById("signIn_Box");
    var signUpBox = document.getElementById("signUp_Box");

    signInBox.classList.toggle("d-none");
    signUpBox.classList.toggle("d-none");

}

function signUp() {
    var fname = document.getElementById("fname");
    var lname = document.getElementById("lname");
    var email = document.getElementById("email");
    var password = document.getElementById("password");
    var mobile = document.getElementById("mobile");
    var gender = document.getElementById("gender");

    var f = new FormData();
    f.append("f",fname.value);
    f.append("l",lname.value);
    f.append("e",email.value);
    f.append("p",password.value);
    f.append("m",mobile.value);
    f.append("g", gender.value);

    
    var request = new XMLHttpRequest();

    request.onreadystatechange = function(){
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            if (response == "success") {
                swal("Good job!", "You Created An Account Successfully", "success");
                window.location = "index.php";

                }else{
                swal("Oops!", response, "error");
            }
        }
    };

    request.open("POST","signUpProcess.php",true);
    request.send(f);
}

//Sign In
function signIn() {

    var email = document.getElementById("email1");
    var password = document.getElementById("password1");
    var rememberme = document.getElementById("rememberme");

    var f = new FormData();
    f.append("e",email.value);
    f.append("pw",password.value);
    f.append("r",rememberme.checked);
    

    var request = new XMLHttpRequest();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var response = request.responseText;
        if (response == "success"){
            swal("Good job!", "Sign In", "success");
            window.location = "home.php";
        }else{
            swal("Oops!", response, "error");
        }
        }
    };

    request.open("POST","signInProcess.php",true);
    request.send(f);
}

function signout(){
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.status == 200 & request.readyState == 4) {
            var response = request.responseText;
            if (response == "success") {
                window.location.reload();
            }
        }
    }

    request.open("GET", "signOutProcess.php", true);
    request.send();
    
}

function showPassword2(){
    var textArea = document.getElementById("pw1");
    var show = document.getElementById("sp");

    if (textArea.type =="password") {
    textArea.type = "text";
    show.innerHTML="<i class='bi bi-eye-slash-fill'></i>";

    }else{
    textArea.type = "password";
    show.innerHTML="<i class='bi bi-eye-fill'></i>";

    }
}


function showPassword(){
    var textArea = document.getElementById("pw1");
    var show = document.getElementById("sp");

    if (textArea.type =="password") {
    textArea.type = "text";
    show.innerHTML="<i class='bi bi-eye-fill'></i>";

    }else{
    textArea.type = "password";
    show.innerHTML="<i class='bi bi-eye-slash-fill'></i>";

    }
}


var forgotPasswordModal;

function forgotPassword() {
    // alert("ok");
    var email = document.getElementById("email1");

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.status == 200 & request.readyState == 4) {
            var text = request.responseText;

            if (text == "Success") {
            swal("Good job!", "Verification code has sent successfully. Please check your Email.", "success");               
                var modal = document.getElementById("fpmodal");
                forgotPasswordModal = new bootstrap.Modal(modal);
                forgotPasswordModal.show();
            } else {
                swal("Oops!", text, "error");

            }

        }
    }

    request.open("GET", "forgotPasswordProcess.php?e=" + email.value, true);
    request.send();

}

function showPassword2() {

    var textfield = document.getElementById("password1");
    var button = document.getElementById("sp");

    if (textfield.type == "password") {
        textfield.type = "text";
        button.innerHTML = "<i class='bi bi-eye-fill'></i>";
    } else {
        textfield.type = "password";
        button.innerHTML = "<i class='bi bi-eye-slash-fill'></i>";
    }

}

function showPassword3() {

    var textfield = document.getElementById("np");
    var button = document.getElementById("npb");

    if (textfield.type == "password") {
        textfield.type = "text";
        button.innerHTML = "<i class='bi bi-eye-fill'></i>";
    } else {
        textfield.type = "password";
        button.innerHTML = "<i class='bi bi-eye-slash-fill'></i>";
    }

}

function showPassword4() {

    var textfield = document.getElementById("rnp");
    var button = document.getElementById("rnpb");

    if (textfield.type == "password") {
        textfield.type = "text";
        button.innerHTML = "<i class='bi bi-eye-fill'></i>";
    } else {
        textfield.type = "password";
        button.innerHTML = "<i class='bi bi-eye-slash-fill'></i>";
    }

}

function resetPassword() {

    var email = document.getElementById("email");
    var newPassword = document.getElementById("np");
    var retypePassword = document.getElementById("rnp");
    var verification = document.getElementById("vcode");

    var form = new FormData();
    form.append("e", email.value);
    form.append("n", newPassword.value);
    form.append("r", retypePassword.value);
    form.append("v", verification.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.status == 200 & request.readyState == 4) {
            var response = request.responseText;
            if (response == "success") {
                alert("Password updated successfully.");
                forgotPasswordModal.hide();
            } else {
                swal("Oops!", response, "error");
            }
        }
    }

    request.open("POST", "resetPasswordProcess.php", true);
    request.send(form);

}

function adminSignIn(){
    var email = document.getElementById("email");
    var password = document.getElementById("pw1");

    var f = new FormData();
    f.append("e",email.value);
    f.append("pw",password.value);
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if ((request.readyState == 4) & (request.status == 200)) {
            var response = request.responseText;
            // alert(response);
            if (response == "Success") {
                swal("HELLO !","Successfully Login", "success");
                window.location = "adminDashboard.php";
            } else {
            swal("Oops!", response, "error");
    
            }
        }
    };

    request.open("POST", "adminSignInProcess.php", true);
    request.send(f);
}

function adminSignout(){
    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.status == 200 & request.readyState == 4) {
            var response = request.responseText;
            if (response == "success") {
                window.location.reload();
            }
        }
    }

    request.open("GET", "adminSignOutProcess.php", true);
    request.send();
    
}
function loadUser() {

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);
            document.getElementById("tb").innerHTML = response;
        }
    }

    request.open("POST", "loadUserProcess.php", true);
    request.send();
}

function updateUserStatus() {
    var userid = document.getElementById("uid");
    // alert(userid.value);

    var f = new FormData();
    f.append("u", userid.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);

            if (response == "Deactive") {
            swal("Success!", "User Deactivate Successfully", "success");
                userid.value = "";
                window.location.reload();

            } else if (response == "Active") {
            swal("Success!", "User Activate Successfully", "success");
                userid.value = "";
                window.location.reload();

            } else {
            swal("Oops!", response, "error");
            }
        }
    }

    request.open("POST", "updateUserStatusProcess.php", true);
    request.send(f);

}

// Search User 

function searchUser(){
    // alert("ok"); 

    var user = document.getElementById("user");

    var f = new FormData();
    f.append("user",user.value);
     
    // alert(user.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function(){
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            // alert(response);
            document.getElementById("tb").innerHTML = response;        
        }
    };

    request.open("POST","userSearchProcess.php",true);
    request.send(f);
}

function addBrand() {
    var brand = document.getElementById("bName");

    var f = new FormData();
    f.append("b", brand.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);

            if (response == "Success") {
                swal("Good job!", "Successfully Added", "success");
                brand.value ="";                
            } else {
            swal("Oops!", response, "error");                
            }
        }
    };

    request.open("POST", "brandRegisterProcess.php", true);
    request.send(f);

}

function addCategory() {
    var category = document.getElementById("catName");

    var f = new FormData();
    f.append("cat", category.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);

            if (response == "Success") {
                swal("Good job!", "Successfully Added", "success");
                category.value ="";
            } else {
            swal("Oops!", response, "error");                
            }
        }
    };

    request.open("POST", "categoryRegisterProcess.php", true);
    request.send(f);

}

function addSize() {
    var size = document.getElementById("size");

    var f = new FormData();
    f.append("s", size.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);

            if (response == "Success") {
                swal("Good job!", "Successfully Added", "success");
                size.value ="";                
            } else {
            swal("Oops!", response, "error");                
            }
        }
    };

    request.open("POST", "sizeRegisterProcess.php", true);
    request.send(f);
}

function addColor() {
    var clr = document.getElementById("clr");

    var f = new FormData();
    f.append("col", clr.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);

            if (response == "Success") {
                swal("Good job!", "Successfully Added", "success");
                clr.value ="";                
            } else {
            swal("Oops!", response, "error");                
            }
        }
    };

    request.open("POST", "clrRegisterProcess.php", true);
    request.send(f);
}

function changeStockView(){

    var productReg = document.getElementById("reg");
    var stockUpdate = document.getElementById("update");

    productReg.classList.toggle("d-none");
    stockUpdate.classList.toggle("d-none");

}

function regProduct(){
    // alert("ok");
    var pname = document.getElementById("pname");
    var brand = document.getElementById("brand");
    var cat = document.getElementById("cat");
    var color = document.getElementById("color");
    var size = document.getElementById("size");
    var desc = document.getElementById("desc");
    var file = document.getElementById("file");

    var f = new FormData();
    f.append("pname", pname.value);
    f.append("brand", brand.value);
    f.append("cat", cat.value);
    f.append("color", color.value);
    f.append("size", size.value);
    f.append("desc", desc.value);
    f.append("image", file.files[0]);


    var req = new XMLHttpRequest();
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            var resp = req.responseText;
            // alert(resp);
            if(resp == "success"){
                swal("Success!", "Successfully Registered", "success");
                window.location.reload();
            }else{
            swal("Oops!", resp, "error");                
            }
        }
    }
    req.open("POST", "productRegProcess.php", true);
    req.send(f);

}

function updateStock() {
    // alert("ok");

    var selectProduct = document.getElementById("productSelct");
    var qty = document.getElementById("qty");
    var price = document.getElementById("price");

    var f = new FormData();
    f.append("sp",selectProduct.value);
    f.append("q",qty.value);
    f.append("p",price.value);

    // alert(qty.value);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            // alert(response);
            if (response == "success" || response == "New Stock Added Successfully") {
                swal("Success!", response , "success");
                selectProduct.value="";
                qty.value="";
                price.value="";
                                
            }else{
            swal("Oops !", response , "error");
            }
        }
    }

    request.open("POST","updateStockProcess.php",true);
    request.send(f);
}

function loadProduct(x){
    var page = x;
    // alert(x); 
    
    var f = new FormData();
    f.append("p", page);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);
            document.getElementById("pid").innerHTML = response;
        }
    }

    request.open("POST", "loadProductProcess.php", true);
    request.send(f);
}

// Product Search
function searchProduct(x) {

    var page = x;
    var product = document.getElementById("sProduct");

    // alert(page);
    // alert(product.value);

    var f = new FormData();
    f.append("p", product.value);
    f.append("pg", page);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);
            document.getElementById("pid").innerHTML = response;
        }
    }
    request.open("POST", "searchProductProcess.php", true);
    request.send(f);
}



function advSearch() {
// alert ("ok");
    var filterElement = document.getElementById("filterId");
    var currentClass = filterElement.className;

    if (currentClass.includes("d-block")) {
        filterElement.className = "d-none";
    } else {
        filterElement.className = "d-block";
    }

}

// advance search 
function advSearchProduct(x) {
    // alert("ok");
    var page = x;
    var color = document.getElementById("color");
    var cat = document.getElementById("cat");
    var brand = document.getElementById("brand");
    var size = document.getElementById("size");
    var min = document.getElementById("min");
    var max = document.getElementById("max");

    var f = new FormData();
    f.append("pg", page);
    f.append("co", color.value);
    f.append("cat", cat.value);
    f.append("b", brand.value);
    f.append("s", size.value);
    f.append("min", min.value);
    f.append("max", max.value);


    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);
            document.getElementById("pid").innerHTML = response;

            color.value = "0";
            cat.value = "0";
            brand.value = "0";
            size.value = "0";
            min.value = "";
            max.value = "";
        }
    };

    request.open("POST", "advSearchProductProcess.php", true);
    request.send(f);
}
// advance search 


function uploadImg() {
    var img = document.getElementById("imgUploader");

    var f = new FormData();
    f.append("i", img.files[0]);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if ((request.readyState == 4) & (request.status == 200)) {
            var response = request.responseText;
            // alert(response);
            if (response == "empty") {
                swal("OOPS !","Please Select your Profile Image","error");
            } else if (response !== "success") {
                window.location.reload();
            } else {
                document.getElementById("i").src = response;
                img.value = "";
            }
        }
    };

    request.open("POST", "profileImgUploadProcess.php", true);
    request.send(f);
}

function updateData() {
    var fname = document.getElementById("fname");
    var lname = document.getElementById("lname");
    var email = document.getElementById("email");
    var mobile = document.getElementById("mobile");
    var pw = document.getElementById("pw");
    var no = document.getElementById("no");
    var line1 = document.getElementById("line1");
    var line2 = document.getElementById("line2");

    var f = new FormData();
    f.append("f", fname.value);
    f.append("l", lname.value);
    f.append("e", email.value);
    f.append("m", mobile.value);
    f.append("p", pw.value);
    f.append("n", no.value);
    f.append("l1", line1.value);
    f.append("l2", line2.value);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            swal(response);
        }
    }

    request.open("POST", "updateDataProcess.php", true);
    request.send(f);
}

function viewShip() {
    // alert ("ok");
        var viewShip = document.getElementById("ship");
        var currentClass = viewShip.className;
    
        if (currentClass.includes("d-block")) {
            viewShip.className = "d-none";
        } else {
            viewShip.className = "d-block";
        }
    
    }

function addtoCart(x) {

    // alert(x);

    var stockId = x;
    var qty = document.getElementById("qty");

    if (qty.value <= 0) {
        swal("Ooops!!","Please Enter Quantity Valid","error")
    } else if (qty.value != "") { 

        var f = new FormData();
        f.append("s", stockId);
        f.append("q", qty.value);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 & request.status == 200) {
                var response = request.responseText;
                swal(response);
                qty.value = "";
            }
        }

        request.open("POST", "addtoCartProcess.php", true);
        request.send(f);

    } else {
        swal("OOps","Please Enter Your Quantity",error);
    }

}

function loadCart() {
    //alert("OK");

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            //alert(response);
            document.getElementById("cartBody").innerHTML = response;
        }
    }

    request.open("POST", "loadCartProcess.php", true);
    request.send();
}

function incrementCartQty(x) {

    //alert(x);

    var cardId = x;
    var qty = document.getElementById("qty" + x);
    //alert(qty.value);

    var newQty = parseInt(qty.value) + 1; //integer
    //alert(newQty);

    var f = new FormData();
    f.append("c", cardId);
    f.append("q", newQty);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            //alert(response);

            if (response == "Success") {
                qty.value = parseInt(qty.value) + 1;
                loadCart();
            } else {
                swal("Ooops!", response , "error");
            }
        }
    }

    request.open("POST", "updateCartQtyProcess.php", true);
    request.send(f);


}

function decrementCartQty(x) {
    //alert(x);

    var cardId = x;
    var qty = document.getElementById("qty" + x);

    var newQty = parseInt(qty.value) - 1; //integer
    //alert(newQty);

    var f = new FormData();
    f.append("c", cardId);
    f.append("q", newQty);

    if (newQty > 0) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 & request.status == 200) {
                var response = request.responseText;
                //alert(response);

                if (response == "Success") {
                    qty.value = parseInt(qty.value) - 1;
                    loadCart();
                } else {
                    swal("Ooops!", response , "error");

                }
            }
        }

        request.open("POST", "updateCartQtyProcess.php", true);
        request.send(f);
    }


}

function removeCart(x) {
    //alert(x);

    if (confirm("Are You Suer Deleting This Item?")) {

        var f = new FormData();
        f.append("c", x);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 & request.status == 200) {
                var response = request.responseText;
                swal(response);
                window.location.reload();
            }
        }


        request.open("POST","removeCartProcess.php", true);
        request.send(f);

    }

}

function checkOut() {

    // alert("OK");

    var f = new FormData();
    f.append("cart", true);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 & request.status == 200) {
            var response = request.responseText;
            // alert(response);
            var payment = JSON.parse(response);
            doCheckout(payment, "checkoutProcess.php");
        }
    };

    request.open("POST", "paymentProcess.php");
    request.send(f);
}


function doCheckout(payment, path) {
    // Payment completed. It can be a successful failure.
    payhere.onCompleted = function onCompleted(orderId) {
        console.log("Payment completed. OrderID:" + orderId);
        // Note: validate the payment and show success or failure page to the customer

        var f = new FormData();
        f.append("payment", JSON.stringify(payment));

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if ((request.readyState == 4) & (request.status == 200)) {
                var response = request.responseText;
                // alert(response);

                var order = JSON.parse(response);

                if (order.resp == "Success") {
                    // location.reload();
                    window.location = "invoice.php?orderId=" + order.order_id;

                } else {
                    alert(response);
                }
            }
        };
        request.open("POST", path, true);
        request.send(f);
    };

    // Payment window closed
    payhere.onDismissed = function onDismissed() {
        // Note: Prompt user to pay again or show an error page
        console.log("Payment dismissed");
    };

    // Error occurred
    payhere.onError = function onError(error) {
        // Note: show an error page
        console.log("Error:" + error);
    };

    // Show the payhere.js popup, when "PayHere Pay" is clicked
    // document.getElementById('payhere-payment').onclick = function (e) {
    payhere.startPayment(payment);
    // };
}


function buyNow(stockId) {
    var qty = document.getElementById("qty");

    if (qty.value > 0) {
        var f = new FormData();
        f.append("cart", false);
        f.append("stockId", stockId);
        f.append("qty", qty.value);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if ((request.readyState == 4) & (request.status == 200)) {
                var response = request.responseText;
                // alert(response);
                var payment = JSON.parse(response);
                payment.stock_id = stockId;
                payment.qty = qty.value;
                doCheckout(payment, "buynowProcess.php");
            }
        };
        request.open("POST", "paymentProcess.php", true);
        request.send(f);
    } else {
        Swal.fire({
            title: "Error",
            text: "Please enter a valid quantity",
            icon: "error",
        });
    }
}

function themeChange() {
    // alert("ok");
    var body = document.body;
    body.dataset.bsTheme = body.dataset.bsTheme == "light" ? "dark" : "light";
}

function loadChart() {

    var ctx = document.getElementById("myChart");

    var f = new FormData();

    f.append("ctx", ctx.value)

    var r = new XMLHttpRequest();

    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var t = r.responseText;
            var data = JSON.parse(t);


            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: '# Sales',
                        data: data.data,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // alert(t);

        }
    }

    r.open("POST", "loadChartProcess.php", true);
    r.send(f);

    // alert("hello");
}

function loadChart2() {
    var ctx = document.getElementById("myChart2");
    var f = new FormData();
    f.append("ctx", ctx.value)

    var r = new XMLHttpRequest();

    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var t = r.responseText;
            var data = JSON.parse(t);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'Daily Income',
                        data: data.incomes,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            document.getElementById("total-amount").innerHTML = "Total Amount: " + data.total_amount;
        }
    }

    r.open("POST", "loadChartProcess2.php", true);
    r.send(f);
}

function loadChart3() {

    var ctx = document.getElementById("myChart3");

    var f = new FormData();

    f.append("ctx", ctx.value)

    var r = new XMLHttpRequest();

    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var t = r.responseText;
            var data = JSON.parse(t);


            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: '# Sales',
                        data: data.data,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // alert(t);

        }
    }

    r.open("POST", "loadChartProcess3.php", true);
    r.send(f);

    // alert("hello");
}

function chnagepw() {
    var fpassword = document.getElementById("fpModal4");
    
    fpModal = new bootstrap.Modal(fpassword);
    fpModal.show();
}
    
function resetPassword2() {
    
    var op1 = document.getElementById("opw");
    var np1 = document.getElementById("newpw1");
    var np2 = document.getElementById("renewpw1");
    
    if (np2.value == np1.value) {
        var f = new FormData();
    
        f.append("op1", op1.value);
        f.append("n1", np1.value);

        // alert(op1.value);
        // alert(np2.value);
    
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                var response = request.responseText;
            if (response.trim() === "Success") {
            swal("Update Successful",response,"success");            
                   
            } else {                        
            swal("Update Failed",response,"error");            

                   
            }
            }
            };
    
            request.open("POST", "changePasswordProcess.php", true);
            request.send(f);
        } else {
            swal("Password is not Matched","Retype Password Again","error");            
        }
}

function showpw3() {
    var pw3 = document.getElementById("newpw1");
    var stb3 = document.getElementById("spb3");

    if (pw3.type == "password") {
        pw3.type = "text";
        stb3.innerHTML = '<i class="bi bi-eye"></i>';
    } else {
        pw3.type = "password";
        stb3.innerHTML = '<i class="bi bi-eye-slash"></i>';
    }
}

function showpw4() {
    var pw4 = document.getElementById("renewpw1");
    var stb4 = document.getElementById("spb4");

    if (pw4.type == "password") {
        pw4.type = "text";
        stb4.innerHTML = '<i class="bi bi-eye"></i>';
    } else {
        pw4.type = "password";
        stb4.innerHTML = '<i class="bi bi-eye-slash"></i>';
    }
}

function showpw5() {
    var pw4 = document.getElementById("opw");
    var stb5 = document.getElementById("spb5");

    if (pw4.type == "password") {
        pw4.type = "text";
        stb5.innerHTML = '<i class="bi bi-eye"></i>';
    } else {
        pw4.type = "password";
        stb5.innerHTML = '<i class="bi bi-eye-slash"></i>';
    }
}

function alert5() {
    // alert("ok");
   swal("opps","Login First","error");

}

function shipAdd() {
    var shipping = document.getElementById("staticBackdrop");
    
    fpModal = new bootstrap.Modal(shipping);
    fpModal.show();
    
}

function setAddress() {
    // alert("ok");
    var no1 = document.getElementById("no1");
    var line1 = document.getElementById("line1");
    var line2 = document.getElementById("line2");

    var f = new FormData();
    f.append("no",no1.value);
    f.append("l1",line1.value);
    f.append("l2",line2.value);

    // alert(line1.value);

    var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if ((request.readyState == 4) & (request.status == 200)) {
                var response = request.responseText;
                // alert(response);
                if (response == "success") {
                    swal("Updated","Set Your Shipping Address","success")
                } else {
                    swal("Something Went Wrong",response,"error");
                }
            }
        };
        request.open("POST", "shippingAddressProcess.php", true);
        request.send(f);
}