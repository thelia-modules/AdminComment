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

use AdminComment\Model\AdminCommentQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class AdminComment extends BaseModule
{
    const MESSAGE_DOMAIN = 'admincomment';

    public function postActivation(ConnectionInterface $con = null)
    {
        // Schema
        try {
            AdminCommentQuery::create()->findOne();
        } catch (\Exception $ex) {
            $database = new Database($con->getWrappedConnection());
            $database->insertSql(null, [__DIR__ . DS . 'Config' . DS . 'thelia.sql']);
        }
    }
}
