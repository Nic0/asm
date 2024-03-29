<?php

    require_once '../lib/GLPIModel.php';

    use Zend\Db\Sql\Expression;
    use Zend\Db\Adapter\Adapter;

    /**
     * @brief Modèle pour un ticket de l'application GLPI
     */
    class GLPIStat extends GLPIModel {

        public $total;
        public $date;

        // GLPI BAR
        public function getStats () {
            $config = AsmConfig::getConfig();
            $days = days_from_open_days($config->home->glpibar->days);

            // requete pour les ouvertures de tickets
            $select = $this->sql
                ->select()
                ->from(array('t' => 'glpi_tickets'))
                ->where("date(date) > (now() - interval ".$days." day)")
                ->group(new Expression('date(date)'))
                ->columns(array(new Expression('COUNT(*) as total'), new Expression('concat(DAYNAME(date), concat(\' \', concat(day(date), concat(\'/\', month(date))))) as date')));
            $result = $this->select($select);

            $data = array('open' => array(), 'solved' => array(),
                         'stock' => array(), 'sla' => array()) ;
            foreach ($result as $row) {
                $object = new GLPIStat();
                $object->total = $row->total;
                $object->date = $row->date;
                unset($object->sql);
                unset($object->adapter);
                $data['open'][] = $object;

            }

            // localisation
            foreach ($data['open'] as $open) {
                $open->date = str_replace('Monday', 'Lun', $open->date);
                $open->date = str_replace('Tuesday', 'Mar', $open->date);
                $open->date = str_replace('Wednesday', 'Mer', $open->date);
                $open->date = str_replace('Thursday', 'Jeu', $open->date);
                $open->date = str_replace('Friday', 'Ven', $open->date);
            }

            // requete pour les résolutions de tickets
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

            // requete pour le stock
            $sql = "select d.date, count(*) as total
                from (
                    select date(date) as date
                      from glpi_tickets
                     where date(date) > (now() - interval ".$days." day)
                     group by date(date) desc
                ) as d
                 inner join glpi_tickets as t on date(t.date) >= (now() - interval ". ($days + 100) ." day)

                 where d.date >= date(t.date)
                   and (date(t.closedate) >= d.date
                    or date(solvedate) >= d.date
                    or (date(closedate) IS NULL
                    and date(solvedate) IS NULL))

                 group by d.date";

            $result = $this->adapter->query($sql , Adapter::QUERY_MODE_EXECUTE);
            foreach ($result as $row) {
                $object = new GLPIStat();
                $object->total = $row->total;
                $object->date = $row->date;
                unset($object->sql);
                unset($object->adapter);
                $data['stock'][] = $object;

            }

            //requete pour le SLA
            $sql ="select count(*) as total
              FROM `glpi_tickets`
             where due_date is not null
               and date(solvedate) >= (now() - interval ".$days." day)
             group by date(solvedate)";

            $result = $this->adapter->query($sql , Adapter::QUERY_MODE_EXECUTE);
            $total = array();
            foreach ($result as $key => $row) {
                $total[$key] = $row->total;
            }

            //requete pour le SLA
            $sql ="select date(solvedate) as date, count(*) as total
              FROM `glpi_tickets`
             where solvedate < due_date
               and due_date is not null
               and date(solvedate) >= (now() - interval ".$days." day)
             group by date(solvedate)";

            $result = $this->adapter->query($sql , Adapter::QUERY_MODE_EXECUTE);

            foreach ($result as $key => $row) {
                $object = new GLPIStat();

                $object->total = (($row->total)/$total[$key])*100;

                $object->date = $row->date;
                unset($object->sql);
                unset($object->adapter);
                $data['sla'][] = $object;

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
            $config = AsmConfig::getConfig();
            $days = days_from_open_days($config->home->glpipie->days);

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