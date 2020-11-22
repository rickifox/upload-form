<?php

class UploadException extends Exception
{
    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, $code);
    }

    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded image exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded image exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded image was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No image was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }
}

if(isset($_FILES['uploadedImages']))
{
    $folder = 'uploads/';
    $images = $_FILES['uploadedImages'];
    $success = [];
    $failure = [];
    $extensionsAllowed = ['png', 'gif', 'jpg'];
    if(count($images['name']) > 0){
        for($i=0; $i<count($images['name']); $i++) {
            $tmpFilePath = $images['tmp_name'][$i];
            if($tmpFilePath != "") {
                $shortname = $images['name'][$i];
                $extension = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
                if(!in_array($extension, $extensionsAllowed)) {
                    $failure[$shortname] = "$shortname should be of type png, gif or jpg.";
                } else {
                    if ($images['error'][$i] === UPLOAD_ERR_OK) {
                        if($images['size'][$i] > 1000000) {
                            $failure[$shortname] = "$shortname is too large ( Maximum: 1MB)";
                        }
                        $imageNewName = uniqid() . "." . $extension;
                        $imageDestinationAndNewName = $folder . $imageNewName;
                        if(move_uploaded_file($tmpFilePath, $imageDestinationAndNewName)) {
                            $success[$i] = "$imageNewName uploaded successfully";
                        } else {
                            $failure[$shortname] = "$shortname failed to upload";
                        }
                    } else {
                        /*$failure[$images['error'][$i]] = $shortname . " : " . */
                        throw new UploadException($images['error']);
                    }
                }
            }
        }
        if(!empty($failure)) {
            var_dump($success); 
            var_dump($failure);
        }
    }
}

$htmlImageList = '';
$iterator = new FilesystemIterator("./uploads/");
foreach ($iterator as $iteration) {
$htmlImageList = $htmlImageList . "<figure><br />
    <img src=\"uploads/{$iteration->getFilename()}\" alt=\"uploaded image\"><br />
    <figcaption>{$iteration->getFilename()}</figcaption><br />
</figure><br />";
}
