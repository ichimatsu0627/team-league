<?php
use Google\Cloud\Storage\StorageClient;

/**
 * Class Cloud_storage
 *
 * @property StorageClient $instance
 */
class Cloud_storage
{
    private $instance;

    /**
     * Cloud_storage constructor.
     */
    public function __construct()
    {
        $this->instance = new StorageClient();
    }

    /**
     * Upload a file.
     *
     * @param string $objectName the name of the object.
     * @param string $source the path to the file to upload.
     *
     * @return Psr\Http\Message\StreamInterface
     */
    public function upload_object($objectName, $source)
    {
        $file = fopen($source, 'r');
        $bucket = $this->instance->bucket($_SERVER["GCP_BUCKET_NAME"]);
        $bucket->upload($file, [
            'name' => $objectName
        ]);
    }
}
