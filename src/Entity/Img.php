<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImgRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Img
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * fileName with extension - Complete URL
     */
    private $fileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * alt for img description and referencement
     */
    private $alt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="imgs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

        /**
     * Object File with setter and getter
     */
    private $file; 

    private $tempFileName; 

    private $ext;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Img
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
      
        if ($this->ext === null)
        {  
            $this->tempFileName=$this->fileName;
            $this->ext = null;
            $this->alt = null;
        }
       
        return $this;
    }

   /**
    * warning 
     * I delete type (string) for update
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * warning
     * I delete type(string) for update
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }


    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;
        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

/*********** EVENTS *************/
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload()
    {
        //echo '<pre>';
        //var_dump($this->file);
        //die();
        if ($this->file === null){
            return;
        }

        //$this->alt = md5(uniqid());
        $this->ext = $this->file->guessExtension();
        $this->fileName=md5(uniqid()).'.'.$this->ext;
        
    }

     /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if(null === $this->file){
            return;
        }
        //if isset $this->file

        // Si on avait un ancien fichier, on le supprime
        if (isset($this->tempFileName)) {
            $oldFile = $this->getUploadRootDir().'/'.$this->tempFileName;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadRootDir(),$this->fileName);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFileName = $this->getUploadRootDir().$this->fileName;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFileName)) {
            // On supprime le fichier
            unlink($this->tempFileName);
        }
    }

    /**
     * @return string
     * Le virer.
     */
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../public/uploads/';
    }

   
}
