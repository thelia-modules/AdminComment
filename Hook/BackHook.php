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

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

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
}
