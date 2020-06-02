<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class Paginator
{
    /**
    * @var string
    */
    private $entityClass;

   /**
    * @var integer
    */
    private $limit = 5;

   /**
    * @var integer
    */
    private $currentPage = 1;

   /**
    * @var EntityManagerInterface
    */
    private $manager;

   /**
    * @param EntityManagerInterface $manager
    */
    public function __construct(EntityManagerInterface $manager) {
       $this->manager = $manager;
    }

   /**
    * @throws Exception si la propriété $entityClass n'est pas configurée
    * @return int
    */
    public function getPages($arraySeach=[]): int {
       if(empty($this->entityClass)) {
           throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !");
       }

       // 1) Connaitre le total des enregistrements de la table
       $total = count($this->manager->getRepository($this->entityClass)->findBy($arraySeach,[]));

       // 2) Faire la division, l'arrondi et le renvoyer
       return ceil($total / $this->limit);
    }

   /**
    * @throws Exception si la propriété $entityClass n'est pas définie
    * @return array
    */
   public function getData($arraySeach=[]) {
       if(empty($this->entityClass)) {
           throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !");
       }
       // 1) Calculer l'offset
       $offset = $this->currentPage * $this->limit - $this->limit;

       // 2) Demander au repository de trouver les éléments à partir d'un offset et 
       return $this->manager->getRepository($this->entityClass)->findBy($arraySeach, ["id"=>"DESC"], $this->limit, $offset);
   }

   /**
    * @param int $page
    * @return self
    */
   public function setPage(int $page): self {
       $this->currentPage = $page;

       return $this;
   }

   /**
    * @return int
    */
   public function getPage(): int {
       return $this->currentPage;
   }

   /**
    * @param int $limit
    * @return self
    */
   public function setLimit(int $limit): self {
       $this->limit = $limit;

       return $this;
   }

   /**
    * @return int
    */
   public function getLimit(): int {
       return $this->limit;
   }

   /**
    * @param string $entityClass
    * @return self
    */
   public function setEntityClass(string $entityClass): self {
       $this->entityClass = $entityClass;

       return $this;
   }

   /**
    * @return string
    */
   public function getEntityClass(): string {
       return $this->entityClass;
   }

   
}