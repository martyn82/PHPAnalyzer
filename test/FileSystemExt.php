<?php
namespace Mend\IO\Stream {
	function is_readable( $filename ) {
		return IsReadable::$result;
	}

	class IsReadable {
		public static $result = false;
	}
}
