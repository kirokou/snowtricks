<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     */
    private $alt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ext;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="imgs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    private $file; //FileType

    private $tempFileName; //  urlcomplet/img.ext

    public function getId(): ?int
    {
        return $this->id;
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

    public function getExt(): ?string
    {
        return $this->ext;
    }

    public function setExt(string $ext): self
    {
        $this->ext = $ext;
        return $this;
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
            $this->ext = null;
            $this->alt = null;
        }
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

        $this->alt = md5(uniqid());
        $this->ext = $this->file->guessExtension();
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

        // Si on avait un ancien fichier, on le supprime
        if (isset($this->tempFileName)) {
           // dump($this->tempFileName);
            $oldFile = $this->getUploadRootDir().'/'.$this->tempFileName;
          //  dump($oldFile, file_exists($oldFile));
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(), // Le répertoire de destination
            $this->alt.'.'.$this->ext  // Le nom du fichier à créer, ici « id.ext »
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        //$this->tempFileName = $this->getUploadRootDir().'/'.$this->completeUrl;
        $this->tempFileName = $this->getUploadRootDir().'/'.$this->alt.'.'.$this->ext;
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
     */
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'img/';
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../public/'.$this->getUploadDir();
    }

   
}
