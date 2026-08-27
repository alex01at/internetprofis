<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{
$get_app = $db->select("app_info");
$row_app = $get_app->fetch();
$current_version = $row_app->version;
?>
<div class="main-container">
  <div class="row mb-4">
    <!--- 2 row Starts --->
    <div class="col-lg-12">
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts --->
        <div class="card-header">
          <!--- card-header Starts --->
          <ol class="text-right">
            <a href="index?plugins" class="btn btn-danger">
              <i class="text-white"></i> <span class="text-white">Back</span>
            </a>
          </ol>
          <h4 class="h4 mb-0"> <i class="fa fa-money fa-fw"></i> Upload Plugin </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body p-0">
          <!--- card-body Starts --->
          <form action="" method="post" enctype="multipart/form-data">
            <!--- form Starts --->
            <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-3">
              <!--- form-group row Starts --->
              <label class="col-md-6 control-label"> Upload File: </label>
              <div class="col-md-6">
                <input type="file" required="" class="form-control form-control-sm mt-0" name="zip_file" accept=".zip">
                <small class="text-muted">See <a href="https://github.com/alex01at/internetprofis/blob/main/plugins/README.md" target="_blank">plugins/README.md</a> for the required package format.</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <hr class="mt-0 mb-3">
            <div class="form-group row mb-4">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"></label>
              <div class="col-md-6 pl-4 pr-4">
                <input type="submit" name="uploadPlugin" value="Install Now" class="btn btn-success form-control">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends --->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!--- 2 row Ends --->
</div>
<!--- container pt-3 Ends --->
<?php
  function getStringBetween($string, $start, $end, $index=1){
      if ($index <= 0) return '';
      $string = ' ' . $string;
      $ini = 0;
      $x = 1;
      while ($x <= $index) {
        $ini = strpos($string, $start, $ini + 1);
        if ($ini == 0) return '';
        $x++;
      }
      $ini += strlen($start);
      $len = strpos($string, $end, $ini) - $ini;
      $string = substr($string, $ini, $len);
      return str_replace(array("\n","\r"),"", $string);
  }

  function rrmdir($path) {
    if(!is_dir($path)){ return; }
    $i = new DirectoryIterator($path);
    foreach($i as $f) {
      if($f->isFile()) {
        unlink($f->getRealPath());
      } else if(!$f->isDot() && $f->isDir()) {
        rrmdir($f->getRealPath());
      }
    }
    @rmdir($path);
  }

  // Rejects any zip entry that could write outside $destDir (zip slip / path
  // traversal), and optionally enforces that every entry lives under
  // $requiredPrefix and has an allowed extension. Validates every entry
  // BEFORE extracting anything, so a bad archive never touches disk.
  function validateAndExtractZip($zipPath, $destDir, $requiredPrefix = null, $allowedExtensions = null){
    $zip = new ZipArchive;
    if($zip->open($zipPath) !== true){
      return "Could not open the zip file.";
    }

    $destReal = realpath($destDir);
    if($destReal === false){
      @mkdir($destDir, 0755, true);
      $destReal = realpath($destDir);
    }
    if($destReal === false){
      $zip->close();
      return "Destination folder could not be created.";
    }

    for($i = 0; $i < $zip->numFiles; $i++){
      $name = $zip->getNameIndex($i);

      if($name === false || $name === ''){ continue; }
      if(strpos($name, "\0") !== false){ $zip->close(); return "Invalid entry name."; }
      if($name[0] === '/' || preg_match('#^[a-zA-Z]:#', $name)){ $zip->close(); return "Absolute paths are not allowed in the archive."; }
      $normalized = str_replace('\\', '/', $name);
      $parts = explode('/', $normalized);
      if(in_array('..', $parts, true)){ $zip->close(); return "Path traversal detected in archive entry: $name"; }

      $basename = basename($normalized);
      if($basename !== '' && $basename[0] === '.'){ $zip->close(); return "Hidden/dotfiles are not allowed in the archive: $name"; }

      if($requiredPrefix !== null && substr($normalized, -1) !== '/'){
        if(strpos($normalized, $requiredPrefix.'/') !== 0){
          $zip->close();
          return "Archive contains a file outside the expected folder ($requiredPrefix/): $name";
        }
      }

      if($allowedExtensions !== null && substr($normalized, -1) !== '/'){
        $ext = strtolower(pathinfo($normalized, PATHINFO_EXTENSION));
        if(!in_array($ext, $allowedExtensions, true)){
          $zip->close();
          return "File type not allowed in plugin package: $name";
        }
      }

      // Final sanity check: the resolved target must stay inside $destDir.
      $targetPath = $destDir.'/'.$normalized;
      $targetDir = dirname($targetPath);
      @mkdir($targetDir, 0755, true);
      $targetDirReal = realpath($targetDir);
      if($targetDirReal === false || strpos($targetDirReal, $destReal) !== 0){
        $zip->close();
        return "Archive entry resolves outside the destination folder: $name";
      }
    }

    $zip->extractTo($destDir);
    $zip->close();
    return true;
  }

  // A plugin only needs to create/alter its own tables and register itself
  // in `plugins` -- it never needs these, so block them as a safety net on
  // top of the zip-slip protection above.
  function containsDangerousSql($sql){
    $blocked = ['DROP DATABASE', 'GRANT ', 'LOAD_FILE', 'INTO OUTFILE', 'INTO DUMPFILE', 'LOAD DATA'];
    $upper = strtoupper($sql);
    foreach($blocked as $needle){
      if(strpos($upper, $needle) !== false){ return true; }
    }
    return false;
  }

  if(isset($_POST["uploadPlugin"])){

    $zip_file = $_FILES['zip_file']['name'];
    $zip_file_tmp = $_FILES['zip_file']['tmp_name'];
    $allowed = array('zip');
    $file_extension = strtolower(pathinfo($zip_file, PATHINFO_EXTENSION));

    if(!in_array($file_extension,$allowed) || empty($zip_file)){
      echo "<script>alert_error('You can only upload a zipped folder.','index?add_plugin');</script>";
    }else{

      $zip_file = 'upload_'.bin2hex(random_bytes(8)).'.zip';

      if(move_uploaded_file($zip_file_tmp,"files/$zip_file")){
        $zip = "files/$zip_file";

        rrmdir("updator");
        @mkdir("updator");

        $extractResult = validateAndExtractZip($zip, 'updator');
        if($extractResult !== true){
          rrmdir("updator");
          @mkdir("updator");
          unlink($zip);
          echo "<script>alert_error(".json_encode($extractResult).",'index?add_plugin');</script>";
        }else{

          @$readme = file_get_contents("updator/readme.txt");
          $pluginName = getStringBetween($readme,"Plugin Name: ","\n");
          $folderName = getStringBetween($readme,"Folder: ","\n");
          $c_version = getStringBetween($readme,"Compatible Gigtodo Version: ","\n");

          if(!file_exists("updator/plugin.sql") || !file_exists("updator/files.zip") || !preg_match('/^[a-zA-Z0-9_-]+$/', $folderName) || $pluginName === ''){
            rrmdir("updator");
            @mkdir("updator");
            unlink($zip);
            echo "<script>alert_error('Please upload the correct zipped folder.','index?add_plugin');</script>";
          }else{
            if(!is_dir("../plugins/$folderName")){
              if($c_version <= $current_version){

                $command = file_get_contents('updator/plugin.sql');

                if(containsDangerousSql($command)){
                  rrmdir("updator");
                  @mkdir("updator");
                  unlink($zip);
                  echo "<script>alert_error('This plugin\\'s SQL file contains statements that are not allowed.','index?add_plugin');</script>";
                }else{

                  $allowedPluginExtensions = ['php','css','js','png','jpg','jpeg','gif','svg','webp','json','txt','woff','woff2','ttf','eot'];
                  $filesExtractResult = validateAndExtractZip('updator/files.zip', '../plugins', $folderName, $allowedPluginExtensions);

                  if($filesExtractResult !== true){
                    rrmdir("updator");
                    @mkdir("updator");
                    unlink($zip);
                    echo "<script>alert_error(".json_encode($filesExtractResult).",'index?add_plugin');</script>";
                  }else{

                    try{
                      $run = $db->con->prepare($command);
                      $success = $run->execute();
                    }catch(PDOException $ex){
                      $success = false;
                    }

                    if($success){
                      rrmdir("updator");
                      @mkdir("updator");
                      unlink($zip);
                      echo "<script>alert_success(".json_encode("$pluginName Has Been Successfully installed on your website.").",'index?plugins');</script>";
                    }else{
                      rrmdir("../plugins/$folderName");
                      rrmdir("updator");
                      @mkdir("updator");
                      unlink($zip);
                      echo "<script>alert_error('The plugin\\'s database setup failed. Nothing was installed.','index?add_plugin');</script>";
                    }
                  }
                }

              }else{
                rrmdir("updator");
                @mkdir("updator");
                unlink($zip);
                echo "<script>alert_error(".json_encode("This plugin requires Gigtodo $c_version or newer.").",'index?add_plugin');</script>";
              }
            }else{
              rrmdir("updator");
              @mkdir("updator");
              unlink($zip);
              echo "<script>alert_error(".json_encode("$pluginName has already been installed on your website.").",'index?add_plugin');</script>";
            }
          }
        }
      }
    }
  }
}
