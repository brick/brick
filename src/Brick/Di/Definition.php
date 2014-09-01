<?php

namespace Brick\Di;

/**
 * Base class for definitions.
 */
abstract class Definition
{
    /**
     * @var Scope|null
     */
    private $scope = null;

    /**
     * Changes the scope of this definition.
     *
     * @param Scope $scope
     *
     * @return static
     */
    public function in(Scope $scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Resolves the value of this definition, according to the current scope.
     *
     * This method is for internal use by the Container.
     *
     * @param Container $container
     *
     * @return mixed
     */
    public function get(Container $container)
    {
        if ($this->scope === null) {
            $this->scope = $this->getDefaultScope();
        }

        return $this->scope->get($this, $container);
    }

    /**
     * Resolves the value of this definition, regardless of the Scope.
     *
     * This method is for internal use by the Scopes.
     *
     * @param Container $container
     *
     * @return mixed
     */
    abstract public function resolve(Container $container);

    /**
     * Returns the default Scope for this definition when not set explicitly.
     *
     * @return Scope
     */
    abstract protected function getDefaultScope();
}
