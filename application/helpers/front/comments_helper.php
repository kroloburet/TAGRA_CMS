<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Класс вивода комментариев и формы комментирования в материале
 */
class Comments
{

    protected $CI;
    protected $conf;
    protected $lexic;

    /**
     *
     * @param array $conf Конфигурация комментариев
     */
    function __construct(array $conf = [])
    {
        $this->CI = &get_instance();
        $this->conf = $conf;
        $this->lexic = $this->CI->app('lexic');
    }

    /**
     * Вывод всех комментариев и формы
     * комментирования
     *
     * @return void
     */
    function print_comments()
    {
        // выборка комментариев материала
        $q = array_reverse(
            $this->CI->db->where(['public' => 1, 'url' => uri_string()])
                ->get('comments')
                ->result_array()
            , true);

        if ($this->conf['form'] !== 'off' || !empty($q)) {
            ?>

            <!--
            ########### Comments
            -->

            <div id="comments_layout">
                <div id="header_comments">
                    <h2><?= $this->lexic['comments']['mod_title'] ?></h2>
                    <div class="count_comments fas fa-comment">
                        &nbsp;<span><?= count($q) ?></span>
                    </div>
                </div>
                <?= $this->_build_tree($this->_tree_arr($q)) . PHP_EOL ?>
            </div>
        <?php }
        if ($this->conf['form'] !== 'off') { ?>

            <!-- форма комментирования -->
            <div class="TUI_noprint add_comment_box">
                <form class="add_comment_form">
                    <label class="TUI_input">
                        <input type="text" name="name"
                               placeholder="<?= htmlspecialchars($this->lexic['comments']['your_name']) ?>"
                            <?= ($this->conf['name_limit'] > 0
                                ? 'oninput="TUI.Lim(this, ' . $this->conf['name_limit'] . ')"'
                                : '') . PHP_EOL ?>
                               value="<?= $this->CI->input->cookie('comment_name')
                                   ? htmlspecialchars($this->CI->input->cookie('comment_name'))
                                   : '' ?>">
                    </label>
                    <label class="TUI_textarea">
                        <textarea name="comment" rows="5"
                                  placeholder="<?= htmlspecialchars($this->lexic['comments']['your_comment']) ?>"
                                  <?= $this->conf['text_limit'] > 0
                                      ? 'oninput="TUI.Lim(this, ' . $this->conf['text_limit'] . ')"'
                                      : '' ?>></textarea>
                    </label>
                    <input type="hidden" name="pid" value="0">
                    <div class="comment_form_actions">
                        <button type="button" onclick="Comments.add(form)">
                            <?= $this->lexic['comments']['send_form'] . PHP_EOL ?>
                        </button>
                    </div>
                </form>
            </div>
            <?php
        }
        $this->print_js();
    }

    /**
     * HTML для вывода комментария
     *
     * @param array $i Данные комментария
     * @return string HTML комментария
     */
    function print_comment(array $i)
    {
        if ($this->conf['rating']) {
            if (!$i['rating']) {
                $like = $dislike = 0;
                $disable = '';
            } else {
                $opt = json_decode($i['rating'], true);
                $like = $opt['like'];
                $dislike = $opt['dislike'];
                $cookie = $this->CI->input->cookie(md5('comment_rating_') . $i['id']);
                $disable = $cookie && $cookie === $_SERVER['REMOTE_ADDR'] ? 'comment_rating_disable' : '';
            }
        }

        $reply_item = $i['pid'] > 0 ? 'reply_item' : '';
        $name = filter_var($i['name'], FILTER_VALIDATE_EMAIL) ? explode('@', $i['name'])[0] : $i['name'];
        $reply_to_btn = $i['pid'] > 0
            ? '
            <span class="reply_to">' . $this->lexic['comments']['reply_to'] . '&nbsp;
              <a class="fas fa-level-up-alt" onclick="TUI.GoTo(\'#comment_' . $i['pid'] . '\')"></a>
            </span>
            '
            : '';
        $reply_btn = $this->conf['form'] == 'on'
            ? '
            <a class="show_reply_form" onclick="Comments.reply(this, ' . $i['id'] . ')">
              ' . $this->lexic['comments']['reply'] . '
            </a>
            '
            : '';
        $rating = $this->conf['rating']
            ? '
            <div class="comment_rating_like fas fa-thumbs-up ' . $disable . '"
                data-comment_id="' . $i['id'] . '"
                title="' . htmlspecialchars($this->lexic['comments']['like']) . '"
                onclick="Comments.rating(this)">&nbsp;
              <span class="comment_rating_total">' . $like . '</span>
            </div>
            <div class="comment_rating_dislike fas fa-thumbs-down ' . $disable . '"
                data-comment_id="' . $i['id'] . '"
                title="' . htmlspecialchars($this->lexic['comments']['dislike']) . '"
                onclick="Comments.rating(this)">&nbsp;
              <span class="comment_rating_total">' . $dislike . '</span>
            </div>
            '
            : '';
        return '
          <div class="comment_item ' . $reply_item . '" id="comment_' . $i['id'] . '">
            <div class="header_comment">
              <div class="comment_user">
                <span class="comment_pic" style="background-color:#' . substr(md5($i['name']), -6) . '">
                  ' . mb_substr($i['name'], 0, 1, 'UTF-8') . '
                </span>
                <span class="comment_name">' . $name . '</span>
                  ' . $reply_to_btn . '
              </div>
              <time class="comment_date" title="' . htmlspecialchars($this->lexic['comments']['public_date']) . '">
                ' . $i['creation_date'] . '
              </time>
            </div>
            <div class="comment_text">
              ' . $this->_replace_urls($i['comment']) . '
            </div>
            <div class="comment_action_box">
              ' . $reply_btn . $rating . '
            </div>
          </div>
        ';
    }

    /**
     * Вывод javascript логики
     *
     * @return void
     */
    function print_js()
    {
        if ($this->conf['form'] === 'off') return;
        ?>

        <script>
            const Comments = {
                <?php if ($this->conf['show'] > 0) {// Установлен лимит видимых комментов ?>

                /**
                 * Проверить якорь в URI и целевой элемент
                 * @returns {boolean}
                 */
                go_to: function () {
                    let hash = window.location.hash; // якорь
                    // якоря нет или нет комментария для перехода к нему
                    return Boolean(hash && $(hash));
                },

                /**
                 * Скрыть комментарии после лимита и вывести кнопку "Еще комментарии"
                 *
                 * @returns {void}
                 */
                hide: function () {
                    let comments = $('.comment_item'), // все комментарии
                        show = <?= $this->conf['show'] ?>, // лимит видимых
                        show_text = '<?= addslashes($this->lexic['comments']['more']) ?>',
                        hide_text = '<?= addslashes($this->lexic['comments']['hide']) ?>',
                        def_text = hide_text;
                    if (comments.length > show) {// лимит превышен, нужно скрывать
                        //скрыть комменты свыше лимита если нет якоря
                        if (!this.go_to()) {
                            comments.slice(show).hide();
                            def_text = show_text;
                        }
                        comments.last().after(// вывод кнопки
                            $('<div/>', {class: 'comments_more_btn TUI_noprint', text: def_text})
                                .on('click.Hide', function () {
                                    let c = $('.comment_item'), // все комменты
                                        h = c.slice(show); // которые скрываются
                                    if (c.is(':hidden')) {
                                        h.slideDown(200);
                                        $(this).text(hide_text);
                                    } else {
                                        h.slideUp(200);
                                        $(this).text(show_text);
                                    }
                                })
                        );
                    }
                },
                <?php } if ($this->conf['feedback']) {// Обратная связь ?>

                /**
                 * Показать уведомление о возможности обратной связи
                 *
                 * @returns {void}
                 */
                feedback: function () {
                    let name = $('.add_comment_form input[name="name"]')
                            .attr('placeholder', '<?= addslashes($this->lexic['comments']['your_name_or_mail']) ?>'),
                        msg = $('<div/>', {
                            class: 'feedback_msg',
                            text: '<?= addslashes($this->lexic['comments']['feedback_msg']) ?>'
                        });
                    name.on('focus.Feedback', function () {
                        $(this).before(msg);
                    });
                    name.on('blur.Feedback', function () {
                        msg.remove();
                    });
                },
                <?php } if ($this->conf['rating']) {// Рейтинг комментария ?>

                /**
                 * Рейтинг комментария
                 *
                 * @param {object} el Ссылка "this" на триггер
                 * @returns {void}
                 */
                rating: function (el) {
                    //выходить если уже проголосовал или в процессе
                    let i = 0,
                        a = [
                            'comment_rating_disable',
                            'comment_rating_process',
                            'comment_rating_good_msg',
                            'comment_rating_bad_msg'
                        ];
                    for (; i < a.length; i++) {
                        if ($(el).hasClass(a[i])) return;
                    }
                    let self = $(el),
                        id = self.data('comment_id'),
                        box = self.parent('.comment_action_box'),
                        all = box.find('.comment_rating_like, .comment_rating_dislike'),
                        action = self.hasClass('comment_rating_like') ? 'like' : 'dislike',
                        err_msg = $('<p/>', {
                            class: 'TUI_notice-r mini',
                            html: '<?= addslashes($this->lexic['basic']['error']) ?>'
                        }),
                        clear = function () {
                            self.removeClass('comment_rating_process comment_rating_good_msg comment_rating_bad_msg');
                            err_msg.remove();
                            return self;
                        },
                        err = function () {
                            clear().addClass('comment_rating_bad_msg');
                            box.append(err_msg);
                            setTimeout(function () {
                                clear();
                                all.removeClass('comment_rating_disable');
                            }, 4000);
                        },
                        ok = function (total) {
                            clear().addClass('comment_rating_good_msg').find('.comment_rating_total').text(total);
                            setTimeout(clear, 2000);
                        };
                    all.addClass('comment_rating_disable');
                    self.addClass('comment_rating_process');
                    // запрос изменения рейтинга
                    $.ajax({
                        url: '/do/comment_rating',
                        type: 'post',
                        data: {id: id, hash: '<?= md5('comment_rating_') ?>' + id, action: action},
                        dataType: 'json',
                        success: function (resp) {
                            switch (resp.status) {
                                case 'ok':
                                    ok(resp.rating[action]);
                                    break;
                                case 'error':
                                    err();
                                    break;
                                default :
                                    err();
                                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                            }
                        },
                        error: function () {
                            err();
                        }
                    });
                },
                <?php } if ($this->conf['form'] === 'on') {// Форма ответа на комментарий ?>

                /**
                 * Подготовка и вывод формы ответа
                 *
                 * @param {object} el Ссылка "this"
                 * @param {string} id Идентификатор комментария
                 * @returns {void}
                 */
                reply: function (el, id) {
                    // не выводить больше одной формы ответа
                    if ($('.add_comment_form').length > 1) return;
                    let form = $('.add_comment_form'), // основная форма комментирования
                        clone = form.clone(true), // клонировать основную форму
                        parent = $('#comment_' + id), // ответ для
                        cancel = $('<a/>', {// кнопка отмены
                            class: 'hide_reply_form',
                            text: '<?= addslashes($this->lexic['comments']['hide_form']) ?>'
                        })
                            .on('click.Reply', function () {
                                clone.remove();
                                form.slideDown(200)
                            });
                    clone.addClass('reply_form');
                    clone.find('[name="comment"]')
                        .attr('placeholder', '<?= addslashes($this->lexic['comments']['your_reply']) ?>')
                        .val(parent.find('.comment_name').text() + ', ');
                    clone.find('[name="pid"]').val(id);
                    clone.find('button').text('<?= addslashes($this->lexic['comments']['send_reply']) ?>').after(cancel);
                    parent.find('.comment_action_box').after(clone);
                    form.slideUp(200);
                },
                <?php } if ($this->conf['form'] !== 'off') {// Отправка комментария ?>

                /**
                 * Добавить комментарий
                 *
                 * @param {object} form Ссылка "form"
                 * @returns {void}
                 */
                add: function (form) {
                    let f = $(form),
                        name = f.find('[name="name"]'),
                        name_val = $.trim(name.val()),
                        comment = f.find('[name="comment"]'),
                        pid = f.find('[name="pid"]'),
                        id = new Date().getTime().toString(),
                        actions_box = f.find('.comment_form_actions'),
                        actions = actions_box.html(),
                        delay = 4000,
                        msg = function (m) {
                            actions_box.html(m);
                            setTimeout(function () {
                                actions_box.html(actions);
                            }, delay);
                        };
                    // валидация полей
                    if (!/\S/.test(name_val)) {
                        msg(
                            `<p class="TUI_notice-r mini TUI_full">
                            <?= addslashes($this->lexic['comments']['novalid_field']) . PHP_EOL ?>
                            <q>${name.attr('placeholder')}</q>
                           </p>`);
                        return;
                    }
                    if (!/\S/.test(comment.val())) {
                        msg(
                            `<p class="TUI_notice-r mini TUI_full">
                            <?= addslashes($this->lexic['comments']['novalid_field']) . PHP_EOL?>
                            <q>${comment.attr('placeholder')}</q>
                           </p>`);
                        return;
                    }
                    <?php if ($this->conf['feedback']) {// если обратная связь ?>

                    if (// в поле есть признак email и он корректный
                        ~name_val.indexOf('@')
                        && !/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(name_val)
                    ) {
                        msg(
                            `<p class="TUI_notice-r mini TUI_full">
                            <?= addslashes($this->lexic['comments']['novalid_mail']) . PHP_EOL?>
                            <q>${name.attr('placeholder')}</q>
                           </p>`);
                        return;
                    }
                    <?php } ?>

                    // блокировать кнопку
                    actions_box.find('button')
                        .attr('disabled', true)
                        .html('<i class="fas fa-spin fa-spinner"></i><?= addslashes($this->lexic['basic']['loading']) ?>');
                    // отправить запрос на добавление
                    $.ajax({
                        url: '/do/add_comment',
                        type: 'post',
                        data: {
                            id: id, // id коммента/ответа
                            pid: pid.val(), // id родительского коммента/ответа
                            name: name_val, // имя/email комментатора
                            comment: comment.val(), // текст коммента
                            url: '<?= uri_string() ?>', // url материала
                            lang: '<?= $this->CI->app('data.lang') ?>', // язык материала
                            conf: '<?= $this->conf['form'] ?>'// форма
                        },
                        dataType: 'json',
                        success: function (resp) {
                            switch (resp.status) {
                                // публикация без премодерации
                                case 'onpublic':
                                    comment.val(''); // очистка формы
                                    actions_box.html(actions);
                                    if (pid.val() !== '0') {// это ответ
                                        f.remove(); // удалить форму ответа
                                        $('#comment_' + pid.val()).after(resp.html)// вставить ответ в список комментариев
                                        $('.add_comment_form').slideDown(200); // отобразить основную форму если она скрыта
                                    } else {// это комментарий
                                        $('#header_comments').after(resp.html); // вставить комментарий в список
                                        TUI.GoTo('#comment_' + id); // перейти к комментарию
                                    }
                                    $('.count_comments span').text($('.comment_item').length); // обновить счетчик комментариев
                                    break;
                                // не опубликован, нужна премодерация
                                case 'premod':
                                    comment.val(''); // очистка формы
                                    msg(
                                        `<p class="TUI_notice-g mini TUI_full">
                                         <?= addslashes($this->lexic['comments']['premod_msg']) . PHP_EOL?>
                                        </p>`);
                                    if (pid.val() !== '0') {// удалить форму ответа, отобразить основную
                                        setTimeout(function () {
                                            f.remove();
                                            $('.add_comment_form').slideDown(200);
                                        }, delay);
                                    }
                                    break;
                                // имя зарезервировано
                                case 'reserved_name':
                                    msg(
                                        `<p class="TUI_notice-r mini TUI_full">
                                         <?= addslashes($this->lexic['comments']['reserved_name_msg']) . PHP_EOL?>
                                        </p>`);
                                    break;
                                // ошибки
                                case 'error':
                                    msg('<p class="TUI_notice-r mini TUI_full"><?= addslashes($this->lexic['basic']['error']) ?></p>');
                                    break;
                                default :
                                    console.error(`#### TAGRA ERROR INFO ####\n\n${resp}`);
                                    msg('<p class="TUI_notice-r mini TUI_full"><?= addslashes($this->lexic['basic']['error']) ?></p>');
                            }
                        },
                        error: function () {
                            msg('<p class="TUI_notice-r mini TUI_full"><?= addslashes($this->lexic['basic']['server_error']) ?></p>');
                        }
                    });
                }
                <?php } ?>

            };

            /**
             * Вызов методов событий елементов (слушателей)
             */
            window.addEventListener('load', function () {
                <?php if ($this->conf['show'] > 0) {// Кнопка "Еще комментарии" ?>

                Comments.hide();
                <?php } if ($this->conf['feedback']) {// Обратная связь ?>

                Comments.feedback();
                <?php } ?>

            });
        </script>
        <?php
    }

    /**
     * Получить многомерный массив
     *
     * @param array $arr Выборка из БД
     * @return array
     */
    protected function _tree_arr(array $arr)
    {
        $tree_arr = [];
        foreach ($arr as $v) {
            $tree_arr[$v['pid']][] = $v;
        }
        return $tree_arr;
    }

    /**
     * Построение дерева комментариев
     *
     * @param array $tree_arr Многомерный массив выборки из БД
     * @param int $pid Уровень, с которого начать построение дерева. по умолчанию 0 (корень)
     * @return string HTML дерево комментариев
     */
    protected function _build_tree(array $tree_arr, int $pid = 0)
    {
        // проверка параметров
        if (!is_array($tree_arr) || !isset($tree_arr[$pid])) return;
        $tree = '';
        foreach ($tree_arr[$pid] as $v) {
            $tree .= $this->print_comment($v);
            $tree .= $this->_build_tree($tree_arr, $v['id']);
        }
        return $tree;
    }

    /**
     * Заменить URL в комментарие на ссылки
     *
     * @param string $text Комментарий
     * @return string
     */
    function _replace_urls(string $text = null)
    {
        return preg_replace_callback('/https?:\/\/[\S]+/ui', function ($m) {
            return '<a href="' . $m[0] . '" target="_blank" rel="nofollow">' . $m[0] . '</a>';
        }, $text);
    }
}
