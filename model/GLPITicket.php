<?php

    require_once '../lib/GLPIModel.php';

    use Zend\Db\Sql\Sql;

    class GLPITicket extends GLPIModel {

        public $name;
        public $content;
        public $priority;
        public $status;
        public $realname;
        public $firstname;
        public $date_mod;
        public $date;

        public function getLast () {
            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from(array('t' => 'glpi_tickets'))
                   ->join(array('r' => 'glpi_tickets_users'), 't.id = r.tickets_id', array())
                   ->join(array('u' => 'glpi_users'), 'r.users_id = u.id', array('realname', 'firstname'))
                   ->limit('20')
                   ->order('t.date_mod DESC')
                   ->where("status != 'closed' AND status != 'solved' AND r.type=1")
                   ->columns(array('date', 'name', 'content', 'priority', 'status', 'date_mod'));
            $results = $this->select($select);

            $data = array();
            foreach ($results as $row) {
                $data[] = $this->createObjectFromSingleData($row, "GLPITicket");
            }

            return $data;
        }

    }