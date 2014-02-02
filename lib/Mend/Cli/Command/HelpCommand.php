<?php
namespace Mend\Cli\Command;

use Mend\Cli\Command;
use Mend\Cli\CommandResult;
use Mend\Cli\Status;

class HelpCommand extends Command {
	/**
	 * @var string
	 */
	private $message = <<<HELP
PHP Analyzer Tool
----------------------

usage: %s [options]

Options:
	-h			Displays this help message.
	-m			Specify a memory limit (e.g., 256M or 1G) [default: %s].
	-o			Specify output format (text|json) [default: text].
	-e			Specify a comma-separated list of file extensions to analyze.
	-v			Turns on verbosity mode. Prints verbose message to stdout.

HELP;

	/**
	 * @var string
	 */
	private $currentScriptName;

	/**
	 * @var string
	 */
	private $defaultMemoryLimit;

	/**
	 * @param string $currentScriptName
	 * @param string $defaultMemoryLimit
	 */
	public function __construct( $currentScriptName, $defaultMemoryLimit ) {
		$this->currentScriptName = $currentScriptName;
		$this->defaultMemoryLimit = $defaultMemoryLimit;
	}

	/**
	 * @see Command::run()
	 */
	public function run() {
		return new CommandResult(
			sprintf( $this->message, $this->currentScriptName, $this->defaultMemoryLimit ),
			Status::STATUS_OK
		);
	}
}