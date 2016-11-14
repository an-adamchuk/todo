<?php
namespace AppBundle\Util\Inflector;

use FOS\RestBundle\Util\Inflector\InflectorInterface;

/**
 * Class NoopInflector
 * @package AppBundle\Util\Inflector
 */
class NoopInflector implements InflectorInterface
{
    public function pluralize($word)
    {
        // Don't pluralize
        return $word;
    }
}