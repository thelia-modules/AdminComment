<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/


namespace AdminComment\Action;

use AdminComment\Events\AdminCommentEvent;
use AdminComment\Events\AdminCommentEvents;
use AdminComment\Model\AdminComment;
use AdminComment\Model\AdminCommentQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AdminCommentAction
 * @package AdminComment\Action
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class AdminCommentAction implements EventSubscriberInterface
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
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            AdminCommentEvents::CREATE => ["create", 128],
            AdminCommentEvents::UPDATE => ["update", 128],
            AdminCommentEvents::DELETE => ["delete", 128],
        ];
    }

    public function create(AdminCommentEvent $event)
    {
        $adminComment = new AdminComment();
        $adminComment
            ->setAdminId($event->getAdminId())
            ->setElementKey($event->getElementKey())
            ->setElementId($event->getElementId())
            ->setComment($event->getComment())
            ->save();
        $event->setAdminComment($adminComment);
    }

    public function update(AdminCommentEvent $event)
    {
        $adminComment = AdminCommentQuery::create()->findPk($event->getId());
        if (null !== $adminComment) {
            $adminComment
                ->setComment($event->getComment())
                ->save();

            $event->setAdminComment($adminComment);
        }
    }

    public function delete(AdminCommentEvent $event)
    {
        $adminComment = AdminCommentQuery::create()->findPk($event->getId());
        if (null !== $adminComment) {
            $adminComment
                ->delete();

            $event->setAdminComment($adminComment);
        }
    }
}
