<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

$githubRepo = 'alex01at/internetprofis';
$githubBranch = 'main';

function readLocalGitHead($rootDir){
	$rootDir = rtrim($rootDir, '/\\');
	$headFile = $rootDir.'/.git/HEAD';
	if(!is_file($headFile)){ return null; }
	$head = trim(file_get_contents($headFile));
	if(strpos($head, 'ref:') === 0){
		$ref = trim(substr($head, 4));
		$refFile = $rootDir.'/.git/'.$ref;
		if(is_file($refFile)){
			return trim(file_get_contents($refFile));
		}
		$packedRefs = $rootDir.'/.git/packed-refs';
		if(is_file($packedRefs)){
			foreach(file($packedRefs) as $line){
				if(strpos($line, $ref) !== false){
					return substr($line, 0, 40);
				}
			}
		}
		return null;
	}
	return preg_match('/^[0-9a-f]{40}$/', $head) ? $head : null;
}

function githubApiGet($url){
	$context = stream_context_create([
		'http' => [
			'method'  => 'GET',
			'header'  => "User-Agent: internetprofis-admin\r\nAccept: application/vnd.github+json\r\n",
			'timeout' => 5,
		],
	]);
	$response = @file_get_contents($url, false, $context);
	if($response === false){ return null; }
	$data = json_decode($response, true);
	return is_array($data) ? $data : null;
}

$localSha = readLocalGitHead($dir);
$compareData = null;
$latestCommit = null;
$apiError = false;

if($localSha){
	$compareData = githubApiGet("https://api.github.com/repos/$githubRepo/compare/$localSha...$githubBranch");
	if($compareData === null){ $apiError = true; }
}else{
	$latestCommit = githubApiGet("https://api.github.com/repos/$githubRepo/commits/$githubBranch");
	if($latestCommit === null){ $apiError = true; }
}

?>
<div class="breadcrumbs">
<div class="col-sm-6">
  <div class="page-header float-left">
  <div class="page-title">
      <h1><i class="menu-icon fa fa-cog"></i> Settings / Application Updater</h1>
  </div>
  </div>
</div>
</div>
<div class="container pt-3">
<div class="row"><!--- 2 row Starts --->
  <div class="col-lg-12"><!--- col-lg-12 Starts --->
    <div class="card mb-5"><!--- card mb-5 Starts --->
      <div class="card-header"><!--- card-header Starts --->
          <h4 class="h4 mb-0"><i class="fa fa-info-circle fa-fw"></i> Application Information</h4>
      </div><!--- card-header Ends --->
      <div class="card-body p-0"><!--- card-body Starts --->
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-3"><!--- form-group row Starts --->
          <label class="col-md-3 control-label">Php Version : </label>
          <div class="col-md-9 text-right">
          <?= phpversion(); ?>
          </div>
          </div><!--- form-group row Ends --->
          <hr class="mt-0 mb-2">
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-2"><!--- form-group row Starts --->
          <label class="col-md-3 control-label"> Installed On : </label>
          <div class="col-md-9 text-right">
          <a href="<?= $site_url; ?>" target="_blank" style="color: green;"><?= $site_url; ?></a>
          </div>
          </div><!--- form-group row Ends --->
          <hr class="mt-0 mb-2">
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-2"><!--- form-group row Starts --->
          <label class="col-md-3 control-label"> Deployed Commit : </label>
          <div class="col-md-9 text-right">
          <?php if($localSha){ ?>
            <a href="https://github.com/<?= $githubRepo; ?>/commit/<?= $localSha; ?>" target="_blank"><code><?= substr($localSha, 0, 10); ?></code></a>
          <?php }else{ ?>
            <span class="text-muted">Could not be determined (no .git found on this server)</span>
          <?php } ?>
          </div>
          </div><!--- form-group row Ends --->
      </div><!--- card-body Ends --->
    </div><!--- card mb-5 Ends --->
  </div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->

<div class="row mb-4"><!--- 2 row Starts --->
  <div class="col-lg-12"><!--- col-lg-12 Starts --->
    <div class="card mb-5"><!--- card mb-5 Starts --->
      <div class="card-header"><!--- card-header Starts --->
      <h4 class="h4 mb-0">
      <i class="fa fa-github fa-fw"></i> Update Check
      </h4>
      </div><!--- card-header Ends --->
      <div class="card-body"><!--- card-body Starts --->

      <?php if($apiError){ ?>
        <div class="alert alert-warning mb-0">Could not reach GitHub to check for updates right now. Try again in a moment.</div>

      <?php }elseif($compareData){ ?>
        <?php if($compareData['status'] === 'identical' || $compareData['status'] === 'behind'){ ?>
          <div class="alert alert-success mb-0">You're running the latest version of the code (<?= $githubBranch; ?>).</div>
        <?php }elseif($compareData['status'] === 'ahead'){ ?>
          <div class="alert alert-info mb-0">This deployment is <?= (int) $compareData['ahead_by']; ?> commit(s) ahead of <?= $githubBranch; ?> on GitHub (local, unpushed changes?).</div>
        <?php }else{ ?>
          <div class="alert alert-warning mb-3">This deployment is <?= (int) $compareData['behind_by']; ?> commit(s) behind <?= $githubBranch; ?> on GitHub.</div>
          <ul class="list-unstyled mb-3">
          <?php foreach(array_slice($compareData['commits'], -10) as $commit){ ?>
            <li class="mb-2">
              <a href="<?= $commit['html_url']; ?>" target="_blank"><code><?= substr($commit['sha'], 0, 10); ?></code></a>
              &mdash; <?= htmlspecialchars(strtok($commit['commit']['message'], "\n"), ENT_QUOTES, 'UTF-8'); ?>
              <small class="text-muted">(<?= htmlspecialchars($commit['commit']['author']['name'], ENT_QUOTES, 'UTF-8'); ?>, <?= date('Y-m-d', strtotime($commit['commit']['author']['date'])); ?>)</small>
            </li>
          <?php } ?>
          </ul>
          <a class="btn btn-success" href="<?= $compareData['html_url']; ?>" target="_blank">View full comparison on GitHub</a>
        <?php } ?>

      <?php }elseif($latestCommit){ ?>
        <p>Deployed commit could not be detected on this server, so a direct comparison isn't possible. Latest commit on <?= $githubBranch; ?>:</p>
        <p>
          <a href="<?= $latestCommit['html_url']; ?>" target="_blank"><code><?= substr($latestCommit['sha'], 0, 10); ?></code></a>
          &mdash; <?= htmlspecialchars(strtok($latestCommit['commit']['message'], "\n"), ENT_QUOTES, 'UTF-8'); ?>
          <small class="text-muted">(<?= date('Y-m-d', strtotime($latestCommit['commit']['author']['date'])); ?>)</small>
        </p>
      <?php } ?>

      <p class="text-muted mt-3 mb-0">
        Updates are pulled via <code>git</code> on the server (e.g. <code>git pull</code>), not through this page.
        This page only checks and reports the status &mdash; it never downloads, extracts, or runs anything.
      </p>

      </div><!--- card-body Ends --->
    </div><!--- card mb-5 Ends --->
  </div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div>
<?php } ?>
