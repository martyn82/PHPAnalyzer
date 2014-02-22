<?php
namespace Mend\Source\Code\Model;

use Mend\Parser\Node\Node;
use Mend\Parser\Node\PHPNode;
use Mend\Source\Code\Model\ClassModel;
use Mend\Source\Code\Location\SourceUrl;

class Package extends Model {
	/**
	 * @var string
	 */
	const DEFAULT_PACKAGE_NAME = 'Default';

	/**
	 * @var ClassModelArray
	 */
	private $classes;

	/**
	 * @var boolean
	 */
	private $isDefault;

	/**
	 * Creates default package.
	 *
	 * @return Package
	 */
	public static function createDefault() {
		return new self();
	}

	/**
	 * Constructs a new Package model.
	 *
	 * @param Node $node
	 * @param SourceUrl $url
	 */
	public function __construct( Node $node = null, SourceUrl $url = null ) {
		if ( is_null( $node ) ) {
			$this->isDefault = true;
			$this->init();
			return;
		}

		parent::__construct( $node, $url );
	}

	/**
	 * @see Model::init()
	 */
	protected function init() {
		$this->classes = new ClassModelArray();
	}

	/**
	 * Determines whether this package is the default package.
	 *
	 * @return boolean
	 */
	public function isDefault() {
		return $this->isDefault;
	}

	/**
	 * @see Model::getName()
	 */
	public function getName() {
		if ( $this->isDefault() ) {
			return self::DEFAULT_PACKAGE_NAME;
		}

		return parent::getName();
	}

	/**
	 * Gets/sets the classes of the package.
	 *
	 * @param ClassModelArray $value
	 *
	 * @return ClassModelArray
	 */
	public function classes( ClassModelArray $value = null ) {
		if ( !is_null( $value ) ) {
			$this->classes = $value;
		}

		return $this->classes;
	}

	/**
	 * @see Model::toArray()
	 */
	public function toArray() {
		$result = parent::toArray();
		$classes = array();

		foreach ( $this->classes as $class ) {
			/* @var $class ClassModel */
			$classes[] = $class->toArray();
		}

		$result[ 'classes' ] = $classes;
		return $result;
	}
}