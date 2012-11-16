<?php

    use Zend\Authentication\AuthenticationService;
    use Zend\Authentication\Adapter\Ldap as AuthAdapter;
    use Zend\Authentication\Result;

    require_once '../lib/Controller.php';
    require_once '../model/User.php';

    /**
     * @brief Controlleur pour gérer les utilisateurs (login/logout)
     */
    class UserController extends Controller {

        /**
         * @brief Gestion du login d'un utilisateur
         * @return None
         */
        public function login () {

            if (isPost()) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $auth = User::authenticate($username, $password);


                switch ($auth->getCode()) {

                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        flash("L'identifiant n'a pas été trouvé", 'error');
                        $this->redirect('/login');
                        break;

                    case Result::FAILURE_CREDENTIAL_INVALID:
                        flash("Erreur d'authentification (failure_credential)", 'error');
                        $this->redirect('/login');
                        break;

                    case Result::SUCCESS:
                        $_SESSION['user'] = $auth->getIdentity();
                        $this->redirect('/');
                        break;

                    default:
                        flash("Erreur", 'error');
                        $this->redirect('/login');
                        break;
                }
            } else {
                $this->render();
            }

        }

        /**
         * @brief Gestion du logout d'un utilisateur
         * @return None
         */
        public function logout () {
            unset($_SESSION['user']);
            flash('Vous avez été correctement déconnecté', 'success');
            $this->redirect('login');
        }
    }