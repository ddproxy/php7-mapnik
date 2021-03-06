--TEST--
\Mapnik\Image
--SKIPIF--
<?php if (!extension_loaded("mapnik")) print "skip"; ?>
--FILE--
<?php

// Setup
$imageFile = sys_get_temp_dir() . '/image-test.png';
$configOutput = [];
exec('mapnik-config --input-plugins', $configOutput);
\Mapnik\DatasourceCache::registerDatasources($configOutput[0]);
$map = new \Mapnik\Map(640, 480);
$basePath = realpath(dirname(__FILE__) . '/data');
$map->loadXmlFile($basePath . '/world.xml', false, $basePath);
$map->zoomAll();

// Assert Image instantiation
$image = new \Mapnik\Image(640, 480);
print ($image instanceof \Mapnik\Image);

// Give the image some data
$renderer = new \Mapnik\AggRenderer($map, $image);
$renderer->apply();

// Assert saving rendered image to file
$image->saveToFile($imageFile);
print file_exists($imageFile);
unlink($imageFile);

// Assert image can be saved to string
$output = $image->saveToString('png');
$phpImageResource = imagecreatefromstring($output);
print ($phpImageResource != false);

?>
--EXPECT--
111