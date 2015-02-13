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


namespace AdminComment\Form;

use AdminComment\AdminComment;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

/**
 * Class AdminCommentCreateForm
 * @package AdminComment\Form
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class AdminCommentUpdateForm extends BaseForm
{
    /** @var Translator $translator */
    protected $translator;

    protected function trans($id, $parameters = [])
    {
        if (null === $this->translator) {
            $this->translator = Translator::getInstance();
        }

        return $this->translator->trans($id, $parameters, AdminComment::MESSAGE_DOMAIN);
    }

    /**
     *
     * in this function you add all the fields you need for your Form.
     * Form this you have to call add method on $this->formBuilder attribute :
     *
     * $this->formBuilder->add("name", "text")
     *   ->add("email", "email", array(
     *           "attr" => array(
     *               "class" => "field"
     *           ),
     *           "label" => "email",
     *           "constraints" => array(
     *               new \Symfony\Component\Validator\Constraints\NotBlank()
     *           )
     *       )
     *   )
     *   ->add('age', 'integer');
     *
     * @return null
     */
    protected function buildForm()
    {
        $this
            ->formBuilder
            ->add(
                "id",
                "integer",
                [
                    "label" => $this->trans("Comment Id"),
                    "constraints" => [
                        new NotBlank()
                    ]
                ]
            )
            ->add(
                "comment",
                "textarea",
                [
                    "label" => $this->trans("Comment"),
                    "constraints" => [
                        new NotBlank()
                    ]
                ]
            );
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'admin_comment_update';
    }
}
