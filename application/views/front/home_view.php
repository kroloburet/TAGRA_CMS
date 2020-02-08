
    <!--
    ########### Mine
    -->

    <div class="mine_wrapper">
      <div class="container">

        <!-- заголовок -->
        <div id="headline">
          <h1><?= $data['title'] ?></h1>

          <?php if ($conf['addthis']['share'] && $data['addthis_share']) { ?>
          <div class="addthis_layout noprint"><?= $conf['addthis']['share'] ?></div>
          <?php } ?>

        </div>

        <?php if ($data['layout_l'] || $data['layout_r'] || $data['layout_t'] || $data['layout_b']) { ?>

        <!-- контент материала -->
        <div id="layouts">
          <?php if ($data['layout_t']) { ?>

          <div id="layout_t">
            <?= $data['layout_t'] . PHP_EOL ?>
          </div>

          <?php } ?>
          <?php if ($data['layout_l'] || $data['layout_r']) { ?>

          <div id="layout_l">
            <?= $data['layout_l'] . PHP_EOL ?>
          </div>

          <div id="layout_r">
            <?= $data['layout_r'] . PHP_EOL ?>
          </div>

          <?php } ?>
          <?php if ($data['layout_b']) { ?>

          <div id="layout_b">
            <?= $data['layout_b'] . PHP_EOL ?>
          </div>

          <?php } ?>

        </div>
        <?php } ?>

      </div>
    </div>
