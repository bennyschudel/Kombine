Kombine
=
This is a command line PHP Script that takes a folder of images (all ImageMagick supported file formats) and combines
them into a single file (or multiples files if you wish ;). It also creates a CSS file for you - ready to go.

If you have any questions feel free to contact me.
// Benny

Usage
-
<pre>
    php kombine.php

    php kombine.php --sizes=16,32,64 --class-name=thumb

    php kombine.php -s16,32,64 -cthumb

    » This will create *16px*, *32px* and *64px* version of the images combined into a *single image*
      with *thumb* as class name in the css.


    php kombine.php --sizes=30x20,60x40 --dimensions=8 --format=jpg --class-name=thumb

    php kombine.php -s30x20,60x40 -d8 -fjpg -cthumb

    » This will create *30x20px* and *60x40px* versions of the images combined into a *single image* with *8 columns*,
      *JPG* format and *thumb* as class name in the css.


    php kombine.php --sizes=30x20 --names=custom --dimensions=8x2 --format=png --class-name=thumb \
                    --images-dir=styles/thumbs --styles-dir=styles

    php kombine.php -s30x20 -ncustom -d8x2 -fpng -cthumb --images-dir=styles/thumbs --styles-dir=styles

    » This will create *30x20px* version of the images combined splitted into *multiple images* with *8 columns*
      and *2 rows*, *PNG* format, *thumb* as class name and *custom* image version class name in the css.
      The final images will be located in the *styles/thumbs* folder and the css in the *styles* folder.
</pre>

Options
-
<pre>
    option              type            format          defaults        notes
    ------------------- --------------- --------------- --------------- -------------------
    -s | --sizes        int             .. or ..x..     16,32,64        [width] x [height]
    -n | --names        int                             16,32,64        custom size names
    -d | --dimensions   int             .. or ..x..     ?x1*            [columns] x [rows]
    -c | --class-name   string                          thumb
    -f | --format       string          jpg|png|gif     jpg
    --images-dir        string                          images/
    --styles-dir        string                          styles/
    --input-dir         string                          images/
    --output-dir        string                          build/..        **

    *) ? = total provided images
    **) a timestamp dir is always automatically added
</pre>

Requirements
-
+ [Linux based OS][1]
+ [PHP 5.3][2]
+ [ImageMagick][3]

Optimize
-
Before using the generated images in production I suggest an optimization tool like [optipng][1] or [jpegoptim][2].

[1]: http://www.ubuntu.com                  "Linux based OS"
[2]: http://www.php.net                     "PHP 5.3"
[3]: http://www.imagemagick.org             "ImageMagick"

[4]: http://optipng.sourceforge.net         "optipng"
[5]: https://github.com/glennr/jpegoptim    "jpegoptim"


