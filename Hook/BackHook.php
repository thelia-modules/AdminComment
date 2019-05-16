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
use AdminComment\Model\AdminCommentQuery;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Translation\Translator;

/**
 * Class BackHook
 * @package AdminComment\Hook
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class BackHook extends baseHook
{

    public function onMainAfterContent(HookRenderEvent $event)
    {
        $event->add(
            $this->render('main-after-content.html')
        );
    }

    public function onMainFooterJs(HookRenderEvent $event)
    {
        $event->add(
            $this->render('main-footer-js.html')
        );
    }

    public function onEditJs(HookRenderEvent $event)
    {
        $params = $this->getParameters($event);

        if (!empty($params)) {
            $event->add(
                $this->render(
                    'edit-js.html',
                    $params
                )
            );
        }
    }

    public function onEditTab(HookRenderBlockEvent $event)
    {
        $params = $this->getTabParameters($event);

        $count = AdminCommentQuery::create()
            ->filterByElementKey($params['key'])
            ->filterByElementId($event->getArgument('id'))
            ->count();


        $event->add(
          [
              "id" => 'admin-comment',
              "title" => Translator::getInstance()->trans("Comment (%count)", ['%count' => $count], AdminComment::MESSAGE_DOMAIN),
              "content" =>  ""

          ]
        );
    }

    public function onListHeader(HookRenderEvent $event)
    {
        $event->add("<td class='text-center'>".Translator::getInstance()->trans('Comment', [], AdminComment::MESSAGE_DOMAIN)."</td>");
    }

    public function onListRow(HookRenderEvent $event)
    {
        $key = null;

        if (false !== strpos($event->getName(), 'orders.table-row')) {
            $key = 'order';
        }

        $count = 0;

        if ($key) {
            $count = AdminCommentQuery::create()
                ->filterByElementKey($key)
                ->filterByElementId($event->getArgument($key.'_id'))
                ->count();

        }

        $counter = "";

        if ($count > 0) {
            $counter = "<span class='badge' style='background-color: #f39922'>$count</span>";
        }

        $event->add("<td class='text-center'>$counter</td>");
    }

    protected function getParameters(HookRenderEvent $event)
    {
        $out = [];

        $authorizedHook = [
            'category.edit-js',
            'product.edit-js',
            'folder.edit-js',
            'content.edit-js',
            'customer.edit-js',
            'order.edit-js',
            'coupon.update-js'
        ];

        foreach ($authorizedHook as $hookName) {
            if (false !== strpos($event->getName(), $hookName)) {
                $key = explode('.', $hookName)[0];
                $id = intval($event->getArgument($key . '_id'));
                // try to get from url
                if (0 === $id) {
                    $id = intval($this->getRequest()->query->get($key . '_id', 0));
                }
                if (0 !== $id) {
                    $out = [
                        'key' => $key,
                        'id' => $id
                    ];
                }
                break;
            }
        }

        return $out;
    }

    protected function getTabParameters(HookRenderBlockEvent $event)
    {
        $out = [];

        $authorizedHook = [
            'category.tab',
            'product.tab',
            'folder.tab',
            'content.tab',
            'order.tab'
        ];

        foreach ($authorizedHook as $hookName) {
            if (false !== strpos($event->getName(), $hookName)) {
                $key = explode('.', $hookName)[0];
                $out['key'] = $key;
            }
        }

        return $out;
    }
}
