<?php

namespace AppBundle\Controller;

use AppBundle\Repository\TaskRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Task;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Exception\InvalidFormException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class TaskController
 */
class TodoController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets a collection of the given User's Tasks.
     *
     * @ApiDoc(
     *   output = "AppBundle\Entity\Task",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return array|View
     */
    public function cgetAction()
    {
        return $this->getHandler()->all();
    }

    /**
     * Replaces existing Task from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\TaskType",
     *   output = "AppBundle\Entity\Task",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the task id
     *
     * @return Task|View
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function putAction(Request $request, $id)
    {
        /** @var Task $requestedTask */
        $requestedTask = $this->getTaskRepository()->findOneByIdAndUser($id, $this->getUser());

        if (!$requestedTask) {
            throw new NotFoundHttpException('Task with id: ' . $id . ' not found');
        }

        try {
            $task = $this->getHandler()->put(
                $requestedTask,
                $request->request->all()
            );

            return $task;
        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }

    /**
     * Creates a new Task.
     *
     * @ApiDoc(
     *  input = "AppBundle\Form\Type\TaskFormType",
     *  output = "AppBundle\Entity\Task",
     *  statusCodes={
     *         200="Returned when a new Task has been successfully created",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     *
     * @param Request $request
     * @return Task|View
     */
    public function postAction(Request $request)
    {
        try {
            $task = $this->getHandler()->post($request->request->all());

            return $task;
        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }

    /**
     * Deletes a specific Task by ID.
     *
     * @ApiDoc(
     *  description="Deletes an existing Task",
     *  statusCodes={
     *         204="Returned when an existing Task has been successfully deleted",
     *         404="Returned when trying to delete a non existent Task"
     *     }
     * )
     *
     * @param int $id the task id
     * @return View
     */
    public function deleteAction($id)
    {
        /** @var Task $requestedTask */
        $requestedTask = $this->getTaskRepository()->findOneByIdAndUser($id, $this->getUser());

        if (!$requestedTask) {
            throw new NotFoundHttpException('Task with id: ' . $id . ' not found');
        }

        $this->getHandler()->delete($requestedTask);

        return new View(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Sets task as completed.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\AccountType",
     *   output = "AppBundle\Entity\Account",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when errors",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param int $id the account id
     *
     * @return Task|View
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction($id)
    {
        /** @var Task $requestedTask */
        $requestedTask = $this->getTaskRepository()->findOneByIdAndUser($id, $this->getUser());

        if (!$requestedTask) {
            throw new NotFoundHttpException('Task with id: ' . $id . ' not found');
        }

        $task = $this->getHandler()->patch($requestedTask);

        return $task;
    }

    /**
     * @return TaskRepository
     */
    private function getTaskRepository()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository(Task::class);
    }

    /**
     * Returns the required handler for this controller
     *
     * @return \AppBundle\Handler\TaskHandler
     */
    private function getHandler()
    {
        $handler = $this->get('appbundle.handler.task_handler');
        $handler->setUser($this->getUser());

        return $handler;
    }
}
