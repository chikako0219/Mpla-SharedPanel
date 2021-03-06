<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Post form for guest users.
 *
 * @package    mod_sharedpanel
 * @copyright  2016 NAGAOKA Chikako, KITA Toshihiro
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

/**
 * Notice :
 * This page allows access to guests(no login users).
 * Guests can post only.
 */

namespace mod_sharedpanel;

require_once('../../../config.php');

global $DB;

$cmid = required_param('id', PARAM_INT);
$instanceid = required_param('n', PARAM_INT);
$content = optional_param('cameracomment', null, PARAM_TEXT);
$name = optional_param('name', 'guest', PARAM_TEXT);
//$capture = optional_param('capture', null, PARAM_TEXT);
//var_dump($capture);

$capture = "";
if (is_uploaded_file($_FILES["capture"]["tmp_name"])){

    $capture .= "<img src='data:image/gif;base64,";
    $capture .= rotatecompress_img($_FILES["capture"]["tmp_name"], 600 );
    $capture .= "' width=85%>";
}

if (!is_null($content)) {
    $instance = $DB->get_record('sharedpanel', ['id' => $instanceid]);

    $cardobj = new card($instance);
    $cardid = $cardobj->add($content, $name, $capture,'camera', '0', '0');

    if (array_key_exists('capture', $_FILES)) {
        $cardobj->add_attachment_by_pathname($content, $cardid, $_FILES['tmp_name'], $_FILES['name']);
    }

    echo html_writer::div(get_string('msg_post_success', 'mod_sharedpanel'));
}

/*
//写真アップロード

echo '<form action="cameraupload.php" method="post" enctype="multipart/form-data">';
echo '<label for="capture">';
echo '＋写真を撮る';
echo '<input type="file" id="capture" name="capture" accept="image/*" capture="camera" style="display:none;" />';
echo '</form>';
*/

echo html_writer::start_tag('html');

echo html_writer::start_tag('head');
echo html_writer::empty_tag('meta', ['http-equiv' => 'Content-Type', 'content' => 'text/html; charset=utf-8']);
echo html_writer::empty_tag('meta', ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.2']);
echo html_writer::tag('title', get_string('post_message', 'mod_sharedpanel'));
echo html_writer::end_tag('head');

echo html_writer::start_tag('body');

echo html_writer::start_tag('form', ['action' => 'com.php', 'method' => 'post', 'enctype' => 'multipart/form-data']);


echo html_writer::start_div();
echo html_writer::tag('label', "upload", ['for' => 'capture']);
echo html_writer::start_div();
echo html_writer::empty_tag('input', ['type' => 'file', 'name' => 'capture', 'style' => 'accept=image/*; capture=camera; style="display:none;']);
echo html_writer::end_div();
echo html_writer::end_div();


echo html_writer::start_div();
echo html_writer::tag('label', get_string('message', 'mod_sharedpanel'), ['for' => 'cameracomment']);
echo html_writer::start_div();
echo html_writer::tag('textarea', '', ['name' => 'cameracomment', 'style' => 'width:20em; height:5em;']);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div();
echo html_writer::tag('label', get_string('name', 'mod_sharedpanel'), ['for' => 'cameracomment']);
echo html_writer::start_div();
echo html_writer::empty_tag('input', ['name' => 'name', 'type' => 'text']);
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::empty_tag('hr');

echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'id', 'value' => $cmid]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'n', 'value' => $instanceid]);

echo html_writer::empty_tag('input', ['type' => 'submit', 'value' => get_string('message', 'mod_sharedpanel')]);
echo html_writer::end_tag('form');

echo html_writer::end_tag('body');
echo html_writer::end_tag('html');

function rotatecompress_img($imgname, $width){
  $imagea= imagecreatefromjpeg($imgname);

  // http://blog.diginnovation.com/archives/1104/
  $exif_data = exif_read_data($imgname);
  if(isset($exif_data['Orientation']) && $exif_data['Orientation'] == 6){
    $imagea = imagerotate($imagea, 270, 0);
  }

  $imagea= imagescale($imagea, $width, -1);  // proportionally compress image with $width
  $jpegfile= tempnam("/tmp", "email-jpg-");
  imagejpeg($imagea,$jpegfile);
  imagedestroy($imagea);
  $attached= base64_encode(file_get_contents($jpegfile));
  unlink($jpegfile);
  return $attached;
}

