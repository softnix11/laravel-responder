<?php

namespace Mangopixel\Responder\Traits;

use Illuminate\Http\Response;
use Mangopixel\Responder\Contracts\Responder;

/**
 * A trait you may apply to your base test case to give you some helper methods for
 * testing the API responses generated by the package.
 *
 * @package Laravel Responder
 * @author  Alexander Tømmerås <flugged@gmail.com>
 * @license The MIT License
 */
trait MakesApiRequests
{
    /**
     * Assert that the response is a valid success response.
     *
     * @param  mixed $data
     * @param  int   $status
     * @return $this
     */
    protected function seeSuccess( $data = null, $status = 200 )
    {
        $response = app( Responder::class )->success( $data, $status );

        $this->seeStatusCode( $status );
        $this->seeSuccessBaseStructure( $response );
        $this->seeSuccessData( $response->getData( true )[ 'data' ] );

        return $this;
    }

    /**
     * Assert that the response is a valid success response.
     *
     * @param  mixed $data
     * @param  int   $status
     * @return $this
     */
    protected function seeSuccessEquals( $data = null, $status = 200 )
    {
        $response = app( Responder::class )->success( $data, $status );

        $this->seeStatusCode( $status );
        $this->seeSuccessBaseStructure( $response );
        $this->seeJsonEquals( $response->getData( true ) );

        return $this;
    }

    /**
     * Assert that the response is a valid success response.
     *
     * @param  Response $data
     * @return $this
     */
    protected function seeSuccessBaseStructure( Response $response )
    {
        $this->seeJson( [
            'success' => true,
            'status' => $response->getStatusCode()
        ] )->seeJsonStructure( [ 'data' ] );

        return $this;
    }

    /**
     * Assert that the response data contains given values.
     *
     * @param  mixed $data
     * @return $this
     */
    protected function seeSuccessData( $data = null )
    {
        collect( $data )->each( function ( $value, $key ) {
            if ( is_array( $value ) ) {
                $this->seeSuccessDataResponse( $value );
            } else {
                $this->seeJson( [ $key => $value ] );
            }
        } );

        return $this;
    }

    /**
     * Decodes JSON response and returns the data.
     *
     * @return array
     */
    protected function getSuccessData()
    {
        return $this->decodeResponseJson()[ 'data' ];
    }

    /**
     * Assert that the response is a valid error response.
     *
     * @param  string $error
     * @param  int    $status
     * @return $this
     */
    protected function seeError( string $error, int $status = null )
    {
        if ( ! is_null( $status ) ) {
            $this->seeStatusCode( $status );
        }

        return $this->seeJson( [
            'success' => false,
            'status' => $status
        ] )->seeJsonSubset( [
            'error' => [
                'code' => $error
            ]
        ] );
    }
}