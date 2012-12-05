<?php

    require_once '../lib/GLPIModel.php';

    use Zend\Db\Sql\Expression;

    /**
     * @brief Modèle pour un ticket de l'application GLPI
     */
    class GLPIStat extends GLPIModel {

        public $total;
        public $date;

        public function getStats () {
            $days = days_from_open_days(20);

            $select = $this->sql
                ->select()
                ->from(array('t' => 'glpi_tickets'))
                ->where("date(date) > (now() - interval ".$days." day)")
                ->group(new Expression('date(date)'))
                ->columns(array(new Expression('COUNT(*) as total'), new Expression('concat(day(date), concat(\'/\', month(date))) as date')));
            $result = $this->select($select);

            $data = array();
            foreach ($result as $row) {
                $object = new GLPIStat();
                $object->total = $row->total;
                $object->date = $row->date;
                unset($object->sql);
                unset($object->adapter);
                $data[] = $object;

            }

            return $data;
        }
    }
