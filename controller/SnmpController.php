<?php

    require_once '../lib/Controller.php';
    require_once '../model/Snmp.php';
    require_once '../model/SnmpInput.php';

    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Insert;

    /**
     * @brief Controlleur pour le SNMP
     */
    class SnmpController extends Controller {

        /**
         * @brief Ajoute un SNMP dans la base
         */
        public function add () {
            if(isPost()) {
                $snmp = new Snmp();
                $snmp->create($_POST)->save();
                flash('Élément SNMP rajouté', 'success');
                $this->redirect('/');
            }
        }

        /**
         * @brief fournire des données dans la base snmp_input, en fonction des snmp disponible
         * @return None
         * @note URL accédé par Cron
         */
        public function feed () {
            $snmp = new Snmp();
            $snmpList = $snmp->getAll();

            $si = new SnmpInput();
            foreach ($snmpList as $snmp) {
                $si->save($snmp);
            }

            $this->purge();
        }

        /**
         * @brief Supprime les vieilles données de snmp_input
         * @return None
         * @note URL accédé par Cron
         */
        private function purge () {
            $snmp = new SnmpInput();
            $snmp->purge();
        }

        /**
         * @brief Suppression d'un élément SNMP (base snmp)
         * @param  int $id identifiant à supprimer
         * @return None
         */
        public function delete ($id) {
            if (loginRole() == 'admin') {
                $snmp = new Snmp();
                $snmp->delete($id);
                flash('Élément SNMP supprimé', 'success');
                $this->redirect('/');
            } else {
                $this->notAllowed();
            }
        }

        /**
         * @brief Mise à jour d'un élément SNMP dans la base snmp
         * @param  int $id identifant snmp à mettre à jour
         * @return None
         */
        public function update ($id) {
            if (loginRole() == 'admin') {
                if (isPost()) {
                    $snmp = new Snmp();
                    $snmp->update($id, $_POST);
                    flash('Élément SNMP mis à jour', 'success');
                    $this->redirect('/');
                } else {
                    $snmp = new Snmp();
                    $this->addData('snmp', $snmp->getById($id));
                    $this->render();
                }
            } else {
                $this->notAllowed();
            }
        }

    }