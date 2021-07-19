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
use AdminComment\Form\AdminCommentCreateForm;
use AdminComment\Form\AdminCommentUpdateForm;
use AdminComment\Model\AdminCommentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\SecurityContext;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\DateTimeFormat;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module/AdminComment", name="admin_comment_module")
 * Class AdminCommentController
 * @package AdminComment\Controller
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class AdminCommentController extends BaseAdminController
{

    /**
     * @Route("/list/{key}/{id}", name="_list", methods="GET")
     */
    public function listAction($key, $id, RequestStack $requestStack, SecurityContext $securityContext)
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
            $data[] = $this->mapCommentObject($comment, $requestStack->getCurrentRequest(), $securityContext);
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

    private function mapCommentObject(\AdminComment\Model\AdminComment $comment, Request $request, $securityContext)
    {
        $format = DateTimeFormat::getInstance($request)
            ->getFormat();

        $data = [
            'id' => $comment->getId(),
            'date' => $comment->getCreatedAt($format),
            'admin' => (null !== $comment->getAdmin())
                ? $comment->getAdmin()->getFirstname() . ' ' . $comment->getAdmin()->getLastname()
                : '',
            'comment' => $comment->getComment(),
            'canChange' => $this->canChange($comment, $securityContext)
        ];

        return $data;
    }

    protected function canChange(\AdminComment\Model\AdminComment $comment, $securityContext)
    {
        $user = $securityContext->getAdminUser();

        if ($comment->getAdminId() === $user->getId()) {
            return true;
        }

        if ($user->getPermissions() === AdminResources::SUPERADMINISTRATOR) {
            return true;
        }

        return false;
    }

    /**
     * @Route("/create", name="_create", methods="POST")
     */
    public function createAction(EventDispatcherInterface $eventDispatcher, RequestStack $requestStack, SecurityContext $securityContext)
    {
        $response = $this->checkAuth([], [AdminComment::getModuleCode()], AccessManager::CREATE);
        if (null !== $response) {
            return $response;
        }

        $responseData = $this->createOrUpdate(
            AdminCommentCreateForm::getName(),
            AdminCommentEvents::CREATE,
            $eventDispatcher,
            $requestStack->getCurrentRequest(),
            $securityContext
        );

        return $this->jsonResponse(json_encode($responseData));
    }

    protected function createOrUpdate($formId, $eventName, EventDispatcherInterface $eventDispatcher, $request, $securityContext)
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

            $eventDispatcher->dispatch($event, $eventName);

            $responseData['success'] = true;
            $responseData['message'] = 'ok';
            $responseData['data'] = $this->mapCommentObject($event->getAdminComment(), $request, $securityContext);
        } catch (FormValidationException $e) {
            $responseData['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $responseData['message'] = $e->getMessage();
        }

        return $responseData;
    }

    /**
     * @Route("/save", name="_save", methods="POST")
     */
    public function saveAction(EventDispatcherInterface $eventDispatcher, RequestStack $requestStack, SecurityContext $securityContext)
    {
        $response = $this->checkAuth([], [AdminComment::getModuleCode()], AccessManager::UPDATE);
        if (null !== $response) {
            return $response;
        }

        $responseData = $this->createOrUpdate(
            AdminCommentUpdateForm::getName(),
            AdminCommentEvents::UPDATE,
            $eventDispatcher,
            $requestStack->getCurrentRequest(),
            $securityContext
        );

        return $this->jsonResponse(json_encode($responseData));
    }

    /**
     * @Route("/delete", name="_delete", methods="POST")
     */
    public function deleteAction(RequestStack $requestStack, Translator $translator, EventDispatcherInterface $eventDispatcher)
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
            $id = (int)$requestStack->getCurrentRequest()->request->get('id');

            if (0 === $id) {
                throw new \RuntimeException(
                    $translator->trans('Unknown comment', [], AdminComment::MESSAGE_DOMAIN)
                );
            }

            $event = new AdminCommentEvent();
            $event->setId($id);
            $eventDispatcher->dispatch($event, AdminCommentEvents::DELETE);

            if (null === $event->getAdminComment()) {
                throw new \RuntimeException(
                    $translator->trans('Unknown comment', [], AdminComment::MESSAGE_DOMAIN)
                );
            }
            $responseData['success'] = true;
        } catch (\Exception $ex) {
            $responseData['message'] = $ex->getMessage();
        }

        return $this->jsonResponse(json_encode($responseData));
    }
}
