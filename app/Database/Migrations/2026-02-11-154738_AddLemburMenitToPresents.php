<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLemburMenitToPresents extends Migration
{
    public function up()
    {
        $fields = [
            'lembur_menit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'terlambat_menit',
            ],
        ];
        $this->forge->addColumn('presents', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('presents', 'lembur_menit');
    }
}
