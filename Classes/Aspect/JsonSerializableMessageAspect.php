<?php
namespace Wwwision\SessionlessFlashMessage\Aspect;

use Neos\Error\Messages\Message;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\AOP\JoinPointInterface;

/**
 * Introduce the \JsonSerializable interface to all \Neos\Error\Messages\Message implementations
 * so that they can be serialized to JSON more easily
 *
 * @Flow\Introduce("class(Neos\Error\Messages\Message)", interfaceName="JsonSerializable")
 * @Flow\Aspect
 */
class JsonSerializableMessageAspect
{
    /**
     * @Flow\Around("method(Neos\Error\Messages\Message->jsonSerialize())")
     */
    public function jsonSerializeImplementation(JoinPointInterface $joinPoint): array
    {
        /** @var Message $message */
        $message = $joinPoint->getProxy();
        return [
            'title' => $message->getTitle(),
            'body' => $message->render(),
            'code' => $message->getCode(),
            'severity' => $message->getSeverity(),
        ];
    }
}