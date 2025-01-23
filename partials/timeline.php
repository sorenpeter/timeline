<!--
<?php if (!empty($_GET['profile'])) {?>
<em>Twts for <a href="<?=$twtsURL?>"><?=$twtsURL?></a></em>
<?php } else {?>
<em>Timeline for <a href="<?=$url?>"><?=$url?></a></em>
<?php }?>
-->

<?php // echo "count: " . count($twts); ?>

<?php include_once 'partials/search.php'; ?>

<?php foreach ($twts as $twt) { ?>
	<article class="post-entry" id="<?=$twt->hash?>">
		<a href="<?=$baseURL?>/profile?url=<?=$twt->mainURL?>">
			<img src='<?=$twt->avatar?>' class="avatar" onerror="this.onerror=null;this.src='<?= $baseURL ?>/media/default.png';">
		</a>
		<div>
			<a href="<?=$baseURL?>/profile?url=<?=$twt->mainURL?>" class="author">
				<strong>@<?=$twt->nick?></strong><?=$twt->domain?>
			</a>

			<div class="twt-msg">
				<?=$twt->content?>
			</div>

			<small>
			<?php
				if ($twt->replyToHash) {
					echo 'In reply to: <a href="' . $baseURL . '/conv/' . $twt->replyToHash . '">#' . $twt->replyToHash . '</a>';
					//echo '<a href="'.$baseURL.'/conv/'.$twt->replyToHash.'">Convesation</a>';
				} else {
					echo '<a href="'.$baseURL.'/conv/'.$twt->hash.'">Read replies</a>';
				}

				/*
				if ($twt->replyToHash && isset($_SESSION['password'])) {
					echo ' | ';
				}
				*/

				require_once 'libs/session.php';
				if (hasValidSession()) {
					echo ' | <a href="' . $baseURL . '/new?hash=' . $twt->hash . '">Reply</a>';
				}

			?>
				<!--  (<a href="new_twt.php?hash=<?=$twt->hash?>">via email</a>) TODO: mailto-link -->
				<a href='<?=$baseURL?>/post/<?=$twt->hash?>' class="right"><span title="<?=$twt->fullDate?>"><?=$twt->displayDate?></span></a>
			</small>
		</div>
	</article>

<?php }

include_once 'partials/pagnation.php';

require_once 'libs/session.php';

if (!hasValidSession()) {
	echo '<center><a href="mailto:' . $config['email'] . '?subject=RE: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" class="button">Comment via email</a></center>';
}