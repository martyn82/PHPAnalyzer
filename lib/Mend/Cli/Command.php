<?php
namespace Mend\Cli;

abstract class Command {
	/**
	 * Runs the command with given arguments.
	 *
	 * @return CommandResult
	 */
	abstract public function run();
}