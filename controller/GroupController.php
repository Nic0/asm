<?php

    require_once '../lib/Controller.php';
    require_once '../model/Group.php';

    /**
     * @brief Controlleur pour les groupes
     */
    class GroupController extends Controller {

        public function add () {
            if (!isPost()) {
                $group = new Group();
                $this->addData('groups', $group->nested($group->getAll()))->render();
            } else {
                $group = new Group();
                $group = $group->create($_POST);

                unset($group->sous_group);
                unset($group->state);
                unset($group->point);
                $group->save();
            }
        }

    }