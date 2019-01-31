<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 22/11/2018
	 * Time: 18:35
	 */

	namespace theme\tools\pagesCache;


	class tools{

		static private $request_uri;


		static function sanitize_id( $string, $limit = 99, $useRegister = false, $ifEmpty_generateRandomKey = true, $additionSymbolsArr = [] ){
			$symbolsAllowArr = [
				'а' => 'a',
				'б' => 'b',
				'в' => 'v',
				'г' => 'g',
				'д' => 'd',
				'е' => 'e',
				'ё' => 'e',
				'ж' => 'zh',
				'з' => 'z',
				'и' => 'i',
				'й' => 'y',
				'к' => 'k',
				'л' => 'l',
				'м' => 'm',
				'н' => 'n',
				'о' => 'o',
				'п' => 'p',
				'р' => 'r',
				'с' => 's',
				'т' => 't',
				'у' => 'u',
				'ф' => 'f',
				'х' => 'h',
				'ц' => 'c',
				'ч' => 'ch',
				'ш' => 'sh',
				'щ' => 'sh',
				'ъ' => '',
				'ы' => 'i',
				'ь' => '',
				'э' => 'e',
				'ю' => 'yu',
				'я' => 'ya',

				'А' => 'a',
				'Б' => 'b',
				'В' => 'v',
				'Г' => 'g',
				'Д' => 'd',
				'Е' => 'e',
				'Ё' => 'e',
				'Ж' => 'zh',
				'З' => 'z',
				'И' => 'i',
				'Й' => 'y',
				'К' => 'k',
				'Л' => 'l',
				'М' => 'm',
				'Н' => 'n',
				'О' => 'o',
				'П' => 'p',
				'Р' => 'r',
				'С' => 's',
				'Т' => 't',
				'У' => 'u',
				'Ф' => 'f',
				'Х' => 'h',
				'Ц' => 'c',
				'Ч' => 'ch',
				'Ш' => 'sh',
				'Щ' => 'sh',
				'Ъ' => '',
				'Ы' => 'i',
				'Ь' => '',
				'Э' => 'e',
				'Ю' => 'yu',
				'Я' => 'ya',

				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',

				'a' => 'a',
				'b' => 'b',
				'c' => 'c',
				'd' => 'd',
				'e' => 'e',
				'f' => 'f',
				'g' => 'g',
				'h' => 'h',
				'i' => 'i',
				'j' => 'j',
				'k' => 'k',
				'l' => 'l',
				'm' => 'm',
				'n' => 'n',
				'o' => 'o',
				'p' => 'p',
				'q' => 'q',
				'r' => 'r',
				's' => 's',
				't' => 't',
				'u' => 'u',
				'v' => 'v',
				'w' => 'w',
				'x' => 'x',
				'y' => 'y',
				'z' => 'z',
				' ' => '-',
				'_' => '_',
				'-' => '-',
				'(' => '-',
				')' => '-',
				'&' => '-',
				'~' => '-',
				'[' => '-',
				']' => '-',
				'%20' => '-',
				'+' => '-',
				'=' => '-',
				',' => '-',
				'.' => '-'
			];
			///
			if( !is_array( $additionSymbolsArr ) || count( $additionSymbolsArr ) == 0 ){
				$additionSymbolsArr = [];
			} else {
				$symbolsAllowArr = array_merge( $symbolsAllowArr, $additionSymbolsArr );
			}
			///
			if( !is_string( $string ) && !is_int( $string ) ){
				return $ifEmpty_generateRandomKey ? '/' : '';
			}
			$R = '';
			if( is_int( $string ) ){
				return strlen( $string ) > $limit ? substr( $string . '', 0, $limit ) : $string;
			} else {
				for( $list_n = 0; $list_n < strlen( $string ) and $list_n < $limit; $list_n ++ ){
					$symStr = mb_substr( $string, $list_n, 1 ) . '';
					$symStrLow = mb_strtolower( $symStr );
					if( in_array( ord( $symStr ), [ 208, 209 ] ) ){
						//$symStr = (string)substr( $in_name, $list_n, 2 );
						//$symStrLow = (string)mb_strtolower( $symStr, 'UTF-8' );
						//$list_n ++;
					} //Если киррилица, брать 2 символа
					///
					$convertStr = '_';
					if( isset( $symbolsAllowArr[ $symStr ] ) ){
						$convertStr = $symbolsAllowArr[ $symStr ];
					} else if( !$useRegister && isset( $symbolsAllowArr[ $symStrLow ] ) ){
						$convertStr = $symbolsAllowArr[ $symStrLow ];
					} else if( $useRegister && isset( $symbolsAllowArr[ $symStrLow ] ) ){
						$convertStr = strtoupper( $symbolsAllowArr[ $symStrLow ] );
					}
					///
					$R .= $convertStr;
				}
			}
			////
			return rtrim( strtr( $R, [ '___' => '-', '__' => '-' ] ), '-_ ' );
		}


		/**
		 * Возвращает корневую папку сайта. Данная функция автоматически определяет корневую папку сайта, отталкиваясь на поиске папок с файлом index.php
		 * @return string
		 * @version 1.5
		 */
		static function base_dir(){
			static $base_dir = false;
			if( $base_dir === false ){
				$base_dir = '';
				$patch = explode( '/', dirname( $_SERVER['SCRIPT_FILENAME'] ) );
				$patches = [];
				$last_path = '';
				foreach( $patch as $dir ){
					if( $dir == '' ){
						continue;
					}
					$last_path .= '/' . $dir;
					$patches[] = $last_path;
				}
				$patches = array_reverse( $patches );
				foreach( $patches as $path ){
					$check_file = $path . '/wp-config.php';
					if( file_exists( $check_file ) && is_file( $check_file ) ){
						$base_dir = $path;
						break;
					}
				}
			}

			return $base_dir;
		}


		/**
		 * Возвращает относительный путь до файла / запроса URL
		 * @param      $url
		 * @param bool $include_params
		 * @return bool
		 */
		static function filter_url( $url, $include_params = true ){
			///filter url - relative / unique
			preg_match( '~^(?>(?<scheme>(?>https?:\/\/|\/\/))(?<domain>[^\/]+))?(?<uri>[^\?\n]+)(?<params>\?.*)?~im', $url, $matches );
			if( !isset( $matches['uri'] ) ) return false;
			$R = trim( $matches['uri'], '/' );
			///remove params
			if( $include_params && isset( $matches['params'] ) ){
				$R .= $matches['params'];
			}
			return $R == '' ? '/' : '/'.ltrim($R.'/');
		}


		/**
		 * @return mixed
		 */
		static function get_request_uri(){
			if( !is_string( self::$request_uri ) ){
				self::$request_uri = self::filter_url( $_SERVER['REQUEST_URI'], false );
			}
			return self::$request_uri;
		}

	}

