<!--
########### Main
-->

<main>
    <div class="container">

        <!-- заголовок -->
        <div class="main_headline">
            <h1><?= $data['title'] ?></h1>

            <?php if ($conf['addthis']['share'] && $data['addthis_share']) { ?>
                <div class="addthis_layout TUI_noprint"><?= $conf['addthis']['share'] ?></div>
            <?php } ?>

        </div>

        <?php if ($data['layout_l'] || $data['layout_r'] || $data['layout_t'] || $data['layout_b']) { ?>
            <!-- контент  материала -->
            <div id="layouts">
                <?php if ($data['layout_t']) { ?>
                    <div id="layout_t"><?= $data['layout_t'] ?></div>
                <?php } ?>
                <?php if ($data['layout_l'] || $data['layout_r']) { ?>
                    <div id="layout_l"><?= $data['layout_l'] ?></div>
                    <div id="layout_r"><?= $data['layout_r'] ?></div>
                <?php } ?>
                <?php if ($data['layout_b']) { ?>
                    <div id="layout_b"><?= $data['layout_b'] ?></div>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</main>

