<?php

namespace AppBundle\Handler;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Form\Type\TaskType;
use AppBundle\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;

    /**
     * @var TaskRepository
     */
    private $repository;

    /**
     * @var User
     */
    private $user;

    public function __construct(
        FormHandlerInterface $formHandler,
        TaskRepository $taskRepository
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $taskRepository;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->user->getTasks();
    }

    /**
     * @param array                 $parameters
     * @param array                 $options
     * @return Task
     */
    public function post(array $parameters, array $options = [])
    {

        $task = $this->formHandler->handle(
            new Task(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $this->user->addTask($task);
        $this->repository->save($task);

        return $task;
    }

    /**
     * @param  Task  $task
     *
     * @return mixed
     */
    public function patch($task)
    {
        $task->setCompleted(true);
        $this->user->addTask($task);
        $this->repository->save($task);

        return $task;
    }

    /**
     * @param  Task $task
     * @param  array $parameters
     * @param  array $options
     * @return mixed
     * @throws \Exception
     */
    public function put($task, array $parameters, array $options = [])
    {
        $task = $this->formHandler->handle(
            $task,
            $parameters,
            Request::METHOD_PUT,
            $options
        );

        $this->user->addTask($task);
        $this->repository->save($task);

        return $task;
    }

    /**
     * @param Task $task
     * @return bool
     * @throws \Exception
     */
    public function delete($task)
    {
        $this->repository->delete($task);

        return true;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        /** @var User user */
        $this->user = $user;

        return $this;
    }
}