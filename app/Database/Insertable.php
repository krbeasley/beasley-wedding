<?php

namespace App\Database;

interface Insertable
{
    /** Returns and array mapping of ALL the object's database columns and their values.
     *
     * @return array
     */
    public function dbColumns() : array;

    /** Returns an array mapping the object's insertable database columns, and their
     * respective values.
     *
     * @return array
     */
    public function insertableColumns(): array;

    /** Returns the object's database table name */
    public function getTableName() : string;
}
