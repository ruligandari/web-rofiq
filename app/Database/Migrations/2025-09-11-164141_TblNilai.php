<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblNilai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'siswa_id'      => ['type' => 'BIGINT', 'unsigned' => true],
            'attempt'       => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'nilai_numerik' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'nilai_color'   => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'nilai_greeting' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'nilai_family'  => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'total_nilai'   => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('siswa_id', 'siswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('nilai', true);
    }

    public function down()
    {
        $this->forge->dropTable('nilai', true);
    }
}
