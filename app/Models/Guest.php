<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Insertable;

class Guest implements Insertable
{
    readonly string $tableName;
    public function __construct(
        public string $firstName,
        public string $lastName,
        public int $id = -1,
        public int $partyId = -1,
        public bool $allowedPlusOne = false,
        public bool $deleted = false,
    )
    {
        $this->tableName = "tbl_guest";
    }

    public function insertableColumns() : array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'party_id' => $this->partyId,
            'allowed_plus_one' => $this->allowedPlusOne,
            'deleted' => $this->deleted,
        ];
    }

    public function dbColumns() : array
    {
        $cols = $this->insertableColumns();
        $returnCols = ['id' => $this->id];
        foreach ($cols as $key => $value) {
            $returnCols[$key] = $value;
        }

        return $returnCols;
    }

    public function getTableName() : string
    {
        return $this->tableName;
    }
}
