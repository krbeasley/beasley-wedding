<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Insertable;

class Party implements Insertable
{
    readonly string $tableName;
    public function __construct(
        public string $partyName,
        public int $size,
        public int $id = -1,
        public bool $deleted = false,
    )
    {
        $this->tableName = "tbl_parties";
    }
    public function dbColumns(): array
    {
        $cols = $this->insertableColumns();
        $returnCols = ['id' => $this->id];
        foreach ($cols as $col) {
            $returnCols[$col] = $col;
        }

        return $returnCols;
    }

    public function insertableColumns(): array
    {
        return [
            'name' => $this->partyName,
            'size' => $this->size,
            'deleted' => $this->deleted,
        ];
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}
