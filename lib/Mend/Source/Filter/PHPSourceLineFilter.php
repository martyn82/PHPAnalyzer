<?php
namespace Mend\Source\Filter;

class PHPSourceLineFilter extends SourceLineFilter {
	/**
	 * @see SourceLineFilter::isComment()
	 */
	public function isComment( $line ) {
		$line = trim( $line );

		if ( $this->isBlank( $line ) ) {
			return $this->inComment();
		}

		if ( $this->inComment() && preg_match( '/\*\/$/', $line ) > 0 ) {
			// End of line is end of comment
			$this->setInComment( false );
			return true;
		}

		if ( !$this->inComment() ) {
			if ( preg_match( '/^\/\//', $line ) > 0 || preg_match( '/^#/', $line ) > 0 ) {
				// Line starts with single line comment
				return true;
			}

			if ( preg_match( '/^\/\*.[^\*\/]*\*\/$/', $line ) > 0 ) {
				// Line starts and ends with multiline comment pointers without intermediate comment-endings.
				return true;
			}

			$matches = array();
			if ( preg_match( '/^\/\*(.*)$/', $line, $matches ) > 0 ) {
				if ( isset( $matches[ 1 ] ) && preg_match( '/\*\/.*/', $matches[ 1 ] ) == 0 ) {
					// Line starting with comment-start and having no comment ends is a comment block start.
					$this->setInComment( true );
					return true;
				}
			}
		}

		return $this->inComment();
	}
}
