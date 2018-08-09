<?php

namespace App\Controllers;

use App\Repository\TaskRepository;
use Core\View;
use Doctrine\ORM\EntityManager;
use App\Views\Pagination;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

class TasksController {

    /**
     * @return EntityManager
     */
    private function getEntityManager(): EntityManager
    {
        return \Core\Environment::get('em');
    }

    /**
     * Task list
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $page = $request->getQueryParams()['page'] ?? 1;
        $page = $page < 1 ? 1 : $page;

        $itemsOnPage = 3;

        $sort = $request->getQueryParams();

        $repo = new TaskRepository();
        list($totalTasksCount, $tasks) = $repo->findTasks($page, $itemsOnPage, $sort);

        $response->getBody()->write(
            View::renderTemplate('tasks/list.twig',
                [
                    'tasks' => $tasks,
                    'sorts' => $sort,
                    'pagination' => (new Pagination('tasks?'.$this->getSortQuery( $sort ), $totalTasksCount, $itemsOnPage))->getHtml($page, $windowSize = 3)
                ]
            )
        );
        return $response;
    }

    /**
     * Get GET query string
     * @param array $sort
     */
    private function getSortQuery(array $sort)
    {
        $str = '';
        if ( isset($sort['executed']) ) {
            $str .= '&executed='.$sort['executed'];
        }
        if ( isset($sort['email']) ) {
            $str .= '&email='.$sort['email'];
        }
        if ( isset($sort['userName']) ) {
            $str .= '&userName='.$sort['userName'];
        }
        if ( isset($sort['page']) ) {
            $str .= '&page='.$sort['page'];
        }
        return $str;
    }

    /**
     * Show form for create Task
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $response->getBody()->write(
            View::renderTemplate('tasks/create.twig', $args)
        );
        return $response;
    }

    /**
     * Save Task
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function store(ServerRequestInterface $request, ResponseInterface $response)
    {
        $body = $request->getParsedBody();

        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = reset($request->getUploadedFiles());

        $repo = new TaskRepository();
        $repo->storeTask($body, $uploadedFile);

        return $this->index($request, $response);
    }

    /**
     * Show form for edit Task
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $repo = new TaskRepository();
        $task = $repo->find((int)$args['id']);

        $response->getBody()->write(
            View::renderTemplate('tasks/edit.twig', ['task' => $task])
        );
        return $response;
    }

}