<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{

$plugin_id = $input->get('update_plugin');
$plugins = $db->select("plugins", ["id" => $plugin_id]);
$plugin = $plugins->fetch();
$pluginName = $plugin->name;
$folder = $plugin->folder;
$current_version = $plugin->version;

?>
<div class="main-container">
<h4 class="mb-4"><i class="fa fa-cog"></i> Update Plugin / <?= $pluginName; ?></h4>
<div class="row mb-4"><!--- 2 row Starts --->
  <div class="col-lg-12"><!--- col-lg-12 Starts --->
  <div class="card mb-5"><!--- card mb-5 Starts --->
    <div class="card-header"><!--- card-header Starts --->
      <h4 class="h4 mb-0"> <i class="fa fa-money fa-fw"></i> Update Plugin / <?= $pluginName; ?> </h4>
    </div><!--- card-header Ends --->
    <div class="card-body p-0"><!--- card-body Starts --->
      <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->
        <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-3"><!--- form-group row Starts --->
          <label class="col-md-6 control-label"> Upload File: </label>
          <div class="col-md-6">
            <input type="file" required="" class="form-control form-control-sm mt-0" name="zip_file" accept=".zip">
            <small class="text-muted">See <a href="https://github.com/alex01at/internetprofis/blob/main/plugins/README.md" target="_blank">plugins/README.md</a> for the required package format.</small>
          </div>
        </div><!--- form-group row Ends --->
        <hr class="mt-0 mb-3">
        <div class="form-group row mb-4"><!--- form-group row Starts --->
          <label class="col-md-3 control-label"></label>
          <div class="col-md-6 pl-4 pr-4">
            <input type="submit" name="updatePlugin" value="Update <?= $pluginName; ?>" class="btn btn-success form-control">
          </div>
        </div><!--- form-group row Ends --->
      </form><!--- form Ends --->
      </div><!--- card-body Ends --->
    </div><!--- card mb-5 Ends --->
  </div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div><!--- container pt-3 Ends --->
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

  // Rejects any zip entry that could write outside $destDir (zip slip /
  // path traversal), and optionally enforces that every entry lives under
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

  // A plugin update only needs to alter its own tables -- block statements
  // it should never need, as a safety net on top of the zip-slip checks.
  function containsDangerousSql($sql){
    $blocked = ['DROP DATABASE', 'GRANT ', 'LOAD_FILE', 'INTO OUTFILE', 'INTO DUMPFILE', 'LOAD DATA'];
    $upper = strtoupper($sql);
    foreach($blocked as $needle){
      if(strpos($upper, $needle) !== false){ return true; }
    }
    return false;
  }

  // Deletes files the update package says are obsolete, but only if the
  // resolved path stays inside the plugin's own folder.
  function safeDeleteListedFiles($listFile, $pluginDir){
    $pluginDirReal = realpath($pluginDir);
    if($pluginDirReal === false){ return; }
    foreach(file($listFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line){
      $line = trim($line);
      if($line === '' || strpos($line, '..') !== false || $line[0] === '/'){ continue; }
      $target = $pluginDir.'/'.$line;
      $targetReal = realpath($target);
      if($targetReal !== false && strpos($targetReal, $pluginDirReal) === 0 && is_file($targetReal)){
        unlink($targetReal);
      }
    }
  }

  if(isset($_POST["updatePlugin"]) && preg_match('/^[a-zA-Z0-9_-]+$/', $folder)){

    $zip_file = $_FILES['zip_file']['name'];
    $zip_file_tmp = $_FILES['zip_file']['tmp_name'];
    $allowed = array('zip');
    $file_extension = strtolower(pathinfo($zip_file, PATHINFO_EXTENSION));

    if(!in_array($file_extension,$allowed) || empty($zip_file)){
      echo "<script>alert_error('You can only upload a zipped folder.','index?update_plugin=$plugin_id');</script>";
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
          echo "<script>alert_error(".json_encode($extractResult).",'index?update_plugin=$plugin_id');</script>";
        }else{

          @$readme = file_get_contents("updator/readme.txt");
          $version = getStringBetween($readme,"Version: ","\n");
          $c_version = getStringBetween($readme,"Compatible Version: ","\n");

          if(!file_exists("updator/readme.txt") || !file_exists("updator/update.sql") || !file_exists("updator/files.zip")){
            rrmdir("updator");
            @mkdir("updator");
            unlink($zip);
            echo "<script>alert_error('Please upload the correct zipped folder.','index?update_plugin=$plugin_id');</script>";
          }else{

            if($current_version < $version){
              if($c_version == $current_version){

                $command = file_get_contents('updator/update.sql');

                if(containsDangerousSql($command)){
                  rrmdir("updator");
                  @mkdir("updator");
                  unlink($zip);
                  echo "<script>alert_error('This update\\'s SQL file contains statements that are not allowed.','index?update_plugin=$plugin_id');</script>";
                }else{

                  $allowedPluginExtensions = ['php','css','js','png','jpg','jpeg','gif','svg','webp','json','txt','woff','woff2','ttf','eot'];
                  $filesExtractResult = validateAndExtractZip('updator/files.zip', '../plugins', $folder, $allowedPluginExtensions);

                  if($filesExtractResult !== true){
                    rrmdir("updator");
                    @mkdir("updator");
                    unlink($zip);
                    echo "<script>alert_error(".json_encode($filesExtractResult).",'index?update_plugin=$plugin_id');</script>";
                  }else{

                    try{
                      $run = $db->con->prepare($command);
                      $success = $run->execute();
                    }catch(PDOException $ex){
                      $success = false;
                    }

                    if($success){

                      if(file_exists("updator/delete_files.txt")){
                        safeDeleteListedFiles("updator/delete_files.txt", "../plugins/$folder");
                      }

                      $db->update("plugins", ["version" => $version], ["id" => $plugin_id]);

                      rrmdir("updator");
                      @mkdir("updator");
                      unlink($zip);
                      echo "<script>alert_success(".json_encode("$pluginName Has Been Successfully Updated on your website.").",'index?plugins');</script>";
                    }else{
                      rrmdir("updator");
                      @mkdir("updator");
                      unlink($zip);
                      echo "<script>alert_error('The update\\'s database step failed. The plugin files were updated but the version was not changed -- please check manually.','index?plugins');</script>";
                    }
                  }
                }

              }else{
                rrmdir("updator");
                @mkdir("updator");
                unlink($zip);
                echo "<script>alert_error(".json_encode("This Update File Will Only Work On Plugin Version:$c_version.").",'index?update_plugin=$plugin_id');</script>";
              }
            }else if($current_version > $version){
              rrmdir("updator");
              @mkdir("updator");
              unlink($zip);
              echo "<script>alert_error('Sorry, you cannot downgrade a version.','index?plugins');</script>";
            }else if($current_version == $version){
              rrmdir("updator");
              @mkdir("updator");
              unlink($zip);
              echo "<script>alert_error('You already have this version installed.','index?update_plugin=$plugin_id');</script>";
            }

          }
        }
      }
    }
  }
}
