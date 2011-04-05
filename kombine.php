<?php

/**
 * This PHP script create sprites and css of given images
 * 
 * usage: php kombine.php -s16,32,64 -d8x2 -cthumb
 *        php kombine.php --sizes=16x16,32x32,64x64 --dimensions=8x2 --class-name=thumb
 * 
 * author: Benjamin Schudel <benjamin.schudel at gmail>
 * date: 20011-04-05
 */

$sizes			= "16,32,64";
$dimensions		= "";
$columns		= 0;
$rows			= 0;
$class_name		= "thumb";
$format			= "png";		// supported formats jpg|png|gif
$images_dir		= "images";
$styles_dir		= "styles";
$input_dir		= "images";
$output_dir		= "build";

$files		 	= array();
$lines 			= array();
$stripes 		= array();

/**
 * Gets command line options in short an long formats and maps given short format to long.
 * 
 * Example:
 * 
 *   -s16,32,64                 => array( sizes => 16,32, 64 )
 *   -s16,32,64 --sizes=16,32   => array( sizes => 16,32 )
 *   --sizes=16,32,64           => array( sizes => 16,32,64 )
 * 
 * @param string $sopt 
 * @param array $lopt 
 * 
 * @return array
 */
function getCliOptions($sopt, $lopt) {
	$opt = getopt($sopt, $lopt);
	foreach (explode(':', trim(str_replace('::', ':', $sopt), ':')) as $index => $value) {
		$param = str_replace(':', '', $lopt[$index]);
		if (isset($opt[$value]) && !isset($opt[$param])) {
			$opt[$param] = $opt[$value];
		}
		if (isset($opt[$value])) {
			unset($opt[$value]);
		}
	}
	
	return $opt;
}

/**
 * Checks if a given option value is valid. If not throws an error.
 *
 * @param array $opt
 * @param string $name 
 * @param string $reg 
 * @param value $default
 * 
 * @return value or exit
 */
function getOption($opt, $name, $reg, $default = null) {
	if (isset($opt[$name])) {
		if (preg_match($reg, @$opt[$name])) {
			
			return $opt[$name];
		}
		else {
			
			exit("ERROR: Invalid {$name} argument\n");
		}
	}
	
	return $default;
}

/**
 * Returns the to_dir in relative to the from_dir
 *
 * @param string $from_dir 
 * @param string $to_dir 
 *
 * @return string
 */
function getRelDirTo($from_dir, $to_dir) {
	if (substr($to_dir, 0, 1) === '/') {
		
		return $to_dir;
	}
	
	$from_dir = explode('/', trim($from_dir, '/'));
	$to_dir = explode('/', trim($to_dir, '/'));
	
	$rel = 0;
	for ($i = 0; $i < count($from_dir); $i++) {
		if ($from_dir[$i] === @$to_dir[$i]) {
			$to_dir[$i] = null;
			continue;
		}
		$rel++;
	}
	$to_dir = array_filter($to_dir, function($v){ return !is_null($v); });
	for ($i = 0; $i < $rel; $i++) {
		array_unshift($to_dir, '..');
	}
	
	return implode('/', $to_dir).'/';
}


/*** Main ***/

	// cli options
$sopt = "s::d::c::f::";
$lopt  = array(
	"sizes::",
	"dimensions::",
	"class-name::",
	"format::",
	"images-dir::",
	"styles-dir::",
	"input-dir::",
	"output-dir::",
);
$opt = getCliOptions($sopt, $lopt);
		// available options
$sizes = explode(',', getOption($opt, 'sizes', '!^[\dx,]+$!', $sizes));
$dimensions = getOption($opt, 'dimensions', '!^[\dx]+$!', $dimensions);
$class_name = getOption($opt, 'class-name', '!^[\w\-_]+$!', $class_name);
$format = strtolower(getOption($opt, 'format', '!^(jpg|png|gif)$!i', $format));
foreach (array('images-dir', 'styles-dir', 'input-dir', 'output-dir') as $dir) {
	$var = str_replace('-', '_', $dir);
	${$var} = getOption($opt, $dir, '!^.+$!', ${$var});
}
		// prepare options
			// convert single sizes to ..x..
foreach ($sizes as $key => $size) {
	@list($size_x, $size_y) = explode('x', $size);
	if ($size_y === null) {
		$sizes[$key] = implode('x', array($size_x, $size_x));
	}
}
			// add trailing slash
foreach (array('images_dir', 'styles_dir', 'input_dir', 'output_dir') as $var) {
	if (strlen(${$var}) > 0) {
		${$var} = rtrim(${$var}, '/').'/';
	}
}

	// create output dirs
foreach (array('input_dir', 'output_dir') as $var) {
	if (!is_dir(${$var})) {
		
		exit("ERROR: {${$var}} is not a directory\n");
	}
}
$now = date('ymd-His');
$output_dir .= "{$now}/";
$output = shell_exec("mkdir -p {$output_dir}".ltrim($images_dir, '/'));
$output = shell_exec("mkdir -p {$output_dir}".ltrim($styles_dir, '/'));

	// get rel dir from styles to images for css
$images_rel_dir = getRelDirTo($styles_dir, $images_dir);

	// load input files
foreach (glob("{$input_dir}*") as $file) {
	$files[] = pathinfo($file);
}
$total = count($files);
		// set dimensions
@list($columns, $rows) = explode('x', $dimensions);
$columns = (int)$columns;
$rows = (int)$rows;
if ($columns === 0) {
	$columns = $total;
}
if ($rows === 0) {
	$rows = ceil($total / $columns);
}
$dimensions = implode('x', array($columns, $rows));
$page_size = $columns * $rows;
$pages = ceil($total / $page_size);

	// create image stripes
foreach ($sizes as $size) {
	list($size_x, $size_y) = explode('x', $size);
	$prefix = ($size_x !== $size_y) ? $size : $size_x;
	$stripe = "{$class_name}-{$prefix}.{$format}";
	$output = shell_exec("montage {$input_dir}* -tile {$dimensions} -geometry {$size} -quality 90 {$output_dir}{$images_dir}{$stripe}");
	$stripes[$size] = $stripe;
}

	// create css
$lines[] = "/* Kombine */";
$lines[] = ".{$class_name} {
	display: inline-block;
	background-repeat: no-repeat;
}";
$lines[] = "";
foreach ($stripes as $size => $stripe) {
	list($size_x, $size_y) = explode('x', $size);
	$prefix = ($size_x !== $size_y) ? $size : $size_x;
	$bg_image = ($pages == 1) ? "\n\tbackground-image: url({$images_rel_dir}{$stripe});" : "";
	$lines[] = ".{$class_name}.{$class_name}-{$prefix} {{$bg_image}
	width: {$size_x}px;
	height: {$size_y}px;
}";
}
$lines[] = "";
foreach ($sizes as $size) {
	list($size_x, $size_y) = explode('x', $size);
	$prefix = ($size_x !== $size_y) ? $size : $size_x;
	$col = $row = $i = $page = 0;
	foreach ($files as $index => $info) {
		if ($col && ($col % $columns === 0)) {
			$row++;
			$col = 0;
		}
		$pos_x = $col * (int)$size_x;
		$pos_y = $row * (int)$size_y; 
		if ($pos_x > 0) {
			$pos_x = "-{$pos_x}px";
		}
		if ($pos_y > 0) {
			$pos_y = "-{$pos_y}px";
		}
		$col++;
		$bg_image = "";
		if ($pages > 1) {
			$bg_image_url = str_replace(".{$format}", "-{$page}.{$format}", $stripes[$size]);
			$bg_image = "\n\tbackground-image: url({$images_rel_dir}{$bg_image_url});";
			if ($i && (($i + 1) % $page_size === 0)) {
				$page++;
				$col = $row = 0;
			}
		}
		$lines[] = ".{$class_name}.{$class_name}-{$prefix}.{$class_name}-{$info['filename']} {{$bg_image}
	background-position: {$pos_x} {$pos_y};
}";
		$i++;
	}
	$lines[] = "";
}
array_pop($lines);
$lines[] = "/* /Kombine */";
		// create css file
file_put_contents("{$output_dir}{$styles_dir}{$class_name}.css", implode("\n", $lines));

	// bye bye
print "successfully built > {$output_dir}\n";

exit();
