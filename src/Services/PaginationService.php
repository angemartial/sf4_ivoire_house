<?php

namespace App\Services;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Config\Definition\Exception\Exception;

class PaginationService {

    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;


    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath){
       
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath(){
        return $templatePath;
    }

    public function setRoute($route){
        $this->route = $route;

        return $this;
    }

    public function getRoute(){
        return $route;
    }


    public function display(){
        $this->twig->display('admin/partials/pagination.html.twig', [
            'page' => $this->currentPage,
            'pages'=> $this->getPages(),
            'route'=> $this->route
        ]);
    }


    public function getPages(){

        if(empty($this->entityClass)){
            throw new \Exception("vous n'avez pas spécifié l'entité sur laquelle nous devons paginer,
             utilisez la méthode setEntityClass() de votre objet de PaginationService !");
        }

        // 1) connaitre le total des enregistrements
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // faire la division l'arrondi et l'envoyer
        $pages = ceil($total / $this->limit);

        return $pages;
    }


    public function getData(){

        if(empty($this->entityClass)){
            throw new \Exception("vous n'avez pas spécifié l'entité sur laquelle nous devons paginer,
             utilisez la méthode setEntityClass() de votre objet de PaginationService !");
        }

        // 1) calculer le offset cad le start d'une paginatio
        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2) demander au repository de trouver les elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        // 3) renvoyer les elements en question
        return $data;
    }


    public function setPage($page){
        $this->currentPage = $page;

        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }

    public function setLimit($limit){
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;

        return $this;
        
    }

    public function getEntityClass(){
        return $this->entityClass;
    }



}