<?php

    require_once '../lib/GLPIModel.php';

    use Zend\Db\Sql\Expression;

    /**
     * @brief Modèle pour un ticket de l'application GLPI
     */
    class GLPIStat extends GLPIModel {

        public $assign;
        public $closed;
        public $solved;
        public $waiting;
        public $total;
        public $month;

        public function getStats () {
            return array(
                $this->getMonthlyStats(date('m',strtotime("-1 month")), date('Y',strtotime("-1 month")), date('F',strtotime("-1 month"))),
                $this->getMonthlyStats(date('m'), date('Y'), date('F'))
                );
        }

        private function getMonthlyStats($month, $year, $letterMonth) {

            $select = $this->sql
                ->select()
                ->from(array('t' => 'glpi_tickets'))
                ->where("MONTH(date) = ".$month." and YEAR(date) = ".$year)
                ->group('status')
                ->columns(array(new Expression('COUNT(*) as total'), 'status'));
            $results = $this->select($select);

            $stat = new GLPIStat();
            $total = 0;
            foreach ($results as $row) {
                $attr = $row->status;
                $val = $row->total;
                $total += $val;
                $stat->$attr = $val;
            }
            $stat->total = $total;
            $stat->month = $letterMonth;
            return $stat;
        }

    }