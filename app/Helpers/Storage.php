<?php

declare(strict_types=1);

namespace App\Helpers;

class Storage
{
    private const string BASE_DIR = __DIR__."/../../storage/";

    /** Get a file's contents from the storage directory. Returns null on failure.
     * 
     * @param string $filepath
     * @return string|false
     */
    public static function get(string $filepath) : string|false
    {
        // Return the file contents if it exists
        return file_get_contents(self::BASE_DIR.$filepath);
    }

    /** Check for the existence of a file within the storage directory.
     * 
     * @param string $filepath
     * @return bool
     */
    public static function exists(string $filepath) : bool
    {
        return file_exists(self::BASE_DIR.$filepath);
    }

    /** Write to a file in the storage directory.
     * 
     * @param string $filepath
     * @param string $content
     * @param bool $append Append instead of overwrite
     * @return false|int
     */
    public static function write(string $filepath, string $content, bool $append = false) : false|int
    {
        return file_put_contents(self::BASE_DIR.$filepath, $content, $append ? FILE_APPEND : 0);
    }
}
