<?php
namespace Mend\Cli;

class CommandResult {
	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var integer
	 */
	private $status;

	/**
	 * Constructs a new command result.
	 *
	 * @param string $message
	 * @param integer $status
	 */
	public function __construct( $message, $status ) {
		$this->message = (string) $message;
		$this->status = (int) $status;
	}

	/**
	 * Determines whether the result is an error.
	 *
	 * @return boolean
	 */
	public function isError() {
		return $this->status != Status::STATUS_OK;
	}

	/**
	 * Retrieves the message.
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Retrieves the status code.
	 *
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}
}