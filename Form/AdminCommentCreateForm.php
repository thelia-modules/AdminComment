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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

/**
 * Class AdminCommentCreateForm
 * @package AdminComment\Form
 * @author Julien Chanséaume <julien@thelia.net[>
 */
class AdminCommentCreateForm extends BaseForm
{
    /** @var Translator $translator */
    protected $translator;

    /**
     * @return string the name of you form. This name must be unique
     */
    public static function getName()
    {
        return 'admin_comment_create';
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
                "admin_id",
                IntegerType::class,
                [
                    "label" => $this->trans("Admin Id"),
                    "constraints" => [
                        new NotBlank()
                    ]
                ]
            )
            ->add(
                "element_key",
                TextType::class,
                [
                    "label" => $this->trans("Element key"),
                    "constraints" => [
                        new NotBlank()
                    ]
                ]
            )
            ->add(
                "element_id",
                NumberType::class,
                [
                    "label" => $this->trans("Element id"),
                    "constraints" => [
                        new NotBlank()
                    ]
                ]
            )
            ->add(
                "comment",
                TextareaType::class,
                [
                    "label" => $this->trans("Comment"),
                    "constraints" => [
                        new NotBlank()
                    ]
                ]
            );
    }

    protected function trans($id, $parameters = [])
    {
        if (null === $this->translator) {
            $this->translator = Translator::getInstance();
        }

        return $this->translator->trans($id, $parameters, AdminComment::MESSAGE_DOMAIN);
    }
}
