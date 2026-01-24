<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkorderPegawaiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'workorder_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('workorder_pegawai');
    }

    public function down()
    {
        $this->forge->dropTable('workorder_pegawai');
    }
}
