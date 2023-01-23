<?php

declare(strict_types=1);

namespace KawaiiGherkin;

final class DIC
{
    /**
     * @var array<object>
     */
    public array $resolvedServices = [];

    /**
     * @var array<callable(DIC): object>
     */
    private array $serviceResolvers = [];

    /**
     * @var array<string, callable(DIC): mixed>
     */
    private array $parameterResolvers = [];

    /**
     * @var array<string, mixed>
     */
    private array $resolvedParameters = [];

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    public function getService(string $className): object
    {
        if (\array_key_exists($className, $this->resolvedServices)) {
            /**
             * @var T
             */
            return $this->resolvedServices[$className];
        }

        if (false === \array_key_exists($className, $this->serviceResolvers)) {
            throw new \Exception("Service \"{$className}\" is not a registered.");
        }

        /**
         * @var T
         */
        $service = $this->serviceResolvers[$className]($this);

        return $this->resolvedServices[$className] = $service;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     * @param callable(DIC $dic): T $callable
     */
    public function setService(string $className, callable $callable): void
    {
        $this->serviceResolvers[$className] = $callable;
    }

    public function setParameter(string $name, callable $resolver): void
    {
        $this->parameterResolvers[$name] = $resolver;
    }

    /**
     * @return mixed
     */
    public function getParameter(string $name)
    {
        if (\array_key_exists($name, $this->resolvedParameters)) {
            return $this->resolvedParameters[$name];
        }

        if (false === \array_key_exists($name, $this->parameterResolvers)) {
            throw new \Exception("Parameter \"{$name}\" is not a registered.");
        }

        return $this->resolvedParameters[$name] = $this->parameterResolvers[$name]($this);
    }
}
