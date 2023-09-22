<!-- <center>
    <?php if (!empty($_GET['twts'])) { ?>
        <em>Twts for <a href="<?= $twtsURL ?>"><?= $twtsURL ?></a></em>
    <?php } else { ?>
        <em>Timeline for <a href="<?= $url ?>"><?= $url ?></a></em>
    <?php } ?>
</center> -->

<?php foreach ($twts as $twt) { ?>

    <article class="post-entry">
        <a href="?twts=<?= $twt->mainURL ?>">
            <img src='<?= $twt->avatar ?>' class="avatar" onerror="this.onerror=null;this.src='imgs/image_not_found.png';">
        </a>
        <div class=""> 
            <a href="?twts=<?= $twt->mainURL ?>" class="author">
                <strong><?= $twt->nick ?></strong>@<?= parse_url($twt->mainURL, PHP_URL_HOST); ?>
            </a>

            <div class="twt-msg">
                <?= $twt->content ?>
                <?php foreach ($twt->mentions as $mention) { ?>
                    <br><?= $mention['nick'] ?>(<?= $mention['url'] ?>)
                <?php } ?>
            </div>

            <small>
                <?php if($twt->replyToHash) { ?>
                    <a href="?hash=<?= $twt->replyToHash?>">Conversation</a> | 
                <?php } ?>
                <a href="new_twt.php?hash=<?= $twt->hash ?>">Reply</a>
                <a href='?hash=<?= $twt->hash ?>' class="right"><span title="<?= $twt->fullDate ?> "><?= $twt->displayDate ?></span></a>
            </small>
        </div>
    </article>

<?php } ?>