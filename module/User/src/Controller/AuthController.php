<?php
namespace User\Controller;

use User\Form\SignUpForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Result;
use Zend\Uri\Uri;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use User\Services\UserManager;
use User\Services\AuthManager;
use User\Form\LoginForm;


/**
 * Controleur d'authentification, gère le login et le logout
 */

class AuthController extends AbstractActionController
{
    // Gestion de l'utilisateur en base de donnée
    private $_userManager;
    // Gestionnaire d'authentification
    private $_authManager;

    public function __construct(UserManager $userManager, AuthManager $authManager)
    {
        $this->_userManager = $userManager;
        $this->_authManager = $authManager;
    }

    public function loginAction()
    {

        /*
        // Récupération du paramètre redirectUrl
        // permettant de rediriger l'utilisateur vers sa page de base
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        // Si la redirection fait plus de 2048 caractère refuser
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        */

        // Initialisation du formulaire de connexion
        $form = new LoginForm();
        //$form->get('redirect_url')->setValue($redirectUrl);

        $isLoginError = false;

        // Si c'est une requête POST
        if ($this->getRequest()->isPost()) {

            // Récupère les données du body
            $data = $this->params()->fromPost();
            // Set les données du formulaire
            $form->setData($data);
            // Vérifie si le formulaire est valide
            if ($form->isValid()) {
                // Récupère les données du formulaire
                $data = $form->getData();
                // Appel le gestionnaire d'authentification pour se connecter
                $result = $this->_authManager->login($data['username'], $data['password']);

                if ($result->getCode() == Result::SUCCESS) {
                    $redirectUrl = $this->params()->fromPost('redirect_url', 'Blog');

                    // Si l'URL est non vide on vérifie qu'elle est valide
                    if (!empty($redirectUrl)) {
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }


                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('Blog');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        // Renvoie la vue
        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
          //  'redirectUrl' => $redirectUrl
        ]);
    }

    public function logoutAction()
    {
        $this->_authManager->logout();

        return $this->redirect()->toRoute('Blog');
    }

    public function signUpAction()
    {


        // Récupération du paramètre redirectUrl
        // permettant de rediriger l'utilisateur vers sa page de base
   $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        // Si la redirection fait plus de 2048 caractère refuser
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }


        // Initialisation du formulaire de connexion
        $form = new SignUpForm();
        $form->get('submit')->setValue('Add');


        // Si c'est une requête POST
        if ($this->getRequest()->isPost()) {

            // Récupère les données du body
            $data = $this->params()->fromPost();
            // Set les données du formulaire
            $form->setData($data);
            // Vérifie si le formulaire est valide
            if ($form->isValid()) {
                // Récupère les données du formulaire
                $data = $form->getData();

                $this->_userManager->addUser($data);


            }

        }
        return new ViewModel([
            'form' => $form,
            'redirectUrl' => $redirectUrl
        ]);


    }
}
?>