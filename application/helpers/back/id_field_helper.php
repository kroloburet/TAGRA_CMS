<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('id_field')) {

    /**
     * Поле "ID материала"
     *
     * @param string $table Имя таблицы материала в БД
     */
    function id_field(string $table)
    {
        $id = round(microtime(true) * 1000);
        ?>

        <div id="id_field">
            ID материала <i class="fas fa-info-circle TUI_red" onmouseover="TUI.Hint(this)"></i>
            <pre class="TUI_Hint">
                Уникальный идентификатор материала который
                станет частью uri (ссылкой на этот материал).
                Допускается только буквенно-цифровые символы
                латиницы, дефисы и нижние подчеркивания.
                <b class="TUI_red">Внимание! После сохранения это поле будет
                    доступно только для чтения!
                    Обязательно для заполнения!</b>
            </pre>
            &nbsp;<a href="#" class="fas fa-spell-check TUI_blue id_transliterate" onmouseover="TUI.Hint(this)"></a>
            <pre class="TUI_Hint">
                Транслитерировать заголовок и применить
                как ID материала. Это полезно когда нужно
                чтобы ссылка выглядела интуитивно осмысленной.
            </pre>
            &nbsp;<a href="#" class="fas fa-undo TUI_blue id_cancel" onmouseover="TUI.Hint(this)"></a>
            <pre class="TUI_Hint">
                Сбросить значение в поле к ID по умолчанию.
            </pre>
            <label class="TUI_input">
                <input type="text" name="id" oninput="TUI.Lim(this, 250)" value="<?= $id ?>">
            </label>
        </div>

        <script>
            /**
             * Управление полем "ID материала"
             *
             * @returns {void}
             */
            window.addEventListener('load', function () {
                let _ = document.querySelector('#id_field'), // Контейнер поля
                    title = document.querySelector('input[name="title"]'), // Поле "Заголовок материала"
                    id = _.querySelector('input[name="id"]'), // Поле "ID материала"
                    transliterate_btn = _.querySelector('.id_transliterate'), // Кнопка транслитерации заголовка в ID
                    cancel_btn = _.querySelector('.id_cancel'), // Кнопка сброса ID по умолчанию
                    submit_btn = document.querySelector('.this_form_control button'), // Кнопка отправки формы добавления материала
                    def = '<?= $id ?>'; // ID по умолчанию

                /**
                 * Транслитерация строки в латиницу
                 * для применения как ID
                 *
                 * @param input Входная строка
                 * @returns {string} Транслитерированная строка
                 */
                const transliterate = function (input) {
                    submit_btn.disabled = true; // Блокировать кнопку отправки формы
                    input = input.trim(); // Подготовить входную строку
                    let output = '', // Будет содержать транслитерированную строку
                        sep = '-', // Замена пробелам и иным не транслитерируемым символам
                        i = 0, // Счетчик символов входной строки
                        char = { // Набор правил транслитерации посимвольно
                            // Прописные
                            'А': 'A',
                            'Б': 'B',
                            'В': 'V',
                            'Г': 'G',
                            'Д': 'D',
                            'Е': 'E',
                            'Ж': 'Zh',
                            'З': 'Z',
                            'И': 'I',
                            'Й': 'Y',
                            'К': 'K',
                            'Л': 'L',
                            'М': 'M',
                            'Н': 'N',
                            'О': 'O',
                            'П': 'P',
                            'Р': 'R',
                            'С': 'S',
                            'Т': 'T',
                            'У': 'U',
                            'Ф': 'F',
                            'Х': 'Kh',
                            'Ц': 'Ts',
                            'Ч': 'Ch',
                            'Ш': 'Sh',
                            'Щ': 'Shch',
                            'Ъ': '',
                            'Ы': 'Y',
                            'Ь': '',
                            'Э': 'E',
                            'Ю': 'Yu',
                            'Я': 'Ya',
                            'Є': 'E',
                            'І': 'I',
                            'Ґ': 'G',
                            'Â': 'A',
                            'Ä': 'A',
                            'Å': 'A',
                            'Á': 'A',
                            'À': 'A',
                            'Ă': 'A',
                            'Ā': 'A',
                            'Ã': 'A',
                            'Ą': 'A',
                            'Ć': 'C',
                            'Č': 'C',
                            'Ċ': 'C',
                            'Ç': 'C',
                            'Ď': 'D',
                            'Đ': 'D',
                            'É': 'E',
                            'È': 'E',
                            'Ê': 'E',
                            'Ě': 'E',
                            'Ė': 'E',
                            'Ё': 'Yo',
                            'Ē': 'E',
                            'Ę': 'E',
                            'Ǵ': 'G',
                            'Ğ': 'G',
                            'Ġ': 'G',
                            'Ģ': 'G',
                            'Ħ': 'H',
                            'Í': 'I',
                            'Ì': 'I',
                            'Î': 'I',
                            'Ī': 'I',
                            'Ɨ': 'I',
                            'Į': 'I',
                            'Ї': 'Yi',
                            'Ĺ': 'L',
                            'Ľ': 'L',
                            'Ł': 'L',
                            'Ň': 'N',
                            'Ń': 'N',
                            'Ñ': 'N',
                            'Ņ': 'N',
                            'Ó': 'O',
                            'Ò': 'O',
                            'Ō': 'O',
                            'Ø': 'O',
                            'Ǿ': 'O',
                            'Ő': 'O',
                            'Ö': 'O',
                            'Õ': 'O',
                            'Ô': 'O',
                            'Ɵ': 'O',
                            'Ř': 'R',
                            'Š': 'S',
                            'Ś': 'S',
                            'Ș': 'S',
                            'Ş': 'S',
                            'Ť': 'T',
                            'Ü': 'U',
                            'Ú': 'U',
                            'Ù': 'U',
                            'Ů': 'U',
                            'Û': 'U',
                            'Ŭ': 'U',
                            'Ű': 'U',
                            'Ū': 'U',
                            'Ʉ': 'U',
                            'Ų': 'U',
                            'Ý': 'Y',
                            'Ÿ': 'Y',
                            'Ž': 'Z',
                            'Ź': 'Z',
                            'Ż': 'Z',
                            'Ƶ': 'Z',
                            'Æ': 'AE',
                            'Ǽ': 'AE',
                            'Ǣ': 'AE',
                            'Œ': 'OE',
                            'ẞ': 'S',
                            // Строчные
                            'а': 'a',
                            'б': 'b',
                            'в': 'v',
                            'г': 'g',
                            'д': 'd',
                            'е': 'e',
                            'ж': 'zh',
                            'з': 'z',
                            'и': 'i',
                            'й': 'y',
                            'к': 'k',
                            'л': 'l',
                            'м': 'm',
                            'н': 'n',
                            'о': 'o',
                            'п': 'p',
                            'р': 'r',
                            'с': 's',
                            'т': 't',
                            'у': 'u',
                            'ф': 'f',
                            'х': 'kh',
                            'ц': 'ts',
                            'ч': 'ch',
                            'ш': 'sh',
                            'щ': 'shch',
                            'ъ': '',
                            'ы': 'y',
                            'ь': '',
                            'э': 'e',
                            'ю': 'yu',
                            'я': 'ya',
                            'є': 'e',
                            'і': 'i',
                            'ґ': 'g',
                            'â': 'a',
                            'ä': 'a',
                            'å': 'a',
                            'á': 'a',
                            'à': 'a',
                            'ă': 'a',
                            'ā': 'a',
                            'ã': 'a',
                            'ą': 'a',
                            'ć': 'c',
                            'č': 'c',
                            'ċ': 'c',
                            'ç': 'c',
                            'ď': 'd',
                            'đ': 'd',
                            'é': 'e',
                            'è': 'e',
                            'ê': 'e',
                            'ě': 'e',
                            'ė': 'e',
                            'ё': 'yo',
                            'ē': 'e',
                            'ę': 'e',
                            'ǵ': 'g',
                            'ğ': 'g',
                            'ġ': 'g',
                            'ģ': 'g',
                            'ħ': 'h',
                            'í': 'i',
                            'ì': 'i',
                            'î': 'i',
                            'ī': 'i',
                            'ɨ': 'i',
                            'į': 'i',
                            'ї': 'yi',
                            'ĺ': 'l',
                            'ľ': 'l',
                            'ł': 'l',
                            'ň': 'n',
                            'ń': 'n',
                            'ñ': 'n',
                            'ņ': 'n',
                            'ó': 'o',
                            'ò': 'o',
                            'ō': 'o',
                            'ø': 'o',
                            'ǿ': 'o',
                            'ő': 'o',
                            'ö': 'o',
                            'õ': 'o',
                            'ô': 'o',
                            'ɵ': 'o',
                            'ř': 'r',
                            'š': 's',
                            'ś': 's',
                            'ș': 's',
                            'ş': 's',
                            'ť': 't',
                            'ü': 'u',
                            'ú': 'u',
                            'ù': 'u',
                            'ů': 'u',
                            'û': 'u',
                            'ŭ': 'u',
                            'ű': 'u',
                            'ū': 'u',
                            'ʉ': 'u',
                            'ų': 'u',
                            'ý': 'y',
                            'ÿ': 'y',
                            'ž': 'z',
                            'ź': 'z',
                            'ż': 'z',
                            'ƶ': 'z',
                            'æ': 'ae',
                            'ǽ': 'ae',
                            'ǣ': 'ae',
                            'œ': 'oe',
                            'ß': 's',
                        };

                    // Посимвольная транслитерация входной строки
                    while (input.length > i) {
                        output += char[input[i]] !== undefined
                            ? char[input[i]]
                            : !/[\w-~]/g.test(input[i])
                                ? sep : input[i];
                        i++;
                    }

                    // Удалить повторяющийся и по краям заменитель, вставить результат в поле ID
                    output = output.replace(/-{2,}/g, sep).replace(/^-|-$/g, '');
                    id.value = output;

                    // Запрос на уникальность ID
                    fetch('/admin/check_id', {
                        headers: {'Content-type': 'application/x-www-form-urlencoded'},
                        method: 'post',
                        body: `id=${output}&tab=<?= $table ?>`,
                    })
                        .then(response => response.text())
                        .then(status => {
                            switch (status) {
                                case 'ok':
                                    submit_btn.disabled = false;
                                    break;
                                case 'found':
                                    alert('Материал с таким ID уже существует!\nИзмените ID и продолжайте.');
                                    break;
                                default:
                                    console.error(`#### TAGRA ERROR INFO ####\n\n${status}`);
                                    alert('Ой! Ошибка..( Не удалось проверить ID.\nСведения о неполадке выведены в консоль.');
                            }
                        })
                        .catch(error => {
                            console.error(`#### TAGRA ERROR INFO ####\n\n${error}`);
                            alert('Ой! Ошибка соединения..(\nСведения о неполадке выведены в консоль.\nВозможно это проблемы на сервере или с сетью Интернет.');
                        });

                    return output;
                }

                // Работа кнопки транслитерации заголовка
                transliterate_btn.onclick = e => {
                    e.preventDefault();
                    let input = title.value.trim();
                    if (!input) {
                        alert('Сначала задайте заголовок для этого материала!');
                        return;
                    }
                    transliterate(input);
                }

                // Работа кнопки сброса ID
                cancel_btn.onclick = e => {
                    e.preventDefault();
                    id.value = def;
                }

                // Проверить и транслитерировать ID по изменению в поле
                id.onchange = () => {
                    let input = id.value.trim();
                    if (!input) {
                        alert('Задайте ID для этого материала!');
                        return;
                    }
                    transliterate(input);
                }
            });
        </script>
        <?php
    }
}
