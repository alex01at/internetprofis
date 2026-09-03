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
                    <div class="page-title">
                        <ol class="text-right">
                            <li class="active">
                                <a href="index?insert_word" class="btn btn-success">
                                    <i class="fa fa-plus-circle text-white"></i>
                                    <span class="text-white">Add New Word</span>
                                </a>
                            </li>
                        </ol>
                    </div>
                    <h4 class="h4">
                        <i class="fa fa-money-bill-alt"></i> View Words
                    </h4>
                </div>
                <!--- card-header Ends --->
                <div class="card-body">
                    <!--- card-body Starts --->
                    <table class="table table-bordered table-hover">
                        <!--- table table-bordered table-hover Starts --->
                        <thead>
                            <tr>
                                <th>Country Id</th>
                                <th>Country Code</th>
                                <th>Country Name</th>
                                
                                <th>Delete Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--- tbody Starts --->
                            <?php
                            $i=0;
                            $get_countries = $db->select("countries");
                            while($row_countries = $get_countries->fetch()){
                            
                            $id = $row_countries->id;
                            $country = $row_countries->name;
                            $code = $row_countries->code;
                            $i++;
                            ?>
                            <tr>
                                <td><?php $i; ?></td>
                                <td><?= $code; ?></td>
                                <td><?= $country; ?></td>
                               
                                <td>
                                    <a href="#" class="btn btn-danger text-white"
                                        onclick="alert_confirm('Do you really want to delete this Word permanently.','index?delete_word=<?= $id; ?>');">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <!--- tbody Ends --->
                    </table>
                    <!--- table table-bordered table-hover Ends --->
                </div>
                <!--- card-body Ends --->
            </div>
            <!--- card Ends --->
        </div>
        <!--- col-lg-12 Ends --->
    </div>
    <!--- 2 row Ends --->
</div>
<?php } ?>