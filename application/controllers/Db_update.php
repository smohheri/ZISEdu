<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "Updating DB...\n";

// Assuming we are in a controller, or we just write a raw PHP script that bootstraps CI.
// The easiest is to make a temporary controller:

class Db_update extends CI_Controller {
    public function index() {
        $this->load->database();
        $this->load->dbforge();

        $fields = array(
            'id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'nomor_transaksi' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'tanggal_transaksi' => array(
                'type' => 'DATE'
            ),
            'muzakki_id' => array(
                'type' => 'INT'
            ),
            'fitrah_id' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'mal_id' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'infaq_id' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'created_at' => array(
                'type' => 'DATETIME'
            ),
            'created_by' => array(
                'type' => 'INT',
                'null' => TRUE
            )
        );

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field($fields);
        
        if (!$this->db->table_exists('transaksi_terpadu')) {
            $this->dbforge->create_table('transaksi_terpadu');
            echo "Table transaksi_terpadu created.\n";
        } else {
            echo "Table transaksi_terpadu already exists.\n";
        }
    }
}
