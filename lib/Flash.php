<?php

    /**
     * @brief messages utilisé dans les sessions pour prévenir des actions.
     * Par exemple si une action à été correctement accomplis.
     *
     * Le message flash est stocké dans la session, afin d'être affiché lors
     * de la prochaine page, ou rafraichissement.
     */
    class Flash {

        /** @brief contenu du message */
        public $message;
        /**
         * brief niveau du message
         *
         * Niveau du message, qui affectera la classe de la div, les différents
         * niveau seront gérer par le CSS. Trois niveaux gérés par le css:
         * success (vert), info (bleu), error (rouge), warning (jaune)
         *
         * Le nom des classes sont reprise selon
         * http://twitter.github.com/bootstrap/components.html#alerts
         *
         * @var [type]
         */
        public $level;

        /**
         * Le constructeur se limite à affecter le message et le niveau
         * @param string $message message à afficher
         * @param string $level   niveau de sévérité (success, info, warning, error)
         */
        public function __construct($message, $level) {
            $this->message = $message;
            $this->level = $level;
        }

    }