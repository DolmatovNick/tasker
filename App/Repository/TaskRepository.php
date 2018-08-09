<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use App\Models\Entity\Task;
use Zend\Diactoros\UploadedFile;

class TaskRepository {

    /**
     * @return EntityManager
     */
    private function getEntityManager(): EntityManager
    {
        return \Core\Environment::get('em');
    }

    public function findTasks($page, $itemsOnPage, array $sort)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select('COUNT(t.id)')->from(Task::class, 't');
        $count = $query->getQuery()->getSingleScalarResult();

        $query->select('t');
        $query->setMaxResults($itemsOnPage);
        $query->setFirstResult(($page - 1) * $itemsOnPage);

        $this->addSort($query, $sort, 'executed');
        $this->addSort($query, $sort, 'email');
        $this->addSort($query, $sort, 'userName');

        $tasks = $query->getQuery()->getResult();

        return [$count, $tasks];
    }

    /**
     * Add sorting
     * @param QueryBuilder $query
     * @param array $sort
     * @param $field
     */
    private function addSort(QueryBuilder $query, array &$sort, $field)
    {
        if ($sort[$field]) {
            $query->addOrderBy('t.'.$field, $sort[$field]);
        }
    }

    /**
     * Get Task by id
     * @param $id
     * @return null|object
     */
    public function find($id)
    {
        $em = $this->getEntityManager();
        return $em->find(Task::class, $id);
    }

    /**
     * Store Task
     * @param $data
     * @param UploadedFile $uploadedFile
     */
    public function storeTask($data, UploadedFile $uploadedFile)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getEntityManager();

        $task = new Task();
        $task->setId( (int)$data['id'] );
        $task->setText( $data['text'] );
        $task->setEmail( $data['email'] );
        $task->setUserName( $data['userName'] );
        $task->setExecuted( $data['executed'] == 0 ? false : true);
        $task->setImage( $data['image'] );

        if ( $task->getImage() ) {
            $getNewFileName = $task->getImage();
        } else {
            $getNewFileName = $this->getNewFileName();
        }

        if ( $uploadedFile->getSize() != 0 ) {
            $uploadedFile->moveTo( $getNewFileName );
            $task->setImage( $getNewFileName );
        }

        if ( $task->getId() ) {
            $em->merge($task);
        } else {
            $em->persist($task);
        }
        $em->flush();
    }


    /**
     * Get unique file name for image
     * @return string
     */
    private function getNewFileName() : string
    {
        return 'public/images/'.bin2hex(openssl_random_pseudo_bytes(16)).'.png';
    }

}