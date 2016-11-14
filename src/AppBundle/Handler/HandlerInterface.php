<?php

namespace AppBundle\Handler;

/**
 * Interface HandlerInterface
 * @package AppBundle\Handler
 */
interface HandlerInterface
{
    /**
     * @return mixed
     */
    public function all();
    /**
     * @param array           $parameters
     * @param array           $options
     * @return mixed
     */
    public function post(array $parameters, array $options);
    /**
     * @param mixed           $resource
     * @param array           $parameters
     * @param array           $options
     * @return mixed
     */
    public function put($resource, array $parameters, array $options);
    /**
     * @param mixed           $resource
     * @return mixed
     */
    public function patch($resource);
    /**
     * @param mixed           $resource
     * @return mixed
     */
    public function delete($resource);
}