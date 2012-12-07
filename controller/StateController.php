<?php

    require_once '../lib/Controller.php';
    require_once '../model/ZabbixHost.php';
    require_once '../model/ZabbixItem.php';
    require_once '../model/State.php';
    require_once '../model/DisplayState.php';
    require_once '../model/GLPIStat.php';

    /**
     * @brief Controlleur des États (correspondant à Zabbix)
     */
    class StateController extends Controller {

        /**
         * @brief Ajout d'un état, gestion du formulaire
         */
        public function add () {
            if(!isPost()) {
                $zHost = new ZabbixHost();
                $group = new Group();
                $this->addData('host', $zHost->getAll())
                     ->addData('groups', $group->nested($group->getAll()))
                     ->render();
            } else {
                $state = new State();
                $state->create($_POST)->save();
                flash("L'ajout à été effectué", 'success');
                $this->redirect('/');
            }
        }

        /**
         * @brief Utile pour charger dynamiquement la liste des éléments en fonction
         * d'un host
         * @return NoneS
         */
        public function ajax_item () {
            $hostid = $_POST['hostid'];
            $zItem = new ZabbixItem();
            $group = new Group();

            $this->addData('item', $zItem->getByHost($hostid))
                 ->addData('group', $group->nested($group->getAll()))
                 ->render();
        }

        /**
         * @brief Suppression d'un état
         * @param  int   $id identifiant de l'état
         * @return None
         */
        public function delete ($id) {
            if (loginRole() == 'admin') {
                $state = new State();
                $state->delete($id);
                flash("La suppression à été effectué", 'success');
                $this->redirect('/');
            } else {
                $this->notAllowed();
            }
        }

        /**
         * @brief Mise à jour d'un état
         * @param  int $id identifiant de l'état à mettre à jour
         * @return None
         */
        public function update ($id) {
            if (loginRole() == 'admin') {
                if (!isPost()) {
                    $state = new State();
                    $group = new Group();

                    $this->addData('state', $state->getById($id))
                         ->addData('group', $group->nested($group->getAll()))
                         ->render();
                } else {
                    $state = new State();
                    $state->update($id, $_POST);
                    flash("La mise à jour à été effectué", "success");
                    $this->redirect('/');
                }
            } else {
                $this->notAllowed();
            }
        }
    }