<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTerlambatMenitToPresents extends Migration
{
    public function up()
    {
        $this->forge->addColumn('presents', [
            'terlambat_menit' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Menit keterlambatan (dihitung saat absen masuk)'
            ],
        ]);
    }

    public function down()
    {
        if ($this->db->fieldExists('terlambat_menit', 'presents')) {
            $this->forge->dropColumn('presents', 'terlambat_menit');
        }
    }
}
