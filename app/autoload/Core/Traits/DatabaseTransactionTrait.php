<?php

namespace App\Core\Traits;

use Exception;

/**
 * Trait DatabaseTransactionTrait
 *
 * @package App\Core\Traits
 */
trait DatabaseTransactionTrait
{
    /** @var bool Использовать тразакции при работе с БД */
    protected $useDbTransactions = true;

    /**
     * @return bool
     */
    public function isUseDbTransactions(): bool
    {
        return $this->useDbTransactions;
    }

    /**
     * @param bool $useTransactions
     * @return static
     */
    public function setUseDbTransactions(bool $useTransactions)
    {
        $this->useDbTransactions = $useTransactions;

        return $this;
    }

    /**
     * @return static
     */
    protected function startDbTransaction()
    {
        if ($this->isUseDbTransactions()) {
            try {
                db()->startTransaction();
            } catch (Exception $exception) {
                // ignore
            }
        }

        return $this;
    }

    /**
     * @return static
     */
    protected function commitDbTransaction()
    {
        if ($this->isUseDbTransactions()) {
            try {
                db()->commitTransaction();
            } catch (Exception $exception) {
                // ignore
            }
        }

        return $this;
    }

    /**
     * @return static
     */
    protected function rollbackDbTransaction()
    {
        if ($this->isUseDbTransactions()) {
            try {
                db()->rollbackTransaction();
            } catch (Exception $exception) {
                // ignore
            }
        }

        return $this;
    }
}
