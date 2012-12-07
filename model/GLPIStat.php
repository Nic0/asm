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

            $data = array('open' => array(), 'solved' => array()) ;
            foreach ($result as $row) {
                $object = new GLPIStat();
                $object->total = $row->total;
                $object->date = $row->date;
                unset($object->sql);
                unset($object->adapter);
                $data['open'][] = $object;

            }


            $select = $this->sql
                ->select()
                ->from(array('t' => 'glpi_tickets'))
                ->where("date(solvedate) > (now() - interval ".$days." day)")
                ->group(new Expression('date(solvedate)'))
                ->columns(array(new Expression('COUNT(*) as total'), new Expression('concat(day(solvedate), concat(\'/\', month(solvedate))) as date')));
            $result = $this->select($select);

            foreach ($result as $row) {
                $object = new GLPIStat();
                $object->total = $row->total;
                $object->date = $row->date;
                unset($object->sql);
                unset($object->adapter);
                $data['solved'][] = $object;

            }

            return $data;
        }

        public function getStatsType () {
            $data = array('incident' => array(), 'demande' => array());

            $result = $this->getStatsTypeRequest('1');
            $total=0;
            foreach ($result as $row) {
                if (is_numeric(substr($row->name, 0, 1))) {
                    $row->name = substr($row->name, 2);
                }
                $data['incident'][$row->name] = $row->total;
                $total += $row->total;
            }
            $data['incident']['total'] = $total;

            $result = $this->getStatsTypeRequest('2');
            $total=0;
            foreach ($result as $row) {
                if (is_numeric(substr($row->name, 0, 1))) {
                    $row->name = substr($row->name, 2);
                }
                $data['demande'][$row->name] = $row->total;
                $total += $row->total;
            }
            $data['demande']['total'] = $total;

            arsort($data['incident']);
            arsort($data['demande']);

            return $data;
        }

        private function getStatsTypeRequest ($type) {
            $days = days_from_open_days(10);

            $select = $this->sql
                ->select()
                ->from(array('t' => 'glpi_tickets'))
                ->join(array('c' => 'glpi_itilcategories'), 't.itilcategories_id = c.id', array())
                ->join(array('cc' => 'glpi_itilcategories'), 'c.itilcategories_id = cc.id', array('name'))
                ->where("date(date) > (now() - interval ".$days." day)")
                ->where('type = '.$type)
                ->group('name')
                ->columns(array(new Expression('COUNT(*) as total')));

            return $this->select($select);
        }
    }


/*
SELECT count(*), cc.name FROM `glpi_tickets` as t
inner join glpi_itilcategories as c on t.itilcategories_id = c.itilcategories_id
inner join glpi_itilcategories as cc on t.itilcategories_id = cc.id
group by c.itilcategories_id
order by date desc
 */