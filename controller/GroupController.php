<?php

    require_once '../lib/Controller.php';
    require_once '../model/Group.php';

    /**
     * @brief Controlleur pour les groupes
     */
    class GroupController extends Controller {

        public function add () {
            if (isPost()) {
                $group = new Group();
                $group = $group->create($_POST);

                unset($group->sous_group);
                unset($group->state);
                unset($group->point);
                $group->save();
                flash('Le groupe a été rajouté', 'success');
                $this->redirect('/state/add');
            }
        }

        public function delete ($id) {
            if (loginRole() == 'admin') {
                $state = new Group();
                $state->delete($id);
                flash("La suppression à été effectué", 'success');
                $this->redirect('/');
            } else {
                $this->notAllowed();
            }
        }

        public function update ($id) {
            # code...
        }
    }