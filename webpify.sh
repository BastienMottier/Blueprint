#!/bin/sh

find . -type f -name "*.png" -exec sh -c 'cwebp -pass 10 -m 6 -mt -af "$1" -o "${1%.png}.webp"; mv "$1" ~/Documents/drawable-nodpi/' _ {} \;