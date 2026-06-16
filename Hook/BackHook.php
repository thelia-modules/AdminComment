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


namespace AdminComment\Hook;

use AdminComment\AdminComment;
use AdminComment\Form\AdminCommentCreateForm;
use AdminComment\Form\AdminCommentUpdateForm;
use AdminComment\Model\AdminCommentQuery;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Form\TheliaFormFactory;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Template\Parser\ParserResolver;
use Thelia\Core\Translation\Translator;

class BackHook extends BaseHook
{
    public function __construct(
        private readonly TheliaFormFactory $formFactory,
        ?EventDispatcherInterface $dispatcher = null,
        ?ParserResolver $parserResolver = null,
    ) {
        parent::__construct($dispatcher, $parserResolver);
    }

    public static function getSubscribedHooks(): array
    {
        return [
            'module.configuration' => [
                ['type' => 'back', 'method' => 'onModuleConfiguration'],
            ],
            'main.after-content' => [
                ['type' => 'back', 'method' => 'onMainAfterContent'],
            ],
            'main.footer-js' => [
                ['type' => 'back', 'method' => 'onMainFooterJs'],
            ],
            'category.edit-js' => [
                ['type' => 'back', 'method' => 'onEditJs'],
            ],
            'product.edit-js' => [
                ['type' => 'back', 'method' => 'onEditJs'],
            ],
            'folder.edit-js' => [
                ['type' => 'back', 'method' => 'onEditJs'],
            ],
            'content.edit-js' => [
                ['type' => 'back', 'method' => 'onEditJs'],
            ],
            'customer.edit-js' => [
                ['type' => 'back', 'method' => 'onEditJs'],
            ],
            'order.edit-js' => [
                ['type' => 'back', 'method' => 'onEditJs'],
            ],
            'coupon.update-js' => [
                ['type' => 'back', 'method' => 'onEditJs'],
            ],
            'category.tab' => [
                ['type' => 'back', 'method' => 'onEditTab'],
            ],
            'product.tab' => [
                ['type' => 'back', 'method' => 'onEditTab'],
            ],
            'folder.tab' => [
                ['type' => 'back', 'method' => 'onEditTab'],
            ],
            'content.tab' => [
                ['type' => 'back', 'method' => 'onEditTab'],
            ],
            'order.tab' => [
                ['type' => 'back', 'method' => 'onEditTab'],
            ],
            'orders.table-header' => [
                ['type' => 'back', 'method' => 'onListHeader'],
            ],
            'orders.table-row' => [
                ['type' => 'back', 'method' => 'onListRow'],
            ],
        ];
    }

    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        $event->add(
            $this->render('AdminComment/module-configuration.html.twig')
        );
    }

    public function onMainAfterContent(HookRenderEvent $event): void
    {
        $createForm = $this->formFactory->createForm(AdminCommentCreateForm::getName());
        $updateForm = $this->formFactory->createForm(AdminCommentUpdateForm::getName());

        $event->add(
            $this->render('main-after-content.html.twig', [
                'form' => $createForm->createView()->getView(),
                'update_form' => $updateForm->createView()->getView(),
                'labels' => $this->boLabels(),
            ])
        );
    }

    // Translated server-side: the default-twig Twig |trans uses the Symfony translator, which
    // does not know the module domains, so rendering in the template falls back to English.
    private function boLabels(): array
    {
        $translator = Translator::getInstance();
        $domain = 'admincomment.bo.default';

        return [
            'admin_comments' => $translator->trans('Admin Comments', [], $domain),
            'save' => $translator->trans('Save', [], $domain),
            'edit' => $translator->trans('Edit this comment', [], $domain),
            'delete' => $translator->trans('Delete this comment', [], $domain),
            'message' => $translator->trans('Message', [], $domain),
        ];
    }

    public function onMainFooterJs(HookRenderEvent $event): void
    {
        $event->add(
            $this->render('main-footer-js.html.twig')
        );
    }

    public function onEditJs(HookRenderEvent $event): void
    {
        $params = $this->getParameters($event);

        if (!empty($params)) {
            $event->add(
                $this->render('edit-js.html.twig', $params)
            );
        }
    }

    public function onEditTab(HookRenderBlockEvent $event): void
    {
        $params = $this->getTabParameters($event);

        if (empty($params['key'])) {
            return;
        }

        $count = AdminCommentQuery::create()
            ->filterByElementKey($params['key'])
            ->filterByElementId((int) $event->getArgument('id'))
            ->count();

        $event->add([
            'id'      => 'admin-comment',
            'title'   => Translator::getInstance()->trans(
                'Comment (%count)',
                ['%count' => $count],
                AdminComment::MESSAGE_DOMAIN
            ),
            'content' => '',
        ]);
    }

    public function onListHeader(HookRenderEvent $event): void
    {
        $event->add(
            "<td class='text-center'>"
            . Translator::getInstance()->trans('Comment', [], AdminComment::MESSAGE_DOMAIN)
            . "</td>"
        );
    }

    public function onListRow(HookRenderEvent $event): void
    {
        $key = null;

        if (false !== strpos($event->getCode(), 'orders.table-row')) {
            $key = 'order';
        }

        $count = 0;

        if ($key) {
            $count = AdminCommentQuery::create()
                ->filterByElementKey($key)
                ->filterByElementId($event->getArgument($key . '_id'))
                ->count();
        }

        $counter = '';

        if ($count > 0) {
            $counter = "<span class='badge text-bg-warning'>$count</span>";
        }

        $event->add("<td class='text-center'>$counter</td>");
    }

    protected function getParameters(HookRenderEvent $event): array
    {
        $out = [];

        $authorizedHook = [
            'category.edit-js',
            'product.edit-js',
            'folder.edit-js',
            'content.edit-js',
            'customer.edit-js',
            'order.edit-js',
            'coupon.update-js',
        ];

        foreach ($authorizedHook as $hookName) {
            if (false !== strpos($event->getCode(), $hookName)) {
                $key = explode('.', $hookName)[0];
                $id = (int) $event->getArgument($key . '_id');
                // try to get from url
                if (0 === $id) {
                    $id = (int) $this->getRequest()->query->get($key . '_id', 0);
                }
                if (0 !== $id) {
                    $out = [
                        'key' => $key,
                        'id'  => $id,
                    ];
                }
                break;
            }
        }

        return $out;
    }

    protected function getTabParameters(HookRenderBlockEvent $event): array
    {
        $out = [];

        $authorizedHook = [
            'category.tab',
            'product.tab',
            'folder.tab',
            'content.tab',
            'order.tab',
        ];

        foreach ($authorizedHook as $hookName) {
            if (false !== strpos($event->getCode(), $hookName)) {
                $key = explode('.', $hookName)[0];
                $out['key'] = $key;
            }
        }

        return $out;
    }
}
