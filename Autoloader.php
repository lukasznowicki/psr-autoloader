<?php
/**
 * This is the main file of the plugin.
 *
 * @package   Phylax
 * @version   0.1.2
 * @since     0.1.0
 * @author    Łukasz Nowicki <lukasz.nowicki@post.pl>
 * @copyright phylax.pl Łukasz Nowicki
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 or later
 * @link      https://github.com/lukasznowicki/psr-autoloader
 */

namespace Phylax;

/**
 * Class Autoloader
 *
 * @package Phylax
 */
class Autoloader {


	/**
	 * @var array List of registered namespaces
	 */
	protected $namespaces = [];

	/**
	 * This method will register Class Autoload handler. As it is vital for
	 * the project, this method always returns true or exits on failure.
	 *
	 * @return bool Always returns True, exiting on error
	 */
	public function registerHandler(): bool {
		try {
			spl_autoload_register( [ $this, 'classLoader' ] );
		} catch ( \Exception $exception ) {
			exit( 'Example Plugin error, cannot register autoloader this is unrecoverable.' );
		}

		return TRUE;
	}

	/**
	 * Add namespace with associated directory to the loader
	 *
	 * @param string $namespace Namespace entry point
	 * @param string $directory Directory entry point for given namespace
	 * @param bool   $prepend   Prepend this entry (True) or append at the end
	 *                          (False, default)
	 *
	 * @return bool Always returns true
	 */
	public function addNamespace( string $namespace, string $directory, bool $prepend = FALSE ): bool {
		$namespace = $this->prepareNamespace( $namespace );
		$directory = $this->prepareDirectory( $directory );
		if ( FALSE === isset( $this->namespaces[ $namespace ] ) ) {
			$this->namespaces[ $namespace ] = [];
		}
		if ( FALSE === $prepend ) {
			array_push( $this->namespaces[ $namespace ], $directory );
		} else {
			array_unshift( $this->namespaces[ $namespace ], $directory );
		}

		return TRUE;
	}

	/**
	 * Make sure, that given namespace ends up with a slash
	 *
	 * @param string $namespace Desired namespace
	 *
	 * @return string Proper namespace
	 */
	private function prepareNamespace( string $namespace ): string {
		$namespace = trim( $namespace, '\\' ) . '\\';

		return $namespace;
	}

	/**
	 * Make sure that directory uses proper directory separator, as defined by
	 * the PHP on current machine and ends up with one
	 *
	 * @param string $directory Desired directory
	 *
	 * @return string Proper directory
	 */
	private function prepareDirectory( string $directory ): string {
		$directory = str_replace( [
			'\\',
			'/',
		], \DIRECTORY_SEPARATOR, $directory );
		$directory = rtrim( $directory, \DIRECTORY_SEPARATOR ) . \DIRECTORY_SEPARATOR;

		return $directory;
	}

	/**
	 * Traverse the class path against the directory structure, if find
	 * suitable entry, calls to check files
	 *
	 * @see \Phylax\Autoloader::checkMappedFile()
	 *
	 * @param string $class Desired class, this one is given by the PHP
	 *
	 * @return null|string Returns string on success or null on failure
	 */
	public function classLoader( string $class ): ?string {
		$prefix = $class;
		while ( FALSE !== $position = strrpos( $prefix, '\\' ) ) {
			$prefix        = substr( $class, 0, $position + 1 );
			$relativeClass = substr( $class, $position + 1 );
			$mappedFile    = $this->checkMappedFile( $prefix, $relativeClass );
			if ( TRUE === $mappedFile ) {
				return $mappedFile;
			}
			$prefix = rtrim( $prefix, '\\' );
		}

		return NULL;
	}

	/**
	 * When a proper Namespace is found, let's traverse assigned directories
	 * to find a file and try to call it
	 *
	 * @see \Phylax\Autoloader::classLoader()
	 * @see \Phylax\Autoloader::callFile()
	 *
	 * @param string $namespace     Desired namespace
	 * @param string $relativeClass Relative class name
	 *
	 * @return null|string String on success or null on failure
	 */
	protected function checkMappedFile( string $namespace, string $relativeClass ): ?string {
		if ( FALSE === isset( $this->namespaces[ $namespace ] ) ) {
			return NULL;
		}
		foreach ( $this->namespaces[ $namespace ] as $baseDirectory ) {
			$filePath = $baseDirectory . str_replace( '\\', \DIRECTORY_SEPARATOR, $relativeClass ) . '.php';
			if ( TRUE === $this->callFile( $filePath ) ) {
				return $filePath;
			}
		}

		return NULL;
	}

	/**
	 * If given file is readable, then load it once
	 *
	 * @see \Phylax\Autoloader::checkMappedFile()
	 *
	 * @param string $filePath Class file path
	 *
	 * @return bool True on success, false otherwise
	 */
	protected function callFile( string $filePath ): bool {
		if ( TRUE === is_readable( $filePath ) ) {
			require_once $filePath;

			return TRUE;
		}

		return FALSE;
	}

}