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

use AdminComment\Model\AdminComment;
use Thelia\Core\Event\ActionEvent;

/**
 * Class AdminCommentEvent
 * @package AdminComment\Events
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class AdminCommentEvent extends ActionEvent
{
    /** @var integer */
    protected $id;

    /** @var integer */
    protected $admin_id;

    /** @var string */
    protected $element_key;

    /** @var integer */
    protected $element_id;

    /** @var string */
    protected $comment;

    /** @var AdminComment */
    protected $adminComment;

    /**
     * @return int
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * @param int $admin_id
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;

        return $this;
    }

    /**
     * @return AdminComment
     */
    public function getAdminComment()
    {
        return $this->adminComment;
    }

    /**
     * @param AdminComment $adminComment
     */
    public function setAdminComment($adminComment)
    {
        $this->adminComment = $adminComment;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return int
     */
    public function getElementId()
    {
        return $this->element_id;
    }

    /**
     * @param int $element_id
     */
    public function setElementId($element_id)
    {
        $this->element_id = $element_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getElementKey()
    {
        return $this->element_key;
    }

    /**
     * @param string $element_key
     */
    public function setElementKey($element_key)
    {
        $this->element_key = $element_key;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
