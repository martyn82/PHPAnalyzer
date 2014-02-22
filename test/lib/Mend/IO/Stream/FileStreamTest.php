<?php
namespace Mend\IO\Stream;

// mock filesystem functions {
	function fclose() {
		return FileStreamTest::fclose();
	}
	function fopen() {
		return FileStreamTest::fopen();
	}
	function fread() {
		return FileStreamTest::fread();
	}
	function fwrite() {
		return FileStreamTest::fwrite();
	}
	function is_resource() {
		return FileStreamTest::isResource();
	}
	function is_readable() {
		return FileStreamTest::isReadable();
	}
	function is_writable() {
		return FileStreamTest::isWritable();
	}
	function is_writeable() {
		return FileStreamTest::isWritable();
	}
	function filesize() {
		return FileStreamTest::fileSize();
	}
// }

abstract class FileStreamTest extends \TestCase {
	public static $fopenResult;
	public static $fcloseResult;
	public static $freadResult;
	public static $fwriteResult;
	public static $isResourceResult;
	public static $isReadableResult;
	public static $isWritableResult;
	public static $filesizeResult;

	public static function fopen() {
		return self::$fopenResult;
	}

	public static function fclose() {
		return self::$fcloseResult;
	}

	public static function fread() {
		return self::$freadResult;
	}

	public static function fwrite() {
		return self::$fwriteResult;
	}

	public static function isResource() {
		return self::$isResourceResult;
	}

	public static function isReadable() {
		return self::$isReadableResult;
	}

	public static function isWritable() {
		return self::$isWritableResult;
	}

	public static function fileSize() {
		return self::$filesizeResult;
	}

	public function setUp() {
		self::$fopenResult = null;
		self::$fcloseResult = null;
		self::$freadResult = null;
		self::$fwriteResult = null;
		self::$isResourceResult = null;
		self::$isReadableResult = null;
		self::$isWritableResult = null;
	}
}