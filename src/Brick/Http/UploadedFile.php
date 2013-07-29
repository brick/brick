<?php

namespace Brick\Http;

/**
 * Represents a file uploaded via an HTML form (RFC-1867).
 */
class UploadedFile
{
    /**
     * Default MIME type assumed when not explicitly specified in a test.
     * This does not apply to an actual uploaded file, where the MIME type is always returned as is.
     *
     * @const string
     */
    const DEFAULT_MIME = 'application/octet-stream';

    /**
     * All the valid statuses for an uploaded file.
     *
     * @var array
     */
    private static $validStatuses = [
        UPLOAD_ERR_OK,
        UPLOAD_ERR_INI_SIZE,
        UPLOAD_ERR_FORM_SIZE,
        UPLOAD_ERR_PARTIAL,
        UPLOAD_ERR_NO_FILE,
        UPLOAD_ERR_NO_TMP_DIR,
        UPLOAD_ERR_CANT_WRITE,
        UPLOAD_ERR_EXTENSION
    ];

    /**
     * The temporary path of the uploaded file on the local filesystem, or null if the upload is not valid.
     *
     * @var string|null
     */
    private $path = null;

    /**
     * The size of the uploaded file, or null if the upload is not valid.
     *
     * @var integer|null
     */
    private $size = null;

    /**
     * The original file name, as sent by the browser, or null if the upload is not valid.
     *
     * @var string|null
     */
    private $originalName = null;

    /**
     * The file MIME type, as sent by the browser, or null if the upload is not valid.
     *
     * @var string|null
     */
    private $mimeType = null;

    /**
     * The status of the upload, one of the UPLOAD_ERR_* constants.
     *
     * @var integer
     */
    private $status;

    /**
     * Class constructor.
     *
     * The optional parameters can only be omitted in test mode.
     *
     * @param string  $path         The path of the temporary file on the local filesystem.
     * @param integer $size         The size of the uploaded file.
     * @param string  $originalName The original file name, as sent by the browser.
     * @param string  $mimeType     The file MIME type, as sent by the browser.
     * @param integer $status       The status of the upload, one of the UPLOAD_ERR_* constants.
     * @param boolean $test         Whether this UplodadedFile is a test, as opposed to a real file upload.
     *
     * @throws UploadedFileException
     */
    private function __construct($path, $size, $originalName, $mimeType, $status, $test)
    {
        if (! in_array($status, self::$validStatuses, true)) {
            throw new UploadedFileException('Invalid status code: ' . $status);
        }

        $this->status = $status;

        if ($this->isValid()) {
            $path         = (string) $path;
            $mimeType     = (string) $mimeType;

            if (! is_readable($path)) {
                throw new UploadedFileException('File is not readable: ' . $path);
            }

            $actualSize = filesize($path);

            if ($test) {
                $size = $actualSize;
                if ($originalName === null) {
                    $originalName = $path;
                }
            } else {
                if ($size !== $actualSize) {
                    throw new UploadedFileException('The reported file size does not match the actual size');
                }
                if (! is_uploaded_file($path)) {
                    throw new UploadedFileException('The file has not been uploaded by PHP');
                }
            }

            $this->path         = $path;
            $this->size         = $size;
            $this->originalName = $this->getFileName($originalName);
            $this->mimeType     = $mimeType;
        }
    }

    /**
     * Creates an UploadedFile from actual upload data, coming from the $_FILES superglobal.
     *
     * @param string  $path         The temporary path of the uploaded file.
     * @param integer $size         The reported file size.
     * @param string  $originalName The original file name, as sent by the browser.
     * @param string  $mimeType     The file MIME type, as sent by the browser.
     * @param integer $status       The upload status, one of the UPLOAD_ERR_* constants.
     *
     * @return UploadedFile
     * @throws UploadedFileException
     */
    public static function create($path, $size, $originalName, $mimeType, $status)
    {
        return new UploadedFile($path, $size, $originalName, $mimeType, $status, false);
    }

    /**
     * Creates an UploadedFile from an array, coming from the $_FILES superglobal.
     *
     * @param array $file
     *
     * @return UploadedFile
     * @throws UploadedFileException
     */
    public static function createFromPhpArray(array $file)
    {
        return UploadedFile::create($file['tmp_name'], $file['size'], $file['name'], $file['type'], $file['error']);
    }

    /**
     * Creates an UploadedFile for tests. Some security checks will be bypassed in this mode.
     *
     * @param string  $path     The path of the (supposedly) uploaded file on the filesystem.
     * @param string  $name     The original file name, as a browser would have sent it.
     * @param string  $mimeType The MIME type of the file, as a browser would have sent it.
     * @param integer $status   The upload status, one of the UPLOAD_ERR_* constants.
     *
     * @return UploadedFile
     * @throws UploadedFileException
     */
    public static function createTest($path, $name = null, $mimeType = self::DEFAULT_MIME, $status = UPLOAD_ERR_OK)
    {
        return new UploadedFile($path, 0, $name, $mimeType, $status, true);
    }

    /**
     * Removes any path information from a file name.
     *
     * @param string $name
     * @return string
     */
    private function getFileName($name)
    {
        $name = str_replace('\\', '/', $name);
        $pos = strrpos($name, '/');

        return ($pos === false) ? $name : substr($name, $pos + 1);
    }

    /**
     * Returns the temporary path of the uploaded file on the local filesystem, or null if the upload is not valid.
     *
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the size of the uploaded file, or null if the upload is not valid.
     *
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Returns the original file name, as sent by the browser, or null if the upload is not valid.
     *
     * @return string|null
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Returns the extension of the original file name, or null if the upload is not valid.
     *
     * @return string|null
     */
    public function getExtension()
    {
        if ($this->originalName === null) {
            return null;
        }

        return pathinfo($this->originalName, PATHINFO_EXTENSION);
    }

    /**
     * Returns the file MIME type, as sent by the browser, or null if the upload is not valid.
     *
     * @return string|null
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Returns the status of the upload, one of the UPLOAD_ERR_* constants.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns whether the file upload is valid.
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->status === UPLOAD_ERR_OK;
    }

    /**
     * Returns whether the form consumer has selected a file.
     *
     * @return boolean
     */
    public function isSelected()
    {
        return $this->status !== UPLOAD_ERR_NO_FILE;
    }
}
