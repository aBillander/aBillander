<?php

/**
 * @see: https://github.com/indiehd/filename-sanitizer

 Usage:
 
$sanitizer = new FilenameSanitizer('On / Off Again: My Journey to Stardom.jpg' . chr(0));

$sanitizer->stripIllegalFilesystemCharacters();

// The resultant string is free of the offending characters.

var_dump($sanitizer->getFilename());

A couple additional methods are available for further sanitizing the filename. These methods may be chained in any order.

$sanitizer = new FilenameSanitizer('<?php malicious_function(); ?>`rm -rf /`' . chr(0));

$sanitizer->stripPhp()
    ->stripRiskyCharacters()
    ->stripIllegalFilesystemCharacters();
    
var_dump($sanitizer->getFilename());

 */

namespace App\Helpers;

class FilenameSanitizer // implements FilenameSanitizerInterface
{
    /**
     * @var array
     */
    protected $illegalCharacters = [];

    /**
     * @var string
     */
    protected $filename;

    public function __construct(string $filename = '')
    {
        $this->initializeIllegalCharacters();

        $this->setFilename($filename);
    }

    /**
     * Specify which characters shall be considered illegal (that is, would
     * cause an error or exception if included in a filename) across the
     * target platforms.
     *
     * @return $this
     *
     * @see: https://kb.acronis.com/content/39790
     * @see: https://stackoverflow.com/questions/1976007/what-characters-are-forbidden-in-windows-and-linux-directory-names
     * @see: https://superuser.com/questions/204287/what-characters-are-forbidden-in-os-x-filenames
     */
    protected function initializeIllegalCharacters()
    {
        $this->illegalCharacters['unix'] = [
            '/',
            chr(0),
        ];

        $this->illegalCharacters['windows'] = [
            '<',
            '>',
            ':',
            '"',
            '/',
            '\\',
            '|',
            '?',
            '*',
        ];

        // 0-31 (ASCII control characters)

        for ($i = 0; $i < 32; $i++) {
            $this->illegalCharacters['windows'][] = chr($i);
        }

        $this->illegalCharacters['macos'] = [
            ':'
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getIllegalCharacters()
    {
        return $this->illegalCharacters;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Strip PHP tags and any encapsulated code; while a closing tag is not
     * required for code following an open tag to be stripped, it will be
     * stripped, too, if present. Short open tags are treated the same as long.
     *
     * @return $this
     */
    public function stripPhp()
    {
        $this->setFilename(filter_var($this->getFilename(), FILTER_SANITIZE_STRING));

        return $this;
    }

    /**
     * Strip characters that might be considered risky and therefore prone to
     * abuse through various injection-style attacks.
     *
     * @return $this
     */
    public function stripRiskyCharacters()
    {
        $options = [
            'flags' => FILTER_FLAG_STRIP_BACKTICK | FILTER_FLAG_STRIP_LOW
        ];

        $this->setFilename(
            filter_var($this->getFilename(), FILTER_SANITIZE_STRING, $options)
        );

        return $this;
    }

    /**
     * Strip illegal filesystem characters.
     *
     * @return $this
     */
    public function stripIllegalFilesystemCharacters( $replacement = '' )
    {
        $illegalCharacters = $this->getIllegalCharacters();

        $illegalCharactersAsString = implode('', array_merge(
            $illegalCharacters['unix'],
            $illegalCharacters['windows'],
            $illegalCharacters['macos']
        ));

        $escapedRegex = preg_quote($illegalCharactersAsString, '/');

        $this->setFilename(preg_replace('/[' . $escapedRegex . ']/', $replacement, $this->getFilename()));

        return $this;
    }
}
