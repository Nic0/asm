<?php

    function debug($data, $param=null) {
        $conf = AsmConfig::getConfig();

        if ($param === null || $conf->debug->$param) {
            echo ">>> debug de " . $param;
            var_dump($data);
            echo "<<< fin debug de " . $param . "<br /><br />";
        }
    }

    /**
     * @brief savoir si une requête provient d'un appel en AJAX
     * @return boolean vrai si c'est de l'AJAX
     */
    function isAjax () {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
                $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    /**
     * @brief savoir si une requête provient d'un appel avec POST
     * @return boolean vrai si c'est du POST
     */
    function isPost () {
        return ($_SERVER['REQUEST_METHOD'] == 'POST');
    }

    function time2str($ts)
    {
        if(!ctype_digit($ts))
            $ts = strtotime($ts);

        $diff = time() - $ts;
        if($diff == 0)
            return 'now';
        elseif($diff > 0)
        {
            $day_diff = floor($diff / 86400);
            if($day_diff == 0)
            {
                if($diff < 60) return 'just now';
                if($diff < 120) return '1 minute ago';
                if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if($diff < 7200) return '1 hour ago';
                if($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if($day_diff == 1) return 'Yesterday';
            if($day_diff < 7) return $day_diff . ' days ago';
            if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
            if($day_diff < 60) return 'last month';
            return date('F Y', $ts);
        }
        else
        {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0)
            {
                if($diff < 120) return 'in a minute';
                if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
                if($diff < 7200) return 'in an hour';
                if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
            }
            if($day_diff == 1) return 'Tomorrow';
            if($day_diff < 4) return date('l', $ts);
            if($day_diff < 7 + (7 - date('w'))) return 'next week';
            if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
            if(date('n', $ts) == date('n') + 1) return 'next month';
            return date('F Y', $ts);
        }
    }

    /**
     * @brief merge deux arrays, repris sur
     * http://www.php.net/manual/en/function.array-merge-recursive.php#92195
     */
    function array_merge_recursive_distinct ( array &$array1, array &$array2 ) {
      $merged = $array1;

      foreach ( $array2 as $key => &$value ) {
        if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
          $merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
        } else {
          $merged [$key] = $value;
        }
      }

      return $merged;
    }

    /**
     * Créer un objet `Flash` en l'affectant à la session.
     * @param  string $msg message à affiche
     * @param  string $lvl niveau d'alerte (success, info, warning)
     * @return None
     */
    function flash($msg, $lvl="warning") {
        $flash = new Flash($msg, $lvl);
        $_SESSION['flash'] = $flash;
    }