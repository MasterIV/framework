<?php


namespace Iv\Framework;

class Files {
	const ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

	/**
	 * @param $pattern
	 * @param bool $extension
	 * @return \string[]
	 */
	public static function glob( $pattern, $extension = true ) {
		$glob = glob( $pattern );

		if( !empty( $glob ))
			foreach(  $glob as $file )
				if( $extension ) $result[] = substr( $file, 1+strrpos( $file, '/' ));
				else $result[] = substr( $file, 1+strrpos( $file, '/' ), -1*strlen(strrchr( $file, '.')));

		return empty($result) ? [] : array_combine($result, $result);
	}

	/**
	 * @param $file
	 * @return string
	 */
	public static function asset($file) {
		return file_get_contents( self::ROOT . 'assets' . DIRECTORY_SEPARATOR . $file );
	}
}