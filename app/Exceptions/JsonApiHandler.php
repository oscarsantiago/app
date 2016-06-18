<?php namespace App\Exceptions;

use Config\ConfigInterface as C;
use Exception;
use Interop\Container\ContainerInterface;
use Limoncello\Core\Contracts\Application\ExceptionHandlerInterface;
use Limoncello\Core\Contracts\Application\SapiInterface;
use Limoncello\JsonApi\Contracts\Encoder\EncoderInterface;
use Limoncello\JsonApi\Http\JsonApiResponse;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\ErrorCollection;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @package App
 */
class JsonApiHandler implements ExceptionHandlerInterface
{
    /**
     * @param Exception          $exception
     * @param SapiInterface      $sapi
     * @param ContainerInterface $container
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function handleException(Exception $exception, SapiInterface $sapi, ContainerInterface $container)
    {
        $this->handle($exception, $sapi, $container);
    }

    /**
     * @param Throwable          $throwable
     * @param SapiInterface      $sapi
     * @param ContainerInterface $container
     */
    public function handleThrowable(Throwable $throwable, SapiInterface $sapi, ContainerInterface $container)
    {
        $this->handle($throwable, $sapi, $container);
    }

    /**
     * @param Exception|Throwable $exception
     * @param SapiInterface       $sapi
     * @param ContainerInterface  $container
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    private function handle($exception, SapiInterface $sapi, ContainerInterface $container)
    {
        $message = 'Internal Server Error';

        // log the error if necessary (you can list here all error classes that should not be logged)
        $ignoredErrorTypes = [
            JsonApiException::class,
        ];
        if (array_key_exists(get_class($exception), $ignoredErrorTypes) === false &&
            $container->has(LoggerInterface::class) === true
        ) {
            /** @var LoggerInterface $logger */
            $logger = $container->get(LoggerInterface::class);
            $logger->critical($message, ['exception' => $exception]);
        }

        // compose JSON API Error with appropriate level of details
        if ($exception instanceof JsonApiException) {
            /** @var JsonApiException $exception */
            $errors   = $exception->getErrors();
            $httpCode = $exception->getHttpCode();
        } else {
            // we assume that 'normal' should be JsonApiException so anything else is 500 error code
            $httpCode = 500;
            $details  = null;
            /** @var C $config */
            $config       = $container->get(C::class);
            $debugEnabled = $config->getConfigValue(C::KEY_APP, C::KEY_APP_DEBUG_MODE);
            if ($debugEnabled === true) {
                $message = $exception->getMessage();
                $details = (string)$exception;
            }
            $errors = new ErrorCollection();
            $errors->add(new Error(null, null, $httpCode, null, $message, $details));
        }

        // encode the error and send to client
        /** @var EncoderInterface $encoder */
        $encoder  = $container->get(EncoderInterface::class);
        $content  = $encoder->encodeErrors($errors);
        $response = new JsonApiResponse($content, $httpCode);
        $sapi->handleResponse($response);
    }
}
