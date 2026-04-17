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

namespace AdminComment;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Core\Install\Database;
use Thelia\Module\BaseModule;

class AdminComment extends BaseModule
{
    const MESSAGE_DOMAIN = 'admincomment';

    public function postActivation(ConnectionInterface $con = null): void
    {
        // Table existence is probed via raw SQL so the method stays usable
        // before the Propel classes for this module are generated (which only
        // happens after the first cache warm-up that follows activation).
        $wrapped = $con->getWrappedConnection();
        $stmt = $wrapped->query("SHOW TABLES LIKE 'admin_comment'");
        if ($stmt->fetchColumn() === false) {
            $database = new Database($wrapped);
            $database->insertSql(null, [__DIR__ . DS . 'Config' . DS . 'thelia.sql']);
        }
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR . ucfirst(self::getModuleCode()). "/I18n/*"])
            ->autowire(true)
            ->autoconfigure(true);
    }
}
