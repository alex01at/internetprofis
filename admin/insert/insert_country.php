<?php 
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login.php','_self');</script>";
}else{
?>
<div class="main-container">
    <div class="row">
        <!--- 2 row Starts --->
        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->
            <div class="card">
                <!--- card Starts --->
                <div class="card-header">
                    <!--- card-header Starts --->
                    <div class="card-header">
                        <div class="float-right">
                            <div class="page-title">
                                <ol class="text-right">
                                    <a href="index?countries" class="btn btn-danger">
                                        <i class="text-white"></i> <span class="text-white">Cancel</span>
                                    </a>
                                </ol>
                            </div>
                        </div>
                        <h4 class="h4"> <i class="fa fa-money-bill-alt"></i> Insert Country </h4>
                    </div>
                    <!--- card-header Ends --->
                    <div class="card-body">
                        <!--- card-body Starts --->
                        <form action="" method="post" enctype="multipart/form-data">
                            <!--- form Starts --->
                            <div class="form-group row">
                                <!--- form-group row Starts --->
                                <label class="col-md-3 control-label"> Country Name : </label>
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <!--- form-group row Starts --->
                                <label class="col-md-3 control-label"> Country Code : </label>
                                
                                <div class="col-md-6">
                                    <input type="text" name="code" class="form-control" required><p>only Numbers </p>
                                </div>
                            </div>
                            <!--- form-group row Ends --->
                            <div class="form-group row">
                                <!--- form-group row Starts --->
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    <input type="submit" name="insert" value="Insert Country"
                                        class="btn btn-success form-control">
                                </div>
                            </div>
                            <!--- form-group row Ends --->
                        </form>
                        <!--- form Ends --->
                    </div>
                    <!--- card-body Ends --->
                </div>
                <!--- card Ends --->
            </div>
            <!--- col-lg-12 Ends --->
        </div>
        <!--- 2 row Ends --->
    </div>
    <?php
if(isset($_POST['insert'])){
$name = $input->post('name');
$code = $input->post('code');
$insert_country = $db->insert("countries",array("name"=>$name, "code" =>$code));
if($insert_country){
echo "<script>alert_success('One Country Has Been Insertd.','index?countries');</script>";
}
}
?>
    <?php } ?>