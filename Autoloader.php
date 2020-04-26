<?php
/**
 * Now it uses PHP 7.4 standards
 *
 * @version   0.2.1
 * @since     0.1.0
 * @author    Łukasz Nowicki <kontakt@phylax.pl>
 * @copyright Łukasz Nowicki
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 or later
 * @link      https://github.com/lukasznowicki/psr-autoloader
 */

namespace Phylax;

use Exception;
use const DIRECTORY_SEPARATOR;

/**
 * Class Autoloader
 *
 * Use this class to handle auto loading classes, traits, implementations and abstracts.
 * You may declare namespace and proper directory in the constructor (usually use this
 * feature when you got only one) or by using register_handler and addNamespace methods.
 */
class Autoloader {

	/**
	 * @var array
	 */
	protected array $namespaces = [];

	/**
	 * Autoloader constructor. You may assign namespace and directory in the constructor. If you do, the register
	 * handler method will be invoked automatically
	 *
	 * @param string|null $namespace
	 * @param string|null $directory
	 * @param bool $exitOnFail
	 */
	public function __construct( ?string $namespace = null, ?string $directory = null, bool $exitOnFail = true ) {
		if ( ! is_null( $namespace ) && ! is_null( $directory ) ) {
			$this->registerHandler( $exitOnFail );
			$this->addNamespace( $namespace, $directory );
		}
	}

	/**
	 * @param bool $exitOnFail
	 *
	 * @return bool
	 */
	public function registerHandler( bool $exitOnFail = true ): bool {
		try {
			spl_autoload_register( [
				$this,
				'classLoader',
			] );
		} catch ( Exception $exception ) {
			if ( $exitOnFail ) {
				exit;
			}

			return false;
		}

		return true;
	}

	/**
	 * @param string $namespace
	 * @param string $directory
	 * @param bool $prepend
	 *
	 * @return bool
	 */
	public function addNamespace( string $namespace, string $directory, bool $prepend = false ): bool {
		$namespace = $this->prepareNamespace( $namespace );
		$directory = $this->prepareDirectory( $directory );
		if ( ! isset( $this->namespaces[ $namespace ] ) ) {
			$this->namespaces[ $namespace ] = [];
		}
		if ( $prepend ) {
			array_unshift( $this->namespaces[ $namespace ], $directory );
		} else {
			array_push( $this->namespaces[ $namespace ], $directory );
		}

		return true;
	}

	/**
	 * @param string $namespace
	 *
	 * @return string
	 */
	protected function prepareNamespace( string $namespace ): string {
		return $this->prepareString( $namespace, '\\' );
	}

	/**
	 * @param string $string
	 * @param string $inUse
	 *
	 * @return string
	 */
	protected function prepareString( string $string, string $inUse ): string {
		$string = str_replace( [
			'\\',
			'/',
		], $inUse, $string );
		$string = rtrim( $string, $inUse ) . $inUse;

		return $string;
	}

	/**
	 * @param string $directory
	 *
	 * @return string
	 */
	protected function prepareDirectory( string $directory ): string {
		return $this->prepareString( $directory, DIRECTORY_SEPARATOR );
	}

	/**
	 * @param string $class
	 *
	 * @return string|null
	 */
	public function classLoader( string $class ): ?string {
		$prefix = $class;
		while ( false !== $position = strrpos( $prefix, '\\' ) ) {
			$prefix  = substr( $class, 0, $position + 1 );
			$relCls  = substr( $class, $position + 1 );
			$mapFile = $this->checkMappedFile( $prefix, $relCls );
			if ( $mapFile ) {
				return $mapFile;
			}
			$prefix = rtrim( $prefix, '\\' );
		}

		return null;
	}

	/**
	 * @param string $namespace
	 * @param string $relClass
	 *
	 * @return string|null
	 */
	protected function checkMappedFile( string $namespace, string $relClass ): ?string {
		if ( false === isset( $this->namespaces[ $namespace ] ) ) {
			return null;
		}
		foreach ( $this->namespaces[ $namespace ] as $baseDir ) {
			$filePath = $baseDir . str_replace( '\\', DIRECTORY_SEPARATOR, $relClass ) . '.php';
			if ( $this->callFile( $filePath ) ) {
				return $filePath;
			}
		}

		return null;
	}

	/**
	 * @param string $filePath
	 *
	 * @return bool
	 * @noinspection PhpIncludeInspection
	 */
	protected function callFile( string $filePath ): bool {
		if ( is_readable( $filePath ) && ! is_dir( $filePath ) ) {
			require_once $filePath;

			return true;
		}

		return false;
	}

}