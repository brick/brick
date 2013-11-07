<?php

namespace Brick\Controller\ParameterConverter;

use Brick\Reflection\ReflectionTools;
use Brick\Http\Exception\HttpNotFoundException;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\LockMode;

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
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->reflectionTools = new ReflectionTools();
    }

    /**
     * {@inheritdoc}
     */
    public function convertParameter(\ReflectionParameter $parameter, $value, array $options = [])
    {
        $class = $parameter->getClass();
        $lockMode = $this->getLockMode($options);

        if ($class) {
            return $this->findObject($class->getName(), $value, $lockMode);
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
                $item = $this->findObject($type, $item, $lockMode);
            }
        }

        return $value;
    }

    /**
     * Finds an object matching the given class or identity, or throws an exception.
     *
     * @param string       $class
     * @param string|array $identity
     * @param integer      $lockMode
     *
     * @return object
     *
     * @throws \Brick\Http\Exception\HttpNotFoundException
     */
    private function findObject($class, $identity, $lockMode)
    {
        $entity = $this->entityManager->find($class, $identity, $lockMode);

        if ($entity === null) {
            $message = '%s with identity %s was not found';
            $message = sprintf($message, $class, var_export($identity, true));

            throw new HttpNotFoundException($message);
        }

        return $entity;
    }

    /**
     * @param array $options
     *
     * @return integer
     *
     * @throws \RuntimeException
     */
    private function getLockMode(array $options)
    {
        if (! isset($options['lock'])) {
            return LockMode::NONE;
        }

        switch ($options['lock']) {
            case 'READ':
                return LockMode::PESSIMISTIC_READ;

            case 'WRITE':
                return LockMode::PESSIMISTIC_WRITE;
        }

        throw new \RuntimeException('Invalid lock mode: ' . $options['lock']);
    }
}
