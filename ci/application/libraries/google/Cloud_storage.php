<?php
use Google\Cloud\Storage\StorageClient;

/**
 * Class Cloud_storage
 */
class Cloud_storage
{
    private $instance;

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
        $storage = new StorageClient();
        $file = fopen($source, 'r');
        $bucket = $storage->bucket($_SERVER["GCP_BUCKET_NAME"]);
        $bucket->upload($file, [
            'name' => $objectName
        ]);
    }
}
