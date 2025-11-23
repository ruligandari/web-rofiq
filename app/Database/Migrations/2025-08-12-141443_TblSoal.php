<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblSoal extends Migration
{
    public function up()
    {
        //create table soal
        $this->forge->addField([
            'id'           => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'level'        => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true, 'null' => true], // 1..4 atau NULL
            'kategori_id'  => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'foto'         => ['type' => 'TEXT', 'null' => true, 'default' => null],
            'soal'       => ['type' => 'TEXT'],
            'pilihan_a'    => ['type' => 'TEXT'],
            'pilihan_b'    => ['type' => 'TEXT'],
            'pilihan_c'    => ['type' => 'TEXT'],
            'pilihan_d'    => ['type' => 'TEXT'],
            // ENUM di Forge: aman pakai string tipe langsung
            'jawaban'      => ['type' => "ENUM('A','B','C','D')", 'null' => false],
            'bobot_nilai'  => ['type' => 'INT', 'constraint' => 10],
            'is_active'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['level', 'is_active']);
        $this->forge->addKey(['kategori_id', 'is_active']);
        $this->forge->addKey('bobot_nilai');

        $this->forge->addForeignKey('kategori_id', 'kategori', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('soal', true);
    }

    public function down()
    {
        $this->forge->dropTable('soal', true);
    }
}
