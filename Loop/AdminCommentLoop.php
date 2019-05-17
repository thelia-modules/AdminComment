<?php


namespace AdminComment\Loop;


use AdminComment\Model\AdminComment;
use AdminComment\Model\AdminCommentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type;

class AdminCommentLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createAlphaNumStringTypeArgument('element_key'),
            Argument::createIntListTypeArgument('element_id'),
            new Argument(
                'order',
                new Type\TypeCollection(
                    new Type\EnumListType(
                        [
                            'id',
                            'id_reverse',
                            'created',
                            'created_reverse',
                            'updated',
                            'updated_reverse'
                        ]
                    )
                ),
                'manual'
            )
        );
    }

    public function buildModelCriteria()
    {
        $search = AdminCommentQuery::create();

        $id = $this->getId();
        if (null !== $id) {
            $search->filterById($id, Criteria::IN);
        }

        $elementKey = $this->getElementKey();
        if (null !== $elementKey) {
            $search->filterByElementKey($elementKey, Criteria::IN);
        }

        $elementId = $this->getElementId();
        if (null !== $elementId) {
            $search->filterByElementId($elementId, Criteria::IN);
        }

        $orders = $this->getOrder();
        if (null !== $orders) {
            foreach ($orders as $order) {
                switch ($order) {
                    case "id":
                        $search->orderById(Criteria::ASC);
                        break;
                    case "id_reverse":
                        $search->orderById(Criteria::DESC);
                        break;
                    case "created":
                        $search->addAscendingOrderByColumn('created_at');
                        break;
                    case "created_reverse":
                        $search->addDescendingOrderByColumn('created_at');
                        break;
                    case "updated":
                        $search->addAscendingOrderByColumn('updated_at');
                        break;
                    case "updated_reverse":
                        $search->addDescendingOrderByColumn('updated_at');
                        break;
                }
            }
        }

        return $search;
    }

    public function parseResults(LoopResult $loopResult)
    {
        /** @var AdminComment $comment */
        foreach ($loopResult->getResultDataCollection() as $comment) {
            $loopResultRow = new LoopResultRow($comment);
            $admin = $comment->getAdmin();
            $adminName = $admin ? $admin->getFirstname().' '.$admin->getLastname() : "";
            $loopResultRow
                ->set('ID', $comment->getId())
                ->set('ADMIN_ID', $comment->getAdminId())
                ->set('ADMIN_NAME', $adminName)
                ->set('COMMENT', $comment->getComment())
                ->set('ELEMENT_KEY', $comment->getElementKey())
                ->set('ELEMENT_ID', $comment->getElementId())
                ->set('CREATED_AT', $comment->getCreatedAt())
                ->set('UPDATED_AT', $comment->getUpdatedAt());

            $this->addOutputFields($loopResultRow, $comment);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}