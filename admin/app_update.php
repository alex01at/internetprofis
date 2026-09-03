<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

require_once __DIR__.'/includes/update_check.php';

$githubRepo = 'alex01at/internetprofis';
$versionFile = $dir.'admin/.deployed_version';

function githubRawGet($repo, $sha, $path){
	$url = "https://raw.githubusercontent.com/$repo/$sha/".implode('/', array_map('rawurlencode', explode('/', $path)));
	$context = stream_context_create([
		'http' => [
			'method'  => 'GET',
			'header'  => "User-Agent: internetprofis-admin\r\n",
			'timeout' => 15,
		],
	]);
	return @file_get_contents($url, false, $context);
}

// Paths this mechanism must never write to or delete, no matter what a diff
// says -- defense in depth on top of the fact that none of these are
// actually tracked in the repo (they're gitignored) so a legitimate diff
// should never mention them.
function isProtectedPath($relativePath){
	$protected = [
		'config.php',
		'admin/.deployed_version',
	];
	if(in_array($relativePath, $protected, true)){ return true; }
	$protectedPrefixes = [
		'order_files/',
		'conversations/conversations_files/',
		'proposals/proposal_files/',
		'requests/request_files/',
		'ticket_files/',
		'.git/',
	];
	foreach($protectedPrefixes as $prefix){
		if(strpos($relativePath, $prefix) === 0){ return true; }
	}
	return false;
}

// Same shape of validation as the plugin installer's zip-slip checks: no
// traversal, no absolute paths, resolved target must stay inside $rootDir.
function isSafeRelativePath($relativePath, $rootDir){
	if($relativePath === '' || strpos($relativePath, "\0") !== false){ return false; }
	if($relativePath[0] === '/' || preg_match('#^[a-zA-Z]:#', $relativePath)){ return false; }
	$normalized = str_replace('\\', '/', $relativePath);
	if(in_array('..', explode('/', $normalized), true)){ return false; }
	if(isProtectedPath($normalized)){ return false; }
	$targetDir = dirname($rootDir.'/'.$normalized);
	@mkdir($targetDir, 0755, true);
	$targetDirReal = realpath($targetDir);
	$rootReal = realpath($rootDir);
	if($targetDirReal === false || $rootReal === false || strpos($targetDirReal, $rootReal) !== 0){ return false; }
	return true;
}

function applyUpdate($githubRepo, $fromSha, $toSha, $toTag, $rootDir, $versionFile){
	$compareData = githubApiGet("https://api.github.com/repos/$githubRepo/compare/$fromSha...$toSha");
	if($compareData === null){
		return ['ok' => false, 'message' => 'Could not reach GitHub to fetch the file list. Nothing was changed.'];
	}
	$files = $compareData['files'] ?? [];
	if(count($files) >= 300){
		return ['ok' => false, 'message' => 'This update touches too many files to apply safely from here (GitHub only lists the first 300 in a comparison). Please sync manually this time.'];
	}

	$written = [];
	$deleted = [];
	$failed = [];

	foreach($files as $file){
		$status = $file['status'];
		$path = $file['filename'];

		if($status === 'removed' || $status === 'renamed'){
			$oldPath = $status === 'renamed' ? $file['previous_filename'] : $path;
			if(isSafeRelativePath($oldPath, $rootDir)){
				$fullOld = $rootDir.'/'.$oldPath;
				if(is_file($fullOld)){
					if(@unlink($fullOld)){ $deleted[] = $oldPath; }
					else{ $failed[] = $oldPath; }
				}
			}
		}

		if($status === 'removed'){ continue; }

		if(!isSafeRelativePath($path, $rootDir)){
			$failed[] = $path;
			continue;
		}

		$content = githubRawGet($githubRepo, $toSha, $path);
		if($content === null || $content === false){
			$failed[] = $path;
			continue;
		}

		$fullPath = $rootDir.'/'.$path;
		if(@file_put_contents($fullPath, $content) === false){
			$failed[] = $path;
		}else{
			$written[] = $path;
		}
	}

	if(!empty($failed)){
		return [
			'ok' => false,
			'message' => count($failed).' file(s) could not be written (check file permissions). The deployed-version marker was NOT updated, so a retry will attempt the same files again.',
			'written' => $written, 'deleted' => $deleted, 'failed' => $failed,
		];
	}

	writeDeployedVersion($versionFile, $toSha, $toTag);

	return [
		'ok' => true,
		'message' => 'Updated successfully: '.count($written).' file(s) written, '.count($deleted).' file(s) removed.',
		'written' => $written, 'deleted' => $deleted, 'failed' => $failed,
	];
}

$updateResult = null;

if(isset($_POST['init_version'])){
	$precheck = getUpdateStatus($githubRepo, $dir, $versionFile);
	if($precheck['latestCommit']){
		writeDeployedVersion($versionFile, $precheck['latestCommit']['sha'], $precheck['latestRelease']['tag_name']);
	}
	echo "<script>window.open('index?app_update','_self');</script>";
}

$status = getUpdateStatus($githubRepo, $dir, $versionFile);

if(isset($_POST['apply_update']) && $status['deployedSha'] && !empty($_POST['target_sha'])){
	$targetSha = preg_replace('/[^0-9a-f]/', '', $_POST['target_sha']);
	$targetTag = $_POST['target_tag'] ?? '';
	if(preg_match('/^[0-9a-f]{40}$/', $targetSha)){
		$updateResult = applyUpdate($githubRepo, $status['deployedSha'], $targetSha, $targetTag, rtrim($dir, '/\\'), $versionFile);
		if($updateResult['ok']){
			$status = getUpdateStatus($githubRepo, $dir, $versionFile);
		}
	}
}

$deployedSha = $status['deployedSha'];
$deployedTag = $status['deployedTag'];
$latestRelease = $status['latestRelease'];
$latestCommit = $status['latestCommit'];
$compareData = $status['compareData'];
$noReleases = $status['noReleases'];
$apiError = $status['apiError'];

?>
<div class="main-container">
<h4 class="mb-4"><i class="fa fa-cog"></i> Application Updater</h4>
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
          <label class="col-md-3 control-label"> Deployed Version : </label>
          <div class="col-md-9 text-right">
          <?php if($deployedSha){ ?>
            <a href="https://github.com/<?= $githubRepo; ?>/commit/<?= $deployedSha; ?>" target="_blank">
              <code><?= $deployedTag ? htmlspecialchars($deployedTag, ENT_QUOTES, 'UTF-8') : substr($deployedSha, 0, 10); ?></code>
            </a>
          <?php }else{ ?>
            <span class="text-muted">Unknown -- not tracked yet on this server</span>
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
      <i class="fa fa-github fa-fw"></i> Update
      </h4>
      </div><!--- card-header Ends --->
      <div class="card-body"><!--- card-body Starts --->

      <?php if($updateResult){ ?>
        <div class="alert <?= $updateResult['ok'] ? 'alert-success' : 'alert-danger'; ?>">
          <?= htmlspecialchars($updateResult['message'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
      <?php } ?>

      <?php if($noReleases){ ?>
        <div class="alert alert-info mb-0">
          No release has been published on <a href="https://github.com/<?= $githubRepo; ?>/releases" target="_blank">GitHub</a> yet.
          This page only offers updates for published releases, not every individual commit -- publish one there first
          (Releases &rarr; Draft a new release) once a set of changes is ready to go live.
        </div>

      <?php }elseif($apiError){ ?>
        <div class="alert alert-warning mb-0">Could not reach GitHub to check for updates right now. Try again in a moment.</div>

      <?php }elseif(!$deployedSha){ ?>
        <p>This server doesn't have a tracked deployment version yet (no <code>admin/.deployed_version</code> and no <code>.git</code> folder found).</p>
        <p>If the code currently on this server already matches release <code><?= htmlspecialchars($latestRelease['tag_name'], ENT_QUOTES, 'UTF-8'); ?></code> on GitHub (e.g. you just finished a manual sync), mark it as up to date to start tracking updates from here on:</p>
        <form method="post" onsubmit="return confirm('This only records the current release as your baseline -- it does not change any files. Only do this right after the server\'s files actually match that release. Continue?');">
          <input type="hidden" name="init_version" value="1">
          <button type="submit" class="btn btn-success">Mark current server state as up to date (<?= htmlspecialchars($latestRelease['tag_name'], ENT_QUOTES, 'UTF-8'); ?>)</button>
        </form>

      <?php }elseif($compareData['status'] === 'identical' || $compareData['status'] === 'behind'){ ?>
        <div class="alert alert-success mb-0">You're running the latest published release (<?= htmlspecialchars($latestRelease['tag_name'], ENT_QUOTES, 'UTF-8'); ?>)<?php if($compareData['status'] === 'behind'){ ?> or newer<?php } ?>.</div>

      <?php }elseif($compareData['status'] === 'ahead' || $compareData['status'] === 'diverged'){ ?>
        <div class="alert alert-warning mb-3">
          A newer release is available: <strong><?= htmlspecialchars($latestRelease['tag_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
          &mdash; <?= (int) $compareData['ahead_by']; ?> commit(s), <?= count($compareData['files'] ?? []); ?> file(s) changed.
        </div>
        <?php if(!empty($latestRelease['body'])){ ?>
          <div class="mb-3"><strong>Release notes:</strong><br><?= nl2br(htmlspecialchars($latestRelease['body'], ENT_QUOTES, 'UTF-8')); ?></div>
        <?php } ?>
        <ul class="list-unstyled mb-3">
        <?php foreach(array_slice($compareData['commits'], -10) as $commit){ ?>
          <li class="mb-2">
            <a href="<?= $commit['html_url']; ?>" target="_blank"><code><?= substr($commit['sha'], 0, 10); ?></code></a>
            &mdash; <?= htmlspecialchars(strtok($commit['commit']['message'], "\n"), ENT_QUOTES, 'UTF-8'); ?>
            <small class="text-muted">(<?= htmlspecialchars($commit['commit']['author']['name'], ENT_QUOTES, 'UTF-8'); ?>, <?= date('Y-m-d', strtotime($commit['commit']['author']['date'])); ?>)</small>
          </li>
        <?php } ?>
        </ul>
        <a class="btn btn-secondary mr-2" href="<?= $latestRelease['html_url']; ?>" target="_blank">View release on GitHub</a>
        <a class="btn btn-secondary mr-2" href="<?= $compareData['html_url']; ?>" target="_blank">View full comparison</a>
        <form method="post" class="d-inline" onsubmit="return confirm('This will fetch and overwrite <?= count($compareData['files']); ?> file(s) on this server directly from GitHub release <?= htmlspecialchars($latestRelease['tag_name'], ENT_QUOTES, 'UTF-8'); ?>, and delete files that were removed upstream. Make sure you have a backup. Continue?');">
          <input type="hidden" name="apply_update" value="1">
          <input type="hidden" name="target_sha" value="<?= htmlspecialchars($latestCommit['sha'], ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="target_tag" value="<?= htmlspecialchars($latestRelease['tag_name'], ENT_QUOTES, 'UTF-8'); ?>">
          <button type="submit" class="btn btn-success">Update now</button>
        </form>
      <?php } ?>

      <p class="text-muted mt-3 mb-0">
        Only offers updates for published <a href="https://github.com/<?= $githubRepo; ?>/releases" target="_blank">GitHub Releases</a>,
        not every commit. Fetches only the changed files directly from <code>github.com/<?= $githubRepo; ?></code> over HTTPS and writes them
        in place -- no ZIP upload, no arbitrary source, no SQL execution. <code>config.php</code> and all folders holding real
        user-submitted content are always left untouched.
      </p>

      </div><!--- card-body Ends --->
    </div><!--- card mb-5 Ends --->
  </div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div>
<?php } ?>
