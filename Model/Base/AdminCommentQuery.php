<?php

namespace AdminComment\Model\Base;

use \Exception;
use \PDO;
use AdminComment\Model\AdminComment as ChildAdminComment;
use AdminComment\Model\AdminCommentQuery as ChildAdminCommentQuery;
use AdminComment\Model\Map\AdminCommentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Thelia\Model\Admin;

/**
 * Base class that represents a query for the 'admin_comment' table.
 *
 *
 *
 * @method     ChildAdminCommentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAdminCommentQuery orderByAdminId($order = Criteria::ASC) Order by the admin_id column
 * @method     ChildAdminCommentQuery orderByComment($order = Criteria::ASC) Order by the comment column
 * @method     ChildAdminCommentQuery orderByElementKey($order = Criteria::ASC) Order by the element_key column
 * @method     ChildAdminCommentQuery orderByElementId($order = Criteria::ASC) Order by the element_id column
 * @method     ChildAdminCommentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildAdminCommentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildAdminCommentQuery groupById() Group by the id column
 * @method     ChildAdminCommentQuery groupByAdminId() Group by the admin_id column
 * @method     ChildAdminCommentQuery groupByComment() Group by the comment column
 * @method     ChildAdminCommentQuery groupByElementKey() Group by the element_key column
 * @method     ChildAdminCommentQuery groupByElementId() Group by the element_id column
 * @method     ChildAdminCommentQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildAdminCommentQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildAdminCommentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAdminCommentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAdminCommentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAdminCommentQuery leftJoinAdmin($relationAlias = null) Adds a LEFT JOIN clause to the query using the Admin relation
 * @method     ChildAdminCommentQuery rightJoinAdmin($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Admin relation
 * @method     ChildAdminCommentQuery innerJoinAdmin($relationAlias = null) Adds a INNER JOIN clause to the query using the Admin relation
 *
 * @method     ChildAdminComment findOne(ConnectionInterface $con = null) Return the first ChildAdminComment matching the query
 * @method     ChildAdminComment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAdminComment matching the query, or a new ChildAdminComment object populated from the query conditions when no match is found
 *
 * @method     ChildAdminComment findOneById(int $id) Return the first ChildAdminComment filtered by the id column
 * @method     ChildAdminComment findOneByAdminId(int $admin_id) Return the first ChildAdminComment filtered by the admin_id column
 * @method     ChildAdminComment findOneByComment(string $comment) Return the first ChildAdminComment filtered by the comment column
 * @method     ChildAdminComment findOneByElementKey(string $element_key) Return the first ChildAdminComment filtered by the element_key column
 * @method     ChildAdminComment findOneByElementId(int $element_id) Return the first ChildAdminComment filtered by the element_id column
 * @method     ChildAdminComment findOneByCreatedAt(string $created_at) Return the first ChildAdminComment filtered by the created_at column
 * @method     ChildAdminComment findOneByUpdatedAt(string $updated_at) Return the first ChildAdminComment filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildAdminComment objects filtered by the id column
 * @method     array findByAdminId(int $admin_id) Return ChildAdminComment objects filtered by the admin_id column
 * @method     array findByComment(string $comment) Return ChildAdminComment objects filtered by the comment column
 * @method     array findByElementKey(string $element_key) Return ChildAdminComment objects filtered by the element_key column
 * @method     array findByElementId(int $element_id) Return ChildAdminComment objects filtered by the element_id column
 * @method     array findByCreatedAt(string $created_at) Return ChildAdminComment objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildAdminComment objects filtered by the updated_at column
 *
 */
abstract class AdminCommentQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \AdminComment\Model\Base\AdminCommentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\AdminComment\\Model\\AdminComment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAdminCommentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAdminCommentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \AdminComment\Model\AdminCommentQuery) {
            return $criteria;
        }
        $query = new \AdminComment\Model\AdminCommentQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildAdminComment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AdminCommentTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AdminCommentTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildAdminComment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ADMIN_ID, COMMENT, ELEMENT_KEY, ELEMENT_ID, CREATED_AT, UPDATED_AT FROM admin_comment WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildAdminComment();
            $obj->hydrate($row);
            AdminCommentTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildAdminComment|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AdminCommentTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AdminCommentTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AdminCommentTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AdminCommentTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdminCommentTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the admin_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAdminId(1234); // WHERE admin_id = 1234
     * $query->filterByAdminId(array(12, 34)); // WHERE admin_id IN (12, 34)
     * $query->filterByAdminId(array('min' => 12)); // WHERE admin_id > 12
     * </code>
     *
     * @see       filterByAdmin()
     *
     * @param     mixed $adminId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByAdminId($adminId = null, $comparison = null)
    {
        if (is_array($adminId)) {
            $useMinMax = false;
            if (isset($adminId['min'])) {
                $this->addUsingAlias(AdminCommentTableMap::ADMIN_ID, $adminId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($adminId['max'])) {
                $this->addUsingAlias(AdminCommentTableMap::ADMIN_ID, $adminId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdminCommentTableMap::ADMIN_ID, $adminId, $comparison);
    }

    /**
     * Filter the query on the comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE comment = 'fooValue'
     * $query->filterByComment('%fooValue%'); // WHERE comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $comment)) {
                $comment = str_replace('*', '%', $comment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdminCommentTableMap::COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query on the element_key column
     *
     * Example usage:
     * <code>
     * $query->filterByElementKey('fooValue');   // WHERE element_key = 'fooValue'
     * $query->filterByElementKey('%fooValue%'); // WHERE element_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $elementKey The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByElementKey($elementKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($elementKey)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $elementKey)) {
                $elementKey = str_replace('*', '%', $elementKey);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdminCommentTableMap::ELEMENT_KEY, $elementKey, $comparison);
    }

    /**
     * Filter the query on the element_id column
     *
     * Example usage:
     * <code>
     * $query->filterByElementId(1234); // WHERE element_id = 1234
     * $query->filterByElementId(array(12, 34)); // WHERE element_id IN (12, 34)
     * $query->filterByElementId(array('min' => 12)); // WHERE element_id > 12
     * </code>
     *
     * @param     mixed $elementId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByElementId($elementId = null, $comparison = null)
    {
        if (is_array($elementId)) {
            $useMinMax = false;
            if (isset($elementId['min'])) {
                $this->addUsingAlias(AdminCommentTableMap::ELEMENT_ID, $elementId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($elementId['max'])) {
                $this->addUsingAlias(AdminCommentTableMap::ELEMENT_ID, $elementId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdminCommentTableMap::ELEMENT_ID, $elementId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AdminCommentTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AdminCommentTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdminCommentTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AdminCommentTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AdminCommentTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdminCommentTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Thelia\Model\Admin object
     *
     * @param \Thelia\Model\Admin|ObjectCollection $admin The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function filterByAdmin($admin, $comparison = null)
    {
        if ($admin instanceof \Thelia\Model\Admin) {
            return $this
                ->addUsingAlias(AdminCommentTableMap::ADMIN_ID, $admin->getId(), $comparison);
        } elseif ($admin instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdminCommentTableMap::ADMIN_ID, $admin->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAdmin() only accepts arguments of type \Thelia\Model\Admin or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Admin relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function joinAdmin($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Admin');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Admin');
        }

        return $this;
    }

    /**
     * Use the Admin relation Admin object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\AdminQuery A secondary query class using the current class as primary query
     */
    public function useAdminQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAdmin($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Admin', '\Thelia\Model\AdminQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAdminComment $adminComment Object to remove from the list of results
     *
     * @return ChildAdminCommentQuery The current query, for fluid interface
     */
    public function prune($adminComment = null)
    {
        if ($adminComment) {
            $this->addUsingAlias(AdminCommentTableMap::ID, $adminComment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the admin_comment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdminCommentTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AdminCommentTableMap::clearInstancePool();
            AdminCommentTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildAdminComment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildAdminComment object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AdminCommentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AdminCommentTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        AdminCommentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AdminCommentTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildAdminCommentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(AdminCommentTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildAdminCommentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(AdminCommentTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildAdminCommentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(AdminCommentTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildAdminCommentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(AdminCommentTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildAdminCommentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(AdminCommentTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildAdminCommentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(AdminCommentTableMap::CREATED_AT);
    }

} // AdminCommentQuery
