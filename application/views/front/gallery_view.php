
    <!--
    ########### Mine
    -->

    <div class="mine_wrapper">
      <div class="container">

        <?php
        $this->load->helper('front/nav');
        (new nav())->breadcrumbs();
        ?>

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

        <!-- галерея -->
        <div id="FVGallery_layout">
        <?php
        switch ($data['gallery_type']) {

            // галерея из папки с изображениями
            case 'foto_folder':
                if ($data['gallery_opt']) {
                    $dir = json_decode($data['gallery_opt'], true)['f_folder'];
                    $dir_handle = @opendir('.' . $dir) or die($lexic['gallery']['error_path_img_dir']);
                    while ($file = readdir($dir_handle)) {
                        if (
                            $file == '.'
                            || $file == '..'
                            || !preg_match('/.+(\.jpg|\.jpeg|\.gif|\.png|\.svg)$/i', $file)
                        ) {
                            continue;
                        }
                        echo '
          <div class="FVG_item FVG_item_f_folder">
            <img src="/img/noimg.jpg" alt="' . $file . '" data-src="' . $dir . '/' . $file . '">
            <textarea class="opt" hidden>{"f_url":"' . $dir . '/' . $file . '"}</textarea>
          </div>
          ';
                    }
                    closedir($dir_handle);
                } else {
                    echo '<div class="notific_b">' . $lexic['gallery']['noimgs'] . '</div>' . PHP_EOL;
                }
                break;

            // галерея изображений с описаниями
            case 'foto_desc':
                if ($data['gallery_opt']) {
                    $opt = json_decode($data['gallery_opt'], true);
                    foreach ($opt as $v) {
                        echo '
          <div class="FVG_item FVG_item_f_desc">
            <img src="/img/noimg.jpg" alt="' . $v['f_title'] . '" data-src="' . $v['f_url'] . '">
            <div class="FVG_item_f_desc_preview">
              <h3>' . $v['f_title'] . '</h3>
              ' . $v['f_desc'] . '
            </div>
            <textarea class="opt" hidden>' . json_encode($v) . '</textarea>
          </div>
          ';
                    }
                } else {
                    echo '<div class="notific_b">' . $lexic['gallery']['noimgs'] . '</div>' . PHP_EOL;
                }
                break;

            // галерея youtube
            case 'video_yt':
                if ($data['gallery_opt']) {
                    $opt = json_decode($data['gallery_opt'], true);
                    foreach ($opt as $v) {
                        echo '
          <div class="FVG_item FVG_item_v_yt">
            <img src="/img/noimg.jpg" data-src="https://img.youtube.com/vi/' . $v['yt_id'] . '/mqdefault.jpg">
            <textarea class="opt" hidden>' . json_encode($v) . '</textarea>
          </div>
          ';
                     }
                } else {
                    echo '<div class="notific_b">' . $lexic['gallery']['novideos'] . '</div>' . PHP_EOL;
                }
                break;

            // галерея audio
            case 'audio':
                if ($data['gallery_opt']) {
                    $num = 1;
                    $opt = json_decode($data['gallery_opt'], true);
                    $a_items = '';
                    foreach ($opt as $v) {
                        $num_ = $num <= 9 ? '0' . $num++ : $num++;
                        $a_items .= '
                <li class="a_item">
                  <div class="a_item_num">' . $num_ . '</div>
                  <div class="a_item_title">' . $v['a_title'] . '</div>
                </li>
                ';
                    }
                    echo '
            <div id="a_player">
              <div class="a_controls">
                <div class="a_now_play">
                  <span class="a_action"></span>
                  <span class="a_title"></span>
                </div>
                <div class="a_box">
                  <audio class="a_audio" preload controls>
                    ' . htmlspecialchars($lexic['gallery']['nohtml5_audio']) . '
                  </audio>
                  <div class="a_nav">
                    <a class="a_prev fas fa-chevron-left"></a>
                    <a class="a_next fas fa-chevron-right"></a>
                  </div>
                </div>
              </div>
              <ul class="a_items">
                ' . $a_items . '
              </ul>
            </div>
                    ';
                } else {
                    echo '<div class="notific_b">' . $lexic['gallery']['noaudios'] . '</div>' . PHP_EOL;
                }
        }
        ?>

        </div>

        <?php
        $this->load->helper('front/comments');
        (new comments(array_replace($conf['comments'], ['form' => $data['comments']])))->print_comments();
        ?>

      </div>
    </div>
