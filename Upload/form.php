<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Library</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data" target="_self">
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
        <label for="uploadedImages">Choose images to upload: </label>
        <input type="file" name="uploadedImages[]" id="uploadedImages" multiple="multiple" />
        <button >Upload</button>
    </form>
    
    <?php require_once("upload.php"); echo $htmlImageList; ?>

</body>
</html>