#!/bin/sh
set -eu
PHP_SOURCE=${PHP_SOURCE:-/opt/zjmf-lab/php-7.2.24}
if [ ! -f "$PHP_SOURCE/main/php_config.h" ]; then
    printf '%s\n' '需要已配置的 PHP 7.2.24 NTS 源码头文件。' >&2
    exit 1
fi
gcc -shared -fPIC -O2 -g -Wall -Wextra -Werror -Wno-unused-parameter \
    -I"$PHP_SOURCE" -I"$PHP_SOURCE/main" -I"$PHP_SOURCE/Zend" \
    -I"$PHP_SOURCE/TSRM" -I"$PHP_SOURCE/ext" \
    -o idcsmart.so zjmf_license.c
printf '%s\n' '已编译 idcsmart.so（PHP 7.2 NTS / x86_64）。'
