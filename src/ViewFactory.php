<?php
namespace Twigulence;

use InvalidArgumentException;
use Opulence\Views\Factories\IO\IViewNameResolver;
use Opulence\Views\Factories\IViewFactory;
use Opulence\Views\IView;
use Opulence\Views\View;

class ViewFactory implements IViewFactory
{
    /**
     * @var null|IViewNameResolver
     */
    protected $viewNameResolver = null;

    /**
     * @var array
     */
    protected $builders = [];

    /**
     * TwigViewFactory constructor.
     *
     * @param IViewNameResolver $viewNameResolver
     */
    public function __construct(IViewNameResolver $viewNameResolver)
    {
        $this->viewNameResolver = $viewNameResolver;
    }

    /**
     * @param string $name
     *
     * @return IView
     */
    public function createView(string $name) : IView
    {
        $resolvedPath = $this->viewNameResolver->resolve($name);

        return new View($resolvedPath);
    }

    /**
     * @inheritdoc
     */
    public function hasView(string $name) : bool
    {
        try {
            $this->viewNameResolver->resolve($name);

            return true;
        } catch (InvalidArgumentException $ex) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function registerBuilder($names, callable $callback)
    {
        foreach ((array)$names as $name) {
            if (!isset($this->builders[$name])) {
                $this->builders[$name] = [];
            }

            $this->builders[$name][] = $callback;
        }
    }
}