<?php

namespace Brick\Controller\ParameterConverter;

use Brick\Reflection\ReflectionTools;
use Brick\Http\Exception\HttpNotFoundException;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Converts requests parameters to objects retrieved by identity.
 *
 * To match a single object, the controller must declare a class type hint for the parameter.
 * To match an array of objects, the controller must declare an array type hint, and properly
 * document the parameter with the fully qualified class name starting with a backslash
 * and ending with empty square brackets: \Application\Model\User[] is a valid example.
 */
class DoctrineConverter implements ParameterConverter
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $objectManager;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->reflectionTools = new ReflectionTools();
    }

    /**
     * {@inheritdoc}
     */
    public function convertParameter(\ReflectionParameter $parameter, $value)
    {
        $class = $parameter->getClass();

        if ($class) {
            return $this->findObject($class->getName(), $value);
        }

        if ($parameter->isArray()) {
            $types = $this->reflectionTools->getParameterTypes($parameter);

            // Must be a single type.
            if (count($types) != 1) {
                return $value;
            }

            $type = $types[0];

            // Must start with a slash and end with empty square brackets.
            if ($type[0] != '\\' || substr($type, -2) != '[]') {
                return $value;
            }

            // Remove the leading slash and trailing square brackets.
            $type = substr($type, 1, -2);

            foreach ($value as & $item) {
                $item = $this->findObject($type, $item);
            }
        }

        return $value;
    }

    /**
     * Finds an object matching the given class or identity, or throws an exception.
     *
     * @param string       $class
     * @param string|array $identity
     *
     * @return object
     *
     * @throws \Brick\Http\Exception\HttpNotFoundException
     */
    private function findObject($class, $identity)
    {
        $entity = $this->objectManager->find($class, $identity);

        if ($entity === null) {
            $message = '%s with identity %s was not found';
            $message = sprintf($message, $class, var_export($identity, true));

            throw new HttpNotFoundException($message);
        }

        return $entity;
    }
}
