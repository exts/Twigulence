# About

Twigulence is a bootstrapper for the Opulence PHP 7.0 Framework.

## Installation

1. `composer require exts/twigulence --no-dev`
2. If you're running a fresh [`Opulence/Project`]() then I recommend heading into your `/PROJECT_NAME/config/http` folder and find the `bootstrappers.php` file
3. Remove the `ViewFunctionsBootstrapper::class` and `ViewBootstrapper::class` from this file and replace those with our Bootstrapper class `Twigulence\Bootstrapper::class`
4. To test add a twig file to your `resources/views` folder then in your controller call `$this->viewFactory->createView` with the file name excluding the file extension and you can use the setVars to set custom values to the view object and it'll pass that data to the twig template.

For now I hope this helps you get twig working with Opulence

## TODO

- Working on the ability to make it easy to customize the bootstrapper, for now your best bet is to extend the Bootstrapper class and overwrite the `getTwigInstance` method.