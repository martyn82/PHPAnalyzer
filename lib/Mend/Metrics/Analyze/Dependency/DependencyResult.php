<?php
namespace Mend\Metrics\Analyze\Dependency;

use Mend\Metrics\Model\Code\ClassModel;
use Mend\Metrics\Model\Code\ClassModelArray;

class DependencyResult {
	/**
	 * @var ClassModelArray
	 */
	private $fanIn;

	/**
	 * @var ClassModelArray
	 */
	private $fanOut;

	/**
	 * @var ClassModel
	 */
	private $class;

	/**
	 * @var integer
	 */
	private $inLevel;

	/**
	 * @var integer
	 */
	private $outLevel;

	/**
	 * Constructs a new depedency result.
	 *
	 * @param ClassModel $class
	 * @param ClassModelArray $fanIn
	 * @param ClassModelArray $fanOut
	 * @param integer $fanInLevel
	 * @param integer $fanOutLevel
	 */
	public function __construct(
		ClassModel $class,
		ClassModelArray $fanIn,
		ClassModelArray $fanOut,
		$fanInLevel,
		$fanOutLevel
	) {
		$this->class = $class;
		$this->fanIn = $fanIn;
		$this->fanOut = $fanOut;
		$this->inLevel = (int) $fanInLevel;
		$this->outLevel = (int) $fanOutLevel;
	}

	/**
	 * Retrieves the class model.
	 *
	 * @return ClassModel
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * Retrieves the fan in dependencies.
	 *
	 * @return ClassModelArray
	 */
	public function getFanIn() {
		return $this->fanIn;
	}

	/**
	 * Retrieves the fan out dependencies.
	 *
	 * @return ClassModelArray
	 */
	public function getFanOut() {
		return $this->fanOut;
	}

	/**
	 * Retrieves the fan-in level.
	 *
	 * @return integer
	 */
	public function getFanInLevel() {
		return $this->inLevel;
	}

	/**
	 * Retrieves the fan-out level.
	 *
	 * @return integer
	 */
	public function getFanOutLevel() {
		return $this->outLevel;
	}
}