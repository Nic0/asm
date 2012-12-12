<?php

    require_once '../lib/Controller.php';
    require_once '../model/Group.php';
    require_once '../vendor/SimpleImage.php';

    /**
     * @brief Controlleur pour les groupes
     */
    class GroupController extends Controller {

        public function add () {
            if (isPost()) {
                $group = new Group();
                $group = $group->create($_POST);
                $group->logo = $_FILES["logo"]["name"];


                move_uploaded_file($_FILES["logo"]["tmp_name"],
                                   "img/upload/" . $_FILES["logo"]["name"]);

                $image = new SimpleImage();
                $image->load("img/upload/" . $group->logo);
                $image->resizeToHeight(48);
                $image->save("img/upload/" . $group->logo);

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
            if (loginRole() == 'admin') {
                if (!isPost()) {
                    $group = new Group();
                    $this->addData('group', $group->getById($id))
                         ->addData('groups', $group->nested($group->getAll()))
                         ->render();

                } else {
                    $group = new Group();
                    $group->update($id, $_POST);
                    flash("La mise à jour à été effectué", "success");
                    $this->redirect('/');
                }
            } else {
                $this->notAllowed();
            }
        }
    }