<?php
use Mockery as m;
use Opulence\Views\IView;
use Twigulence\Compiler;

/**
 * Class CompilerTest
 */
class CompilerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $fixture;

    public function setUp()
    {
        $this->fixture = __DIR__ . '/CompilerTestFixtures';
        $loader = new Twig_Loader_Filesystem($this->fixture);
        $this->twig = new Twig_Environment($loader, ['strict_variables' => false]);
    }

    public function testTheCompilersOutputValueWhichShouldMatch()
    {
        $view = m::mock(IView::class);
        $view->shouldReceive('getPath')
            ->once()
            ->andReturn($this->fixture . '/example.twig');

        $view->shouldReceive('getVars')
            ->once()
            ->andReturn(['name' => 'Unit Test']);

        $compiler = new Compiler($this->twig, $this->fixture);

        $this->assertTrue("Hello Unit Test" == $compiler->compile($view));
    }
}