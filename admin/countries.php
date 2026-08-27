<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login.php','_self');</script>";
}else{
?>
<div class="main-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="page-title">
                        <ol class="text-right">
                            <li class="active">
                                <a href="index?insert_country" class="btn btn-success">
                                    <i class="fa fa-plus-circle text-white"></i>
                                    <span class="text-white">Add New Country</span>
                                </a>
                            </li>
                        </ol>
                    </div>
                    <h4 class="h4">
                        <i class="fa fa-money-bill-alt"></i> View Countries
                    </h4>
                </div>
                <div class="card-body">
                    <form id="delete-country-form" method="post" action="index?delete_country">  <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Country Id</th>
                                <th>Country Code</th>
                                <th>Country Name</th>
                                <th>Delete Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $get_countries = $db->select("countries");
                            while ($row_countries = $get_countries->fetch()) {

                                $id = $row_countries->id;
                                $country = $row_countries->name;
                                $code = $row_countries->code;
                                $i++;
                                ?>
                                <tr>
                                   
                                    <td><?= $i; ?></td>
                                    <td><?= $code; ?></td>
                                    <td><?= $country; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-danger text-white"
                                            onclick="alert_confirm('Do you really want to delete this Country permanently?','index?delete_country=<?= $id; ?>');">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        </table>
                  </form>  </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>