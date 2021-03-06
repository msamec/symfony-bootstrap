<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestJsonToArraySubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'convertJsonToArray',
        ];
    }

    /**
     * Convert request JSON into array.
     *
     * @param FilterControllerEvent $event
     */
    public function convertJsonToArray(FilterControllerEvent $event): void
    {
        $request = $event->getRequest();

        if ('json' !== $request->getContentType() || !$request->getContent()) {
            return;
        }

        /** @var string $content */
        $content = $request->getContent();

        $data = \Safe\json_decode($content, true);

        $request->request->replace(\is_array($data) ? $data : []);
    }
}
