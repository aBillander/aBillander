<?php

namespace aBillander\Installer\Helpers;

class LicenseReader
{
    /**
     * Get license text.
     *
     * @param $folder
     * @return string
     */
    static function getText()
    {
        $file = base_path('LICENSE.md');

        if ( file_exists($file) )
        {
            $text = file_get_contents($file);

            $text = self::nl2p($text);

            return $text;
        }        

        return null;
    }

    static function nl2p($text, $cssClass=''){

      // Return if there are no line breaks.
      if (!strstr($text, "\n")) {
         return $text;
      }

      // Return if text is HTML (simple check).
      // Etiquetas abren con < y cierran con </, excepto <br />
      if ( strstr($text, "<") && ( strstr($text, "</") || strstr($text, "/>") ) ) {
         return $text;
      } else if ( strstr($text, "<br>") || strstr($text, "<BR>") ) return $text;

      // Add Optional css class
      if (!empty($cssClass)) {
         $cssClass = ' class="' . $cssClass . '" ';
      }

      // put all text into <p> tags
      $text = '<p' . $cssClass . '>' . $text . '</p>';

      // replace all newline characters with paragraph
      // ending and starting tags
      $text = str_replace("\n", "</p>\n<p" . $cssClass . '>', $text);

      // remove empty paragraph tags & any cariage return characters
      $text = str_replace(array('<p' . $cssClass . '></p>', '<p></p>', "\r"), '', $text);

      return $text;

   } // end nl2p
}
