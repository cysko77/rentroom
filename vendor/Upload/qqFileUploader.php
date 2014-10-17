<?php

namespace Upload;

header('Content-type: text/html; charset=UTF-8');

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr
{

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    public function save($path)
    {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize())
        {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    public function getName()
    {
        return $_GET['qqfile'];
    }

    public function getSize()
    {
        if (isset($_SERVER["CONTENT_LENGTH"]))
        {
            return (int) $_SERVER["CONTENT_LENGTH"];
        }
        else
        {
            throw new Exception('Getting content length is not supported.');
        }
    }

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm
{

    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    public function save($path)
    {
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path))
        {
            return false;
        }
        return true;
    }

    public function getName()
    {
        return $_FILES['qqfile']['name'];
    }

    public function getSize()
    {
        return $_FILES['qqfile']['size'];
    }

}

class qqFileUploader
{

    private $allowedExtensions = array();
    private $sizeLimit = 2485760;
    private $file;
    private $imgfile;
    private $img_alt;
    private $imgWidth;
    private $imgHeight;
    private $ratio;

    public function __construct($allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF'), $sizeLimit = 2485760)
    {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile']))
        {
            $this->file = new qqUploadedFileXhr();
        }
        elseif (isset($_FILES['qqfile']))
        {
            $this->file = new qqUploadedFileForm();
        }
        else
        {
            $this->file = false;
        }
    }

    private function checkServerSettings()
    {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit)
        {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';

            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last)
        {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    public function handleUpload($uploadDirectory, $replaceOldFile = FALSE)
    {
        $this->imgfile = "";
        $this->img_alt = "";
        if (!is_writable($uploadDirectory))
        {
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file)
        {
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0)
        {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit)
        {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());

        $filename = utf8_decode($pathinfo['filename']);
        $alt = $pathinfo['filename'];
        $ext = strtolower($pathinfo['extension']);

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions))
        {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
        }

        if (!$replaceOldFile)
        {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext))
            {
                $filename .= rand(10, 99);
            }
            $alt = $filename;
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext))
        {
            $this->imgfile = $filename . '.' . $ext;
            $this->img_alt = $alt;
            return array('success' => true, 'img' => $this->imgfile . '.' . $ext, 'alt' => $alt);
        }
        else
        {
            return array('error' => 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
    }

    public function rezise($destinationWidth, $destinationHeight, $destinationCube, $imgSourceDir, $imgDestinationDir)
    {
        //On défini l'image source
        $source = $imgSourceDir . $this->imgfile;
        //On défini l'image destination
        $imgDestination = $imgDestinationDir . $this->imgfile;
        //On récupère les informations sur l'image
        $infoImg = getimagesize($source);
        $type = $infoImg[2];
        /* On crée une ressource image adaptée au type de l'image source */
        switch ($type)
        {
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                $source = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($source);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($source);
                break;
            default:
                return array('error' => 'type d\'image non reconnu pour l\'image ' . $source);
                exit();
        }

        $source_Width = $infoImg[0];
        $source_Height = $infoImg[1];
        $ratio = $source_Width / $source_Height;

        /* On définit la taille de la miniature */
        /* miniature paysage */
        if ($ratio > 1)
        {
            $destination = array("width" => $destinationWidth, "height" => $destinationWidth / $ratio);
        }
        /* miniature portrait */
        if ($ratio < 1)
        {
            $destination = array("width" => $destinationHeight, "height" => $destinationHeight / $ratio);
        }
        /* miniature CARRE */
        if ($ratio == 1)
        {
            $destination = array("width" => $destinationCube, "height" => $destinationCube);
        }

        /* On crée la ressource image pour la miniature */
        $destination_img = ImageCreateTrueColor($destination["width"], $destination["height"]);
        /* Si l'image source est potentiellement transparente */
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF)
        {
            /* On active l'alpha */
            imagealphablending($destination_img, false);
            imagesavealpha($destination_img, true);
            /* On remplit la miniature avec un blanc opaque */
            imagefill($destination_img, 0, 0, imagecolorallocatealpha($destination_img, 255, 255, 255, 0));
        }
        /* On ajoute l'image source dans la ressource de la miniature en la retaillant */
        imagecopyresampled($destination_img, $source, 0, 0, 0, 0, $destination["width"], $destination["height"], $source_Width, $source_Height);
        // On enregistre l'image
        $imgPNG = ($type === IMAGETYPE_PNG) ? imagepng($destination_img, $imgDestination) : false;
        $imgGIF = ($type === IMAGETYPE_GIF) ? imagegif($destination_img, $imgDestination) : false;
        $imgJPEG = ($type === IMAGETYPE_JPEG2000 || $type === IMAGETYPE_JPEG) ? imagejpeg($destination_img, $imgDestination) : false;

        if ($imgPNG || $imgGIF || $imgJPEG)
        {
            $this->imgWidth = $destination["width"];
            $this->imgHeight = $destination["height"];
            $this->ratio = $ratio;
            return array('success' => true, 'img' => $this->imgfile, 'alt' => $this->img_alt, 'size_w' => $destination["width"], 'size_h' => $destination["height"]);
        }
        else
        {
            return array("error" => "L'image n'a pas été redimensionnée");
        }
    }

    public function crop($imgSourceDir, $imgDestinationDir, $destinationWidth, $destinationHeight)
    {
        //On défini l'image source
        $source = $imgSourceDir . $this->imgfile;
        $imgDestination = $imgDestinationDir . $this->imgfile;
        $infoImg = getimagesize($source);
        if ($this->ratio > 1)
        {
            $source_x = ( $infoImg[0] - $destinationWidth) / 2;
            $source_y = ( $infoImg[1] - $destinationWidth) / 2;
        }
        elseif ($this->ratio < 1)
        {
            $source_x = ( $infoImg[0] - $destinationWidth) / 2;
            $source_y = 20;
        }
        else
        {
            $source_x = $source_y = 0;
        }

        $type = $infoImg[2];
        /* On crée une ressource image adaptée au type de l'image source */
        switch ($type)
        {
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                $source = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($source);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($source);
                break;
            default:
                trigger_error('type d\'image non reconnu pour l\'image ' . $source);
                exit();
        }

        /* On crée la ressource image pour la miniature */
        $destination = ImageCreateTrueColor($destinationWidth, $destinationHeight);
        /* Si l'image source est potentiellement transparente */
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF)
        {
            /* On active l'alpha */
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            /* On remplit la miniature avec un blanc opaque */
            imagefill($destination, 0, 0, imagecolorallocatealpha($destination, 255, 255, 255, 0));
        }
        /* On ajoute l'image source dans la ressource de la miniature en la retaillant */
        imagecopy($destination, $source, 0, 0, $source_x, $source_y, $destinationWidth, $destinationHeight);

        /* On enregistre l'image au format adequat */
        $imgPNG = ($type === IMAGETYPE_PNG) ? imagepng($destination, $imgDestination) : false;
        $imgGIF = ($type === IMAGETYPE_GIF) ? imagegif($destination, $imgDestination) : false;
        $imgJPEG = ($type === IMAGETYPE_JPEG || $type === IMAGETYPE_JPEG2000) ? imagejpeg($destination, $imgDestination) : false;

        if ($imgPNG || $imgGIF || $imgJPEG)
        {
            imagedestroy($destination);
            return array('success' => true, 'img' => $this->imgfile, 'alt' => $this->img_alt, 'size_w' => $destinationWidth, 'size_h' => $destinationHeight);
        }
        else
        {
            return array("error" => "L'image n'a pas été redimensionnée");
        }
    }

}

?>