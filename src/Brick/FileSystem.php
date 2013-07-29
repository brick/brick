<?php

namespace Brick;

/**
 * Filesystem utilities.
 * @todo merge into Brick\Filesystem\Filesystem
 */
abstract class FileSystem
{
    /**
     * @param  string $source      The source file/folder.
     * @param  string $destination The destination path.
     * @return integer             The number of elements copied.
     * @throws \RuntimeException
     */
    public static function recursiveCopy($source, $destination)
    {
        if (file_exists($destination)) {
            throw new \RuntimeException('The destination path already exists');
        }

        if (is_file($source)) {
            copy($source, $destination);
        }
        elseif (is_dir($source)) {
            mkdir($destination);
            foreach (scandir($source) as $item) {
                if ($item != '.' && $item != '..') {
                    self::recursiveCopy(
                        $source . DIRECTORY_SEPARATOR . $item,
                        $destination . DIRECTORY_SEPARATOR . $item
                    );
                }
            }
        } else {
            throw new \RuntimeException(sprintf('The source file "%s" is of an unsupported type', $source));
        }
    }
}
