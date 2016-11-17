<?php
namespace Twigulence;

use Opulence\Framework\Configuration\Config;
use Opulence\Views\Compilers\ICompiler;
use Opulence\Views\IView;
use Twig_Environment;

class Compiler implements ICompiler
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var null
     */
    private $viewPath;

    /**
     * TwigCompiler constructor.
     *
     * @param \Twig_Environment $twig
     * @param null $viewPath
     */
    public function __construct(Twig_Environment $twig, $viewPath = null)
    {
        $this->twig = $twig;
        $this->viewPath = $viewPath;
    }

    /**
     * @param IView $view
     *
     * @return string
     */
    public function compile(IView $view) : string
    {
        $viewBasePath = trim($this->viewPath ?? Config::get("paths", "views.raw"), '/ ');

        //remove base path from view
        $viewTemplatePath = ltrim($view->getPath(), '/ ');
        $viewTemplatePath = trim(str_replace($viewBasePath, '', $viewTemplatePath), '/ ');

        return trim($this->twig->render($viewTemplatePath, $view->getVars()));
    }
}