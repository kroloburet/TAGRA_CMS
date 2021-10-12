<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('content')) {

    /**
     * Контент материала
     *
     * @return void
     */
    function content()
    {
        $CI = &get_instance();
        $data = $CI->app('data');
        $conf = $CI->app('conf');
        ?>

        <!--
        ########### Контент
        -->

        <div class="touch" id="content">
            <h2>Контент</h2>
            <hr>
            <div class="content_control">
                <label>
                    Ширина левой колонки макета
                    <input type="text" name="content_l_width" size="3">%
                </label>
                <a href="#" onclick="TUI.Toggle('content_layout_info');return false">
                    О макете&nbsp;<i class="fas fa-angle-down"></i></a>
                <a href="#" class="content_layout_compact">Компактно <i class="fas fa-compress"></i></a>
            </div>
            <p id="content_layout_info" hidden>
                Чтобы основная часть страницы проще воспринималась визуально и была адаптивной, она представлена в виде
                макета. Сам макет разделен на 4 сегмента (колонки). Вы можете заполнять один и более этих сегментов
                своим контентом (содержимым). Чтобы разместить или редактировать контент в одном из сегментов, выберите
                его, кликнув по нему мышкой. Пустой сегмент, без контента, не будет отображаться на странице. Вы можете
                задать ширину левой колонки в процентном отношении к общей ширине шаблона. Значение ширины шаблона и
                ширина левой колонки по умолчанию для всех вновь создаваемых материалов устанавливается в настройках <q>Макет
                    и редактор</q> (в главном меню: Конфигурация). Чтобы вернуть макет к <q>компактному</q> виду,
                нажмите на <q>Компактно</q> в верхней части этого блока.
            </p>
            <div id="content_layout">
                <div id="content_t"><?= isset($data['content_t']) ? $data['content_t'] : '' ?></div>
                <div id="content_l"><?= isset($data['content_l']) ? $data['content_l'] : '' ?></div>
                <div id="content_r"><?= isset($data['content_r']) ? $data['content_r'] : '' ?></div>
                <div id="content_b"><?= isset($data['content_b']) ? $data['content_b'] : '' ?></div>
            </div>
        </div>

        <style>
            #content .content_control {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 10px;
            }

            #content .content_control label {
                flex-grow: 1;
                margin-right: 10px;
            }

            #content .content_control input {
                border: solid 1px var(--color-form-border);
                border-radius: var(--radius-border);
                background-color: var(--color-form-field-bg);
                margin: 0 .5em;
                padding: .2em;
                text-align: center;
                width: 3em;
                color: var(--color-font-field);
            }

            #content .content_control a {
                margin-right: 10px;
                flex-shrink: 0;
            }

            #content .content_control a:last-child {
                margin-right: 0;
            }

            #content #content_layout {
                display: grid;
                grid-template: 2.2em / 60% 1fr;
                grid-auto-rows: 2.2em;
                grid-gap: 10px 1.5em;
            }

            #content #content_t, #content #content_b {
                grid-column: 1 / -1;
            }

            #content #content_t, #content #content_l, #content #content_r, #content #content_b {
                border: solid 1px var(--color-form-border);
                border-radius: var(--radius-border);
                background-color: var(--color-form-field-bg);
                padding: .3em;
                overflow: auto;
            }

            #content #content_t.content_activated, #content #content_l.content_activated, #content #content_r.content_activated, #content #content_b.content_activated {
                grid-row: span 10;
                grid-column: 1 / -1;
                border: solid 1px #c0c0c0;
                outline: none;
            }
        </style>

        <script>
            ;(function () {
                const layoutsSelector = '#content_t, #content_l, #content_r, #content_b';
                const content = document.querySelector('#content');
                const layouts = content.querySelector('#content_layout');
                const segments = layouts.querySelectorAll(layoutsSelector);
                const inputLWidth = content.querySelector('input[name="content_l_width"]');
                const lWidth = '<?= isset($data['content_l_width'])
                    ? htmlspecialchars($data['content_l_width'])
                    : htmlspecialchars($conf['content_l_width']) ?>';

                /**
                 * Ширина левого сегмента
                 */
                inputLWidth.addEventListener('input', ({target: input}) => {
                    if (!/^[1-9]\d?(\.\d+)?$/.test(input.value)) {
                        input.classList.add('TUI_novalid');
                        return;
                    }
                    input.classList.remove('TUI_novalid');
                    layouts.style.gridTemplateColumns = `${input.value}% 1fr`;
                });

                /**
                 * Активный сегмент
                 */
                segments.forEach(segment => {
                    segment.addEventListener('click', () => {
                        segments.forEach(el => el.classList.remove('content_activated'));
                        segment.classList.add('content_activated');
                    });
                });

                /**
                 * Макет в компактный вид
                 */
                content.querySelector('.content_layout_compact').addEventListener('click', (e) => {
                    e.preventDefault();
                    segments.forEach(segment => {
                        segment.classList.remove('content_activated');
                    });
                });

                document.addEventListener('DOMContentLoaded', () => {
                    /**
                     * Ширина левого сегмента по умолчанию
                     */
                    inputLWidth.value = lWidth;
                    layouts.style.gridTemplateColumns = `${lWidth}% 1fr`;

                    /**
                     * Запуск текстового редактора по умолчанию
                     */
                    tinymce.init(Object.assign(mce_overall_conf, {
                        selector: layoutsSelector,
                        inline: true,// редактор появляется после клика в элементе
                    }));
                });
            })();
        </script>

        <?php
    }
}
