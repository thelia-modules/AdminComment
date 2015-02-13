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


namespace AdminComment\Controller;

use AdminComment\AdminComment;
use AdminComment\Events\AdminCommentEvent;
use AdminComment\Events\AdminCommentEvents;
use AdminComment\Model\AdminCommentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\DateTimeFormat;

/**
 * Class AdminCommentController
 * @package AdminComment\Controller
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class AdminCommentController extends BaseAdminController
{

    public function listAction($key, $id)
    {
        $response = $this->checkAuth([], [AdminComment::getModuleCode()], AccessManager::VIEW);
        if (null !== $response) {
            return $response;
        }

        $comments = AdminCommentQuery::create()
            ->filterByElementKey($key)
            ->filterByElementId($id)
            ->orderByCreatedAt(Criteria::DESC)
            ->find();

        $data = [];
        foreach ($comments as $comment) {
            $data[] = $this->mapCommentObject($comment);
        }

        return $this->jsonResponse(
            json_encode(
                [
                    'success' => true,
                    'message' => '',
                    'data' => $data
                ]
            )
        );
    }

    private function mapCommentObject(\AdminComment\Model\AdminComment $comment)
    {
        $format = DateTimeFormat::getInstance($this->getRequest())
            ->getFormat();

        $data = [
            'id' => $comment->getId(),
            'date' => $comment->getCreatedAt($format),
            'admin' => (null !== $comment->getAdmin())
                ? $comment->getAdmin()->getFirstname() . ' ' . $comment->getAdmin()->getLastname()
                : '',
            'comment' => $comment->getComment(),
            'canChange' => $this->canChange($comment)
        ];

        return $data;
    }

    protected function canChange(\AdminComment\Model\AdminComment $comment)
    {
        $user = $this->getSecurityContext()->getAdminUser();

        if ($comment->getAdminId() === $user->getId()) {
            return true;
        }

        if ($user->getPermissions() === AdminResources::SUPERADMINISTRATOR) {
            return true;
        }

        return false;
    }

    public function createAction()
    {
        $response = $this->checkAuth([], [AdminComment::getModuleCode()], AccessManager::CREATE);
        if (null !== $response) {
            return $response;
        }

        $responseData = $this->createOrUpdate(
            'admin_comment_create_form',
            AdminCommentEvents::CREATE
        );

        return $this->jsonResponse(json_encode($responseData));
    }

    protected function createOrUpdate($formId, $eventName)
    {
        $this->checkXmlHttpRequest();

        $responseData = [
            "success" => false,
            "message" => ''
        ];

        $form = $this->createForm($formId);

        try {
            $formData = $this->validateForm($form);

            $event = new AdminCommentEvent();
            $event->bindForm($formData);

            $this->dispatch($eventName, $event);

            $responseData['success'] = true;
            $responseData['message'] = 'ok';
            $responseData['data'] = $this->mapCommentObject($event->getAdminComment());
        } catch (FormValidationException $e) {
            $responseData['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $responseData['message'] = $e->getMessage();
        }

        return $responseData;
    }

    public function saveAction()
    {
        $response = $this->checkAuth([], [AdminComment::getModuleCode()], AccessManager::UPDATE);
        if (null !== $response) {
            return $response;
        }

        $responseData = $this->createOrUpdate(
            'admin_comment_update_form',
            AdminCommentEvents::UPDATE
        );

        return $this->jsonResponse(json_encode($responseData));
    }

    public function deleteAction()
    {
        $response = $this->checkAuth([], [AdminComment::getModuleCode()], AccessManager::DELETE);
        if (null !== $response) {
            return $response;
        }

        $this->checkXmlHttpRequest();

        $responseData = [
            "success" => false,
            "message" => ''
        ];

        try {
            $id = intval($this->getRequest()->request->get('id'));

            if (0 === $id) {
                throw new \RuntimeException(
                    $this->getTranslator()->trans('Unknown comment', [], AdminComment::MESSAGE_DOMAIN)
                );
            }

            $event = new AdminCommentEvent();
            $event->setId($id);
            $this->dispatch(AdminCommentEvents::DELETE, $event);

            if (null === $event->getAdminComment()) {
                throw new \RuntimeException(
                    $this->getTranslator()->trans('Unknown comment', [], AdminComment::MESSAGE_DOMAIN)
                );
            }
            $responseData['success'] = true;
        } catch (\Exception $ex) {
            $responseData['message'] = $ex->getMessage();
        }

        return $this->jsonResponse(json_encode($responseData));
    }
}
