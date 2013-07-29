<?php

namespace Brick\FileStorage;

use Guzzle\Http\Exception\HttpException;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

/**
 * Amazon S3 implementation of the Storage interface.
 *
 * @todo Remove Guzzle exception catching once this has been fixed:
 * @see https://github.com/aws/aws-sdk-php/issues/120
 */
class S3Storage implements Storage
{
    /**
     * @var \Aws\S3\S3Client
     */
    protected $s3;

    /**
     * @var string
     */
    protected $bucket;

    /**
     * Class constructor.
     *
     * @param \Aws\S3\S3Client $s3
     * @param string $bucket
     */
    public function __construct(S3Client $s3, $bucket)
    {
        $this->s3 = $s3;
        $this->bucket = $bucket;
    }

    /**
     * {@inheritdoc}
     */
    public function put($path, $data)
    {
        try {
            $this->s3->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $path,
                'Body' => $data
            ));
        } catch (HttpException $e) {
            throw StorageException::putError($path, $e);
        } catch (S3Exception $e) {
            throw StorageException::putError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($path)
    {
        try {
            // @todo this actually returns a Model, we need to extra the contents from it.
            return $this->s3->getObject(array(
                'Bucket' => $this->bucket,
                'Key' => $path
            ));
        } catch (HttpException $e) {
            throw StorageException::getError($path, $e);
        } catch (S3Exception $e) {
            throw StorageException::getError($path, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path)
    {
        try {
            $this->s3->deleteObject(array(
                'Bucket' => $this->bucket,
                'Key' => $path
            ));
        } catch (HttpException $e) {
            throw StorageException::deleteError($path, $e);
        } catch (S3Exception $e) {
            throw StorageException::deleteError($path, $e);
        }
    }
}
