# PSR Autoloader
PSR Autoloader to use with my projects

Use:

require 'Phylax\Autoloader';
$my_autoloader = new \Phylax\Autoloader();
$my_atuoloader->registerHandler();
$my_autoloader->addNamespace( 'My\Class\Namespace', __DIR__ );
