<?php
// Shared read-only "how does this deployment compare to GitHub" logic, used
// by both admin/app_update.php (the full update page) and admin/dashboard.php
// (the small version/update-available indicator). Nothing in this file
// writes to the filesystem except writeDeployedVersion(), and nothing here
// downloads or applies any update -- that stays in app_update.php.

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

// Returns ['sha' => ..., 'tag' => ...|null] or null if nothing is tracked yet.
function readDeployedVersion($versionFile, $rootDir){
	if(is_file($versionFile)){
		$raw = trim(file_get_contents($versionFile));
		$data = json_decode($raw, true);
		if(is_array($data) && !empty($data['sha']) && preg_match('/^[0-9a-f]{40}$/', $data['sha'])){
			return ['sha' => $data['sha'], 'tag' => $data['tag'] ?? null];
		}
		// Back-compat: earlier versions of this file stored a bare SHA.
		if(preg_match('/^[0-9a-f]{40}$/', $raw)){
			return ['sha' => $raw, 'tag' => null];
		}
	}
	$sha = readLocalGitHead($rootDir);
	return $sha ? ['sha' => $sha, 'tag' => null] : null;
}

function writeDeployedVersion($versionFile, $sha, $tag){
	@file_put_contents($versionFile, json_encode(['sha' => $sha, 'tag' => $tag]));
}

function githubApiGet($url){
	$context = stream_context_create([
		'http' => [
			'method'  => 'GET',
			'header'  => "User-Agent: internetprofis-admin\r\nAccept: application/vnd.github+json\r\n",
			'timeout' => 8,
		],
	]);
	$response = @file_get_contents($url, false, $context);
	if($response === false){ return null; }
	$data = json_decode($response, true);
	return is_array($data) ? $data : null;
}

// Finds which release tag (if any) points at $sha. Cheap path first (does it
// match the release we already fetched?), then checks recent releases
// (capped, since each one needs its own API call to resolve tag -> commit).
function resolveTagForCommit($githubRepo, $sha, $latestRelease, $latestCommit){
	if($latestCommit && $sha === $latestCommit['sha']){
		return $latestRelease['tag_name'];
	}

	$releases = githubApiGet("https://api.github.com/repos/$githubRepo/releases?per_page=10");
	if(!is_array($releases)){ return null; }

	foreach($releases as $release){
		if(empty($release['tag_name'])){ continue; }
		$commit = githubApiGet("https://api.github.com/repos/$githubRepo/commits/".rawurlencode($release['tag_name']));
		if($commit && $commit['sha'] === $sha){
			return $release['tag_name'];
		}
	}

	return null;
}

// One-stop status check: where are we vs. the latest published release.
function getUpdateStatus($githubRepo, $rootDir, $versionFile){
	$rootDir = rtrim($rootDir, '/\\');
	$deployed = readDeployedVersion($versionFile, $rootDir);

	$status = [
		'deployedSha'     => $deployed['sha'] ?? null,
		'deployedTag'     => $deployed['tag'] ?? null,
		'latestRelease'   => null,
		'latestCommit'    => null,
		'compareData'     => null,
		'noReleases'      => false,
		'apiError'        => false,
		'updateAvailable' => false,
	];

	$latestRelease = githubApiGet("https://api.github.com/repos/$githubRepo/releases/latest");
	if($latestRelease === null){
		$status['noReleases'] = true;
		return $status;
	}
	$status['latestRelease'] = $latestRelease;

	$latestCommit = githubApiGet("https://api.github.com/repos/$githubRepo/commits/".rawurlencode($latestRelease['tag_name']));
	if($latestCommit === null){
		$status['apiError'] = true;
		return $status;
	}
	$status['latestCommit'] = $latestCommit;

	if(!$status['deployedSha']){
		return $status;
	}

	// A deployment tracked before release-name storage was added only has a
	// commit SHA on file. Resolve it to a release tag where possible, so the
	// dashboard can show "1.0.1" instead of a raw hash, and persist it so
	// this lookup only has to happen once.
	if(!$status['deployedTag']){
		$resolvedTag = resolveTagForCommit($githubRepo, $status['deployedSha'], $latestRelease, $latestCommit);
		if($resolvedTag){
			$status['deployedTag'] = $resolvedTag;
			writeDeployedVersion($versionFile, $status['deployedSha'], $resolvedTag);
		}
	}

	$compareData = githubApiGet("https://api.github.com/repos/$githubRepo/compare/{$status['deployedSha']}...{$latestCommit['sha']}");
	if($compareData === null){
		$status['apiError'] = true;
		return $status;
	}
	$status['compareData'] = $compareData;
	$status['updateAvailable'] = in_array($compareData['status'], ['ahead', 'diverged'], true);

	return $status;
}
