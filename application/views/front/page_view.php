<!--
########### Main
-->

<main>
    <div class="container">

        <?php
        $this->load->helper('front/nav');
        (new nav())->breadcrumbs();
        ?>

        <!-- заголовок -->
        <div class="main_headline">
            <h1><?= $data['title'] ?></h1>

            <?php if ($conf['addthis']['share'] && $data['addthis_share']) { ?>
                <div class="addthis_layout TUI_noprint"><?= $conf['addthis']['share'] ?></div>
            <?php } ?>

        </div>

        <?php if ($data['content_l'] || $data['content_r'] || $data['content_t'] || $data['content_b']) { ?>
            <!-- контент материала -->
            <div id="content_layout">
                <?php if ($data['content_t']) { ?>
                    <div id="content_t"><?= $data['content_t'] ?></div>
                <?php } ?>
                <?php if ($data['content_l'] || $data['content_r']) { ?>
                    <div id="content_l"><?= $data['content_l'] ?></div>
                    <div id="content_r"><?= $data['content_r'] ?></div>
                <?php } ?>
                <?php if ($data['content_b']) { ?>
                    <div id="content_b"><?= $data['content_b'] ?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php
        $this->load->helper('front/comments');
        (new comments(array_replace($conf['comments'], ['form' => $data['comments']])))->print_comments();
        ?>

    </div>
</main>

