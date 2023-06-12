<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;

/**
 * Class StatsController
 * @package Vanguard\Http\Controllers\Api
 */
class LogicalTestsController extends ApiController
{
    public function __construct(){

    }

    /**
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function index(){

    }

    public function taskThree( Request $request ){
        $input = $request->get( 'input' ) ?? 'D2705A,D2705E';
        $letterArr = $this->_getLetterArr();
        $data = explode( ',', $input );
        $arr = [];
        $result = [];
        if( !empty( $data ) ){
            foreach( $data as $key => $value ){
                $arr[$key]['letter'] = array_slice( $letterArr, 0, array_search( substr( $value, -1 ), $letterArr ) );
                $arr[$key]['value'] = substr( $value, 0, -1 );
            }
            foreach( $arr as $valueT ){
                if( !empty( $valueT['letter'] ) ){
                    foreach( $valueT['letter'] as $valueTh ){
                        $checkLetter = $valueT['value'].$valueTh;
                        if( !in_array( $checkLetter, $data ) ){
                            $result[] = $valueT['value'].$valueTh;
                        }
                    }
                }
            }
        }
        return response()->json( array_keys( array_flip( $result ) ) );
    }

    private function _getLetterArr(){
        $result = [];
        for( $x = ord( 'A' ); $x <= ord( 'Z' ); $x++ ){
            $result[] = chr( $x );
        }
        return $result;
    }

    public function taskFour( Request $request ){
        $input = $request->get( 'input' ) ?? "(())";
        $valid = 'valid';
        $invalid = 'invalid';
        $model = [
            '(' => 1,
            ')' => 2,
            '{' => 4,
            '}' => 5,
            '[' => 7,
            ']' => 8,
        ];

        $data = str_split( $input );
        $newData = [];
        foreach( $data as $value ){
            if( !array_key_exists( $value, $model ) ){
                return response()->json( $invalid );
            }
            $newData[] = $model[$value];
        }
        $length = sizeof( $newData );
        if( $length % 2 != 0 ){
            return response()->json( $invalid );
        }
        $midNum = $length / 2;
        if( array_sum( array_slice( $newData, 0, $midNum ) ) + $midNum === array_sum ( array_reverse( array_slice( $newData, $midNum  ) ) ) ){
            return response()->json( $valid );
        }

        $checkNum = 0;
        foreach( $newData as $key => $value ){
            if ( ( empty( $checkNum ) || $checkNum == 2 ) ){
                if( $newData[$key] + 1 === $newData[$key+1] ){
                    $checkNum = 1;
                    continue;
                } else {
                    return response()->json( $invalid );
                }
            } else if ( $checkNum == 1 ){
                $checkNum++;
            }
        }
        return response()->json( $valid );
    }
}
