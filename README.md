# <img align="right" src="https://zhabbler.ru/assets/images/icon.png" alt="Жабблер" title="Жабблер" width="12%"> zhabbler
Жабблер - социальная сеть аля пародия на tumblr<br>
# Как мне его установить (у меня уже есть php)?
Версии php можно использовать 8.0 и выше, но рекомендуется 7.4.<br>
```cd {ДОМАШНАЯЯ ПАПКА ВАШЕГО САЙТА}```<br>
```git clone https://github.com/zhabbler/zhabbler .```<br>
```composer install```<br>
А также, не забываем про ffmpeg.<br>
```sudo apt install ffmpeg```<br>
И ещё, необходимо поддержка коротких тегов, поэтому в файле ```php.ini``` в аттрибуте ```short_open_tag``` пишем значение ```On```.<br>
Также, необходимо файл ```app/zhabbler-example.env``` отредактировать (это файл конфиг) и переименовать его в ```.env```.<br>