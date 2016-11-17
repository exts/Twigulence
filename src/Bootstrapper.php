<?php
namespace Twigulence;

use Opulence\Framework\Configuration\Config;
use Opulence\Ioc\Bootstrappers\Bootstrapper as BaseBootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Views\Compilers\Compiler as BaseCompiler;
use Opulence\Views\Compilers\CompilerRegistry;
use Opulence\Views\Compilers\ICompiler;
use Opulence\Views\Factories\IO\FileViewNameResolver;
use Opulence\Views\Factories\IO\IViewNameResolver;
use Opulence\Views\Factories\IViewFactory;
use Twig_Environment;

class Bootstrapper extends BaseBootstrapper implements ILazyBootstrapper
{
    /**
     * @var IViewFactory
     */
    private $viewFactory;

    /**
     * @return array
     */
    public function getBindings() : array
    {
        return [
            ICompiler::class,
            IViewFactory::class,
        ];
    }

    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container)
    {
        $this->viewFactory = $this->getViewFactory($container);
        $compiler = $this->getViewCompiler($container);

        //bindings
        $container->bindInstance(ICompiler::class, $compiler);
        $container->bindInstance(IViewFactory::class, $this->viewFactory);
    }

    /**
     * @param IContainer $container
     *
     * @return ICompiler
     */
    public function getViewCompiler(IContainer $container) : ICompiler
    {
        $registry = new CompilerRegistry();
        $viewCompiler = new BaseCompiler($registry);

        $twig = $this->getTwigInstance();
        $container->bindInstance(Twig_Environment::class, $twig);

        $twigCompiler = new Compiler($twig);
        $registry->registerCompiler("twig", $twigCompiler);

        return $viewCompiler;
    }

    /**
     * @param IContainer $container
     *
     * @return IViewFactory
     */
    protected function getViewFactory(IContainer $container) : IViewFactory
    {
        //twig file resolver
        $resolver = new FileViewNameResolver();
        $resolver->registerPath(Config::get("paths", "views.raw"));
        $resolver->registerExtension("twig");

        //bindings
        $container->bindInstance(IViewNameResolver::class, $resolver);

        return new ViewFactory($resolver);
    }

    /**
     * @return Twig_Environment
     */
    protected function getTwigInstance() : Twig_Environment
    {
        $viewFolder = Config::get("paths", "views.raw");
        $viewCacheFolder = Config::get("twig", "cache.path");

        //twig loader
        $twigLoader = new \Twig_Loader_Filesystem($viewFolder);

        //twig instance
        $twigOptions = array_merge([
            'cache' => $viewCacheFolder,
        ], Config::get("twig", "env.options", []));

        $twig = new Twig_Environment($twigLoader, $twigOptions);

        return $twig;
    }

}