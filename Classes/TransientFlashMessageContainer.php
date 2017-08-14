<?php
namespace Wwwision\SessionlessFlashMessage;

use Neos\Error\Messages\Message;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
final class TransientFlashMessageContainer
{
    /**
     * @var array
     */
    private $messages = [];

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @param string $severity severity of messages (from Message::SEVERITY_* constants) to return.
     * @return Message[]
     */
    public function getMessages($severity = null): array
    {
        if ($severity === null) {
            return $this->messages;
        }

        $messages = [];
        foreach ($this->messages as $message) {
            if ($message->getSeverity() === $severity) {
                $messages[] = $message;
            }
        }
        return $messages;
    }

    /**
     * Remove messages from this container.
     *
     * @param string $severity severity of messages (from Message::SEVERITY_* constants) to remove.
     */
    public function flush($severity = null)
    {
        if ($severity === null) {
            $this->messages = [];
        } else {
            foreach ($this->messages as $index => $message) {
                if ($message->getSeverity() === $severity) {
                    unset($this->messages[$index]);
                }
            }
        }
    }

    /**
     * Get all flash messages (with given severity) currently available and remove them from the container.
     *
     * @param string $severity severity of the messages (One of the Message::SEVERITY_* constants)
     * @return Message[]
     */
    public function getMessagesAndFlush($severity = null): array
    {
        $messages = $this->getMessages($severity);
        if (count($messages) > 0) {
            $this->flush($severity);
        }
        return $messages;
    }
}