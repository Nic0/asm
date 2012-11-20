<?php

    require_once '../lib/GLPIModel.php';

    use Zend\Db\Sql\Sql;

    /**
     * @brief Modèle pour un ticket de l'application GLPI
     */
    class GLPITicket extends GLPIModel {

        /** @brief Titre */
        public $name;
        /** @brief Contenu (non utilisé) */
        public $content;
        /** @brief Niveau de priorité (1-6) */
        public $priority;
        /** @brief En cours, cloturé, résolu ? */
        public $status;
        /** @brief Nom du demandeur */
        public $realname;
        /** @brief Prénom du demandeur */
        public $firstname;
        /** @brief Date de la dernière modification */
        public $date_mod;
        /** @brief Date de création (non utilisé) */
        public $date;

        public $type;

        /**
         * @brief Permet d'obtenir les X derniers tickets
         * @return array(GLPITicket) Tableau d'objet des X derniers tickets GLPI
         *
         * La requête est construite avec un select de Zend (\Db\Sql\Sql)
         *
         * Deux paramètres pris dans la configuration permet de construire
         * la requête.
         *
         * - db:glpi:limit: Le nombre d'éléments à retourner
         * - db:glpi:orderby: nom de la colonne pour lequel le résultat sera trié
         *                    par défaut, ce devrait être t.data_mod
         *
         * La requête SQL est construite sur une jointure de trois table:
         *
         * `glpi_tickets(t)->glpi_tickets_users(r)->glpi_users(u)`
         *
         * L'alias `r` de la table glpi_tickets_users signifie "relation".
         *
         */
        public function getLast () {

            $config = AsmConfig::getConfig();

            $this->sql = new Sql($this->adapter);
            $select = $this->sql->select();
            $select->from(array('t' => 'glpi_tickets'))
                   ->join(array('r' => 'glpi_tickets_users'), 't.id = r.tickets_id', array('type'))
                   ->join(array('u' => 'glpi_users'), 'r.users_id = u.id', array('realname', 'firstname'))
                   ->limit($config->db->glpi->limit)
                   ->group('t.id')
                   ->order($config->db->glpi->orderby . ' DESC')
                   ->where("status != 'closed' AND status != 'solved'")
                   ->columns(array('date', 'name', 'content', 'priority', 'status', 'date_mod'));
            $results = $this->select($select);

            $data = $this->createObjectFromArrayData($results);

            return $data;
        }

    }