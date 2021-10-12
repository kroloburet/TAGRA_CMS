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

        // Раздел содержит подразделы
        if ($data['sub_sections']) {
            $sub_sections = '';
            foreach ($data['sub_sections'] as $v) {
                $img_prev = $v['img_prev']
                    ? '
          <a href="/section/' . $v['id'] . '"
              style="background-image:url(' . $v['img_prev'] . ')"
              class="section_sub_item_prev"
              title="' . htmlspecialchars($lexic['section']['sub_section'] . ' "' . $v['title'] . '"') . '">
          </a>
          '
                    : '
          <a href="/section/' . $v['id'] . '"
              class="section_sub_item_prev"
              title="' . htmlspecialchars($lexic['section']['sub_section'] . ' "' . $v['title'] . '"') . '">
            <i class="fas fa-copy"></i>
          </a>
          ';
                $sub_sections .= '
        <div class="section_sub_item">
          ' . $img_prev . '
          <div class="section_sub_item_desc">
            <h3>' . $v['title'] . '</h3>
            <div>' . $v['description'] . '...</div>
            <a href="/section/' . $v['id'] . '">' . $lexic['section']['more'] . '</a>
          </div>

        </div>
        ';
            }
            echo '
        <!-- подразделы -->
        <h2>' . $lexic['section']['sub_sections_title'] . '</h2>
        ' . $sub_sections;
        }

        // Раздел содержит галереи
        if ($data['sub_galleries']) {
            $sub_galleries = '';
            $icon = 'fas fa-file-image';
            foreach ($data['sub_galleries'] as $v) {
                $icon = $v['gallery_type'] === 'audio' ? 'fas fa-file-audio' : $icon;
                $icon = $v['gallery_type'] === 'video_yt' ? 'fas fa-file-video' : $icon;
                $img_prev = $v['img_prev']
                    ? '
          <a href="/gallery/' . $v['id'] . '"
              style="background-image:url(' . $v['img_prev'] . ')"
              class="section_sub_item_prev"
              title="' . htmlspecialchars($lexic['section']['sub_gallery'] . ' "' . $v['title'] . '"') . '">
          </a>
          '
                    : '
          <a href="/gallery/' . $v['id'] . '"
              class="section_sub_item_prev"
              title="' . htmlspecialchars($lexic['section']['sub_gallery'] . ' "' . $v['title'] . '"') . '">
            <i class="' . $icon . '"></i>
          </a>
          ';
                $sub_galleries .= '
        <div class="section_sub_item">
          ' . $img_prev . '
          <div class="section_sub_item_desc">
            <h3>' . $v['title'] . '</h3>
            <div>' . $v['description'] . '...</div>
            <a href="/gallery/' . $v['id'] . '">' . $lexic['section']['more'] . '</a>
          </div>

        </div>
        ';
            }
            echo '
        <!-- галереи -->
        <h2>' . $lexic['section']['sub_galleries_title'] . '</h2>
        ' . $sub_galleries;
        }

        // Раздел содержит страницы
        if ($data['sub_pages']) {
            $sub_pages = '';
            foreach ($data['sub_pages'] as $v) {
                $img_prev = $v['img_prev']
                    ? '
          <a href="/page/' . $v['id'] . '"
              style="background-image:url(' . $v['img_prev'] . ')"
              class="section_sub_item_prev"
              title="' . htmlspecialchars($lexic['section']['sub_page'] . ' "' . $v['title'] . '"') . '">
          </a>
          '
                    : '
          <a href="/page/' . $v['id'] . '"
              class="section_sub_item_prev"
              title="' . htmlspecialchars($lexic['section']['sub_page'] . ' "' . $v['title'] . '"') . '">
            <i class="fas fa-file-alt"></i>
          </a>
          ';
                $sub_pages .= '
        <div class="section_sub_item">
          ' . $img_prev . '
          <div class="section_sub_item_desc">
            <h3>' . $v['title'] . '</h3>
            <div>' . $v['description'] . '...</div>
            <a href="/page/' . $v['id'] . '">' . $lexic['section']['more'] . '</a>
          </div>

        </div>
        ';
            }
            echo '
        <!-- страницы -->
        <h2>' . $lexic['section']['sub_pages_title'] . '</h2>
        ' . $sub_pages;
        }
        ?>

        <?php
        $this->load->helper('front/comments');
        (new comments(array_replace($conf['comments'], ['form' => $data['comments']])))->print_comments();
        ?>

    </div>
</main>

