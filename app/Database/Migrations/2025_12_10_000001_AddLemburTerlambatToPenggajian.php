<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLemburTerlambatToPenggajian extends Migration
{
    public function up()
    {
        // Check if columns already exist before adding them
        $fields = $this->db->getFieldData('penggajian');
        $columnNames = array_column($fields, 'name');

        $newColumns = [];

        if (!in_array('lembur', $columnNames)) {
            $newColumns['lembur'] = [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
                'comment' => 'Jam lembur'
            ];
        }

        if (!in_array('terlambat', $columnNames)) {
            $newColumns['terlambat'] = [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
                'comment' => 'Jam terlambat'
            ];
        }

        if (!in_array('potongan', $columnNames)) {
            $newColumns['potongan'] = [
                'type' => 'BIGINT',
                'null' => true,
                'default' => 0,
                'comment' => 'Potongan gaji dari keterlambatan'
            ];
        }

        if (!empty($newColumns)) {
            $this->forge->addColumn('penggajian', $newColumns);
        }
    }

    public function down()
    {
        // Drop columns if they exist
        if ($this->db->fieldExists('lembur', 'penggajian')) {
            $this->forge->dropColumn('penggajian', 'lembur');
        }

        if ($this->db->fieldExists('terlambat', 'penggajian')) {
            $this->forge->dropColumn('penggajian', 'terlambat');
        }

        if ($this->db->fieldExists('potongan', 'penggajian')) {
            $this->forge->dropColumn('penggajian', 'potongan');
        }
    }
}
