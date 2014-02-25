<?php
namespace Mend\Cli\Command;

use Mend\Cli\Command;
use Mend\Cli\CommandResult;
use Mend\Cli\Status;
use Mend\Cli\Options;

class HelpCommand extends Command {
	/**
	 * @var string
	 */
	private $message = <<<HELP
PHP Analyzer Tool
----------------------

usage: %scriptname% [options]

Options:
	-%help%			Displays this help message.
	-%summary%			Turns on summary. This will output a summary report to the console.
	-%verbose%			Turns on verbosity mode. Prints verbose message to the console.

HELP;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var string
	 */
	private $currentScriptName;

	/**
	 * @var string
	 */
	private $defaultMemoryLimit;

	/**
	 * Constructs a new Help command.
	 *
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
		$vars = array(
			'%scriptname%' => $this->currentScriptName,
			'%help%' => Options::OPT_HELP,
			'%verbose%' => Options::OPT_VERBOSITY_FLAG,
			'%summary%' => Options::OPT_SUMMARIZE,
			'%defaultMemoryLimit%' => $this->defaultMemoryLimit
		);

		return new CommandResult(
			str_replace(
				array_keys( $vars ),
				array_values( $vars ),
				$this->message
			),
			Status::STATUS_OK
		);
	}
}