<?php
namespace Mend\IO\Stream {
	function is_readable( $filename ) {
		return IsReadable::$result;
	}

	function is_writable( $filename ) {
		return IsWritable::$result;
	}

	class IsReadable {
		public static $result = false;
	}

	class IsWritable {
		public static $result = false;
	}
}
