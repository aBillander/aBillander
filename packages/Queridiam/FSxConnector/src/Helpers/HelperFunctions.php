<?php

/*
|--------------------------------------------------------------------------
| Helper functions.
|--------------------------------------------------------------------------
|
| ToDo: move to a proper location if necessary; Google this: 
| "laravel 5 where to put helpers".
|
*/

function fsx()
	{
return 'FSx say hello!';
}




// Unicode BOM is U+FEFF, but after encoded, it will look like this.
define ('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
define ('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
define ('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));
define ('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
define ('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));

function detect_utf_encoding($filename) {

    $text = @file_get_contents($filename);
  
  $text_encoding = mb_detect_encoding($text,"UTF-8, ISO-8859-1, ISO-8859-15"); // die($text_encoding);
  if (strpos($text_encoding,   "8859") >0) return  1;  // Need translation
  elseif (strpos($text_encoding, "-8") >0) return  0;
  else                                     return -1;  // Unknown
    $first2 = substr($text, 0, 2);
    $first3 = substr($text, 0, 3);
    $first4 = substr($text, 0, 3);
   
    if ($first3 == UTF8_BOM) return 'UTF-8';
    elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
    elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
    elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
    elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
    else   {$encoding = mb_detect_encoding($text, "UTF-8,ISO-8859-1,WINDOWS-1252"); ;}
}

function detect_utf_encodings($text) {
  
  $text_encoding = mb_detect_encoding($text,"UTF-8, ISO-8859-1, ISO-8859-15"); // die($text_encoding);
  if (strpos($text_encoding,   "8859") >0) return  1;  // Need translation
  elseif (strpos($text_encoding, "-8") >0) return  0;
  else                                     return -1;  // Unknown
    $first2 = substr($text, 0, 2);
    $first3 = substr($text, 0, 3);
    $first4 = substr($text, 0, 3);
   
    if ($first3 == UTF8_BOM) return 'UTF-8';
    elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
    elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
    elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
    elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
    else   {$encoding = mb_detect_encoding($text, "UTF-8,ISO-8859-1,WINDOWS-1252"); ;}
}

function utf8_encoded($text) {
      return $text;
}


