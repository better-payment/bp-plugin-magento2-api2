<?php

namespace BetterPayment\Core\Webapi\Rest\Request\Deserializer;

use InvalidArgumentException;
use Magento\Framework\App\State;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\Parameters;
use Magento\Framework\Webapi\Exception;
use Magento\Framework\Webapi\Rest\Request\DeserializerInterface;

/**
 * Class FormUrlencoded
 */
class FormUrlencoded implements DeserializerInterface
{
    /**
     * @var Parameters
     */
    private Parameters $parameters;
    /**
     * @var State
     */
    protected State $_appState;

    /**
     * FormUrlencoded constructor.
     * @param Parameters $parameters
     * @param State $appState
     */
    public function __construct(
        Parameters $parameters,
        State      $appState
    )
    {
        $this->parameters = $parameters;
        $this->_appState = $appState;
    }

    /**
     * Parse Request body into array of params.
     *
     * @param string $body Posted content from request.
     * @return array|null Return NULL if content is invalid.
     * @throws InvalidArgumentException
     * @throws Exception If decoding error was encountered.
     */
    public function deserialize($body): ?array
    {
        if (!is_string($body)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" data type is invalid. String is expected.', gettype($body))
            );
        }
        try {
            $decodedBody = urldecode($body);
            $this->parameters->fromString($decodedBody);
            return $this->parameters->toArray();
        } catch (\InvalidArgumentException $e) {
            if ($this->_appState->getMode() !== State::MODE_DEVELOPER) {
                throw new Exception(new Phrase('Decoding error.'));
            } else {
                throw new Exception(
                    new Phrase(
                        'Decoding error: %1%2%3%4',
                        [PHP_EOL, $e->getMessage(), PHP_EOL, $e->getTraceAsString()]
                    )
                );
            }
        }
    }
}
