<?php

namespace Brick\DependencyInjection;

/**
 * Base class for all bindings.
 */
abstract class Binding
{
    /**
     * @var Scope|null
     */
    private $scope = null;

    /**
     * Sets the scope of this binding.
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
     * Resolves the value of this binding, according to the current scope.
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
     * Resolves the value of this binding.
     *
     * This method is for internal use by the Scopes.
     *
     * @param Container $container
     *
     * @return mixed
     */
    abstract public function resolve(Container $container);

    /**
     * Returns the default scope for this binding if not set.
     *
     * @return Scope
     */
    abstract protected function getDefaultScope();
}
