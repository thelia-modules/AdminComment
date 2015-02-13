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


namespace AdminComment\Events;

/**
 * Class AdminCommentEvents
 * @package AdminComment\Events
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class AdminCommentEvents
{
    const CREATE = "action.admin_comment.create";
    const UPDATE = "action.admin_comment.update";
    const DELETE = "action.admin_comment.delete";
}
