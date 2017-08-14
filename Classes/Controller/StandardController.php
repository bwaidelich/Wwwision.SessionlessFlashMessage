<?php
namespace Wwwision\SessionlessFlashMessage\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Http\Cookie;
use Neos\Flow\Mvc\Controller\ActionController;
use Wwwision\SessionlessFlashMessage\TransientFlashMessageContainer;

class StandardController extends ActionController
{
    /**
     * Replace the session-based FlashMessageContainer of AbstractController with
     * a custom container that only stores the messages in memory
     *
     * @Flow\Inject
     * @var TransientFlashMessageContainer
     */
    protected $flashMessageContainer;


    /**
     * Default action, displaying the form and - if set - any FlashMessage
     */
    public function indexAction()
    {
    }

    /**
     * Some action that adds a FlashMessage and redirects back to the index action
     *
     * @param string $body
     * @param string $severity
     */
    public function flashMessageAction(string $body, string $severity)
    {
        $this->addFlashMessage($body, 'FlashMessage Title', $severity);
        $this->redirect('index');
    }

    /**
     * Overrides AbstractController::redirectToUri() in order to add a FlashMessage session cookie
     *
     * @param string $uri
     * @param int $delay
     * @param int $statusCode
     */
    protected function redirectToUri($uri, $delay = 0, $statusCode = 303)
    {
        $flashMessages = $this->flashMessageContainer->getMessagesAndFlush();
        if ($flashMessages !== []) {
            $this->response->setCookie(new Cookie('Neos_Flow_FlashMessages', json_encode($flashMessages), 0, null, null, '/', false, false));
        }
        parent::redirectToUri($uri, $delay, $statusCode);
    }

}
