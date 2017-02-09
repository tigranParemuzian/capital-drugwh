<?php

namespace AppBundle\Traits;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * This traits is used for include file
 *
 * Class File
 * @package AppBundle\Traits
 */
trait File
{

    /**
     * @Assert\File()
     */
    protected  $file;

    /**
     * @ORM\Column(name="file_original_name", type="string", length=255, nullable=true)
     */
    protected $fileOriginalName;

    /**
     * @ORM\Column(name="file_name", type="string", length=255, nullable=true)
     */
    protected $fileName;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set FileOriginalName
     *
     * @param string $fileOriginalName
     * @return $this
     */
    public function setFileOriginalName($fileOriginalName)
    {
        $this->fileOriginalName = $fileOriginalName;

        return $this;
    }

    /**
     * @var null
     */
    public $imageFromCache = null;

    /**
     * Get fileOriginalName
     *
     * @return string
     */
    public function getFileOriginalName()
    {
        return $this->fileOriginalName;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * This function is used to return file web path
     *
     * @VirtualProperty()
     * @Groups({"user-info", "cars_list", "finance"})
     *
     * @return string
     */
    public function getDownloadLink()
    {
        return $this->fileName ? '/' . $this->getUploadDir() . '/' . $this->getPath() . '/' . $this->fileName : '' ;
    }

    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir() . '/' . $this->getPath() .'/';
    }

    /**
     * This function is used to return file web path
     *
     * @return string
     */
    public function getUploadRootDir()
    {
        return __DIR__. '/../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return 'credit_application_uploads';
    }

    /**
     * Upload folder name
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads';
    }

    /**
     * This function is used to move(physically download) file
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function uploadFile()
    {
        // the file property can be empty if the field is not required
        if (null == $this->getFile())
        {
            return;
        }
        // check file name
        if($this->getFileName()){
            // get file path
            $path = $this->getAbsolutePath() . $this->getFileName();
            // check file
            if(file_exists($path) && is_file($path)){
                // remove file
                unlink($path);
            }
        }

        // get file originalName
        $this->setFileOriginalName($this->getFile()->getClientOriginalName());

        // get file
        $path_parts = pathinfo($this->getFile()->getClientOriginalName());

        // generate filename
        $this->setFileName(md5(microtime()) . '.' . $path_parts['extension']);

        // upload file
        $this->getFile()->move($this->getAbsolutePath(), $this->getFileName());

        // set file to null
        $this->setFile(null);
    }

    /**
     * This function is used to remove file
     *
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        // get origin file path
        $filePath = $this->getAbsolutePath() . $this->getFileName();

        // check file and remove
        if (file_exists($filePath) && is_file($filePath)){
            unlink($filePath);
        }
    }


    /**
     * This function is used to get object class name
     *
     * @return string
     */
    public function getClassName(){
        return get_class($this);
    }

}