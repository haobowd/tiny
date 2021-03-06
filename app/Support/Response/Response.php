<?php


namespace App\Support\Response;


use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Response
 * @method TransformerResponse item($item, $transformer)
 * @method TransformerResponse collection(\Illuminate\Support\Collection $collection, $transformer)
 * @method TransformerResponse paginator(\Illuminate\Contracts\Pagination\Paginator $paginator, $transformer)
 * @method \League\Fractal\Manager getFractalManager()
 * @method void setFractalManager(\League\Fractal\Manager $fractalManager)
 * @method TransformerResponse setMeta(array $meta)
 * @method TransformerResponse addMeta($key, $value)
 * @method void getMeta()
 * @package App\Support
 */

class Response implements Responsable
{
    protected $status;
    protected $headers;
    protected $content;

    protected $resource = null;


    public function setContent($content){
        $this->content = $content;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function setHeaders($headers){
        $this->headers = $headers;
    }

    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->setContent($content);
        $this->setStatus($status);
        $this->setHeaders($headers);
    }

    public function toResponse($request)
    {
        return new IlluminateResponse($this->content, $this->status, $this->headers);
    }

    /**
     * @return Response
     */
    public function noContent()
    {
        $this->setContent(null);
        $this->setStatus(204);
        return $this;
    }

    /**
     * Return a 404 not found error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorNotFound($message = 'Not Found')
    {
        $this->error($message, 404);
    }

    public function __call($methodName, $arguments)
    {
        return (new TransformerResponse($this))->$methodName(...$arguments);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int    $statusCode
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }
}