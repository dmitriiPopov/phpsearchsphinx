<?php
namespace app\components;

/**
 * Class UploadedFile
 * @package app\components
 */
class UploadedFile extends \yii\web\UploadedFile
{
    /**
     * Saves the uploaded file.
     * Note that this method uses php's move_uploaded_file() method. If the target file `$file`
     * already exists, it will be overwritten.
     * @param string $file the file path used to save the uploaded file
     * @param bool $deleteTempFile whether to delete the temporary file after saving.
     * If true, you will not be able to save the uploaded file again in the current request.
     * @return bool true whether the file is saved successfully
     * @see error
     */
    public function saveAs($file, $deleteTempFile = true)
    {
        $file = $this->preparePath($file);

        $result = parent::saveAs($file, $deleteTempFile);
        if ($result) {
            chmod($file, 0774);
        }

        return $result;
    }

    /**
     * Check path and prepare directories tree for saving
     * @param string $filepath
     * @return string
     */
    protected function preparePath($filepath)
    {
        $parts = array_filter(explode('/', $filepath));
        array_pop($parts);

        $path = '';
        while (current($parts)) {
            $path .= DIRECTORY_SEPARATOR . current($parts);
            if (!file_exists($path)) {
                @mkdir($path);
                @chmod($path, 0777);
            }
            next($parts);
            continue;
        }

        return $filepath;
    }
}