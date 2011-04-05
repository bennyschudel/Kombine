Kombine
=

This is a commandline PHP Script that takes a folder of images (all ImageMagick supported file formats) and combines
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


    php kombine.php --sizes=30x20 --dimensions=8x2 --format=png --class-name=thumb --images-dir=styles/thumbs \
                    --styles-dir=styles

    php kombine.php -s30x20 -d8x2 -fpng -cthumb -istyles/thumbs -ystyles

    » This will create *30x20px* version of the images combined splitted into *multiple images* with *8 columns* 
      and *2 rows*, *PNG* format and *thumb* as class name in the css. The final images will be located in the 
      *styles/thumbs* folder and the css in the *styles* folder.
</pre>

Options
-
<pre>
    option              type            format          defaults        notes
    ------------------- --------------- --------------- --------------- -------------------
    -s | --sizes        int             .. or ..x..     16,32,64        [width] x [height]
    -d | --dimensions   int             .. or ..x..     ?x1*            [columns] x [rows]
    -c | --class-name   string                          thumb
    -f | --format       string          jpg|png|gif     jpg
    -i | --images-dir   string                          images
    -y | --styles-dir   string                          styles
    
    *) ? = total provided images
</pre>


Optimize
-
Before using the generated images in production I suggest to open them in Photoshop and save it for web.


