<?php 
$page_title ="Registration Form";
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card-shadow">
                    <div class="card-header">
                        <h5>Registration Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="form-group">
                                <label for="studentname">Student Name</label>
                                <input type="text" name='name' class='form-control'>
                            </div>
                            <div class="form-group">
                                <label for="emailAddress">Email Address</label>
                                <input type="text" name='email' class='form-control'>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" name='password' class='form-control'>
                                <div class="form-group">
                                <label for="confirmpassword">Confirm Password</label>
                                <input type="text" name='confirmpassword' class='form-control'>
                            </div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name='phone' class='form-control'>
                            </div>
                            <div class="form-group" style="margin-top: 20px;">
                                <button class="button" name="register_btn" type="submit" class="btn-btn-primary">Register Now</button>
                                

                            </div>


                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php include('includes/footer.php'); ?>



