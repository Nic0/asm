<?php

    require_once '../lib/PhpAsmModel.php';

    /**
     * @brief Etat d'un élément Zabbix (base phpasm)
     */
    class Group extends PhpAsmModel {

        public $id;
        public $name;
        public $logo;
        public $coeff;
        public $group_id;
        public $sous_group = array();
        public $state = array();
        public $point;

        public function nested ($groups) {
            $data = array();
            foreach ($groups as $g) {
                if ($g->group_id == 0) {
                    $data[$g->id] = $g;
                } else {
                    $data[$g->group_id]->sous_group[$g->id] = $g;
                }
            }

            return $data;
        }

        public function update ($id, $values) {
            $update = $this->sql->update();

            if ($_FILES["logo"]["error"] != 0) {
                $update->table('group')->where('id='.$id)->set(array(
                    'name' => $values['name'],
                    'coeff' => $values['coeff'],
                    'group_id' => $values['group_id']));

            } else {
                $group->logo = $_FILES["logo"]["name"];


                move_uploaded_file($_FILES["logo"]["tmp_name"],
                                   "img/upload/" . $_FILES["logo"]["name"]);

                $image = new SimpleImage();
                $image->load("img/upload/" . $group->logo);
                $image->resizeToHeight(48);
                $image->save("img/upload/" . $group->logo);

                $update->table('group')->where('id='.$id)->set(array(
                    'name' => $values['name'],
                    'coeff' => $values['coeff'],
                    'group_id' => $values['group_id'],
                    'logo' => $group->logo));

            }
            $statement = $this->sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }

    }