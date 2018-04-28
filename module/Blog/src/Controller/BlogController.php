<?php
/**
 * Created by PhpStorm.
 * User: cdcde
 * Date: 18/04/2018
 * Time: 22:34
 */

namespace Blog\Controller;
use Blog\Form\BlogForm;
use Blog\Form\CommentairesForm;
use Blog\Models\commentaire;
use Blog\Models\commentaireTable;
use Blog\Models\poste;
use Blog\Models\posteTable;
use User\Services\UserManager;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController {

    private $tableBlog;
    private $authService;
    private $_userManager;
    private $listeCommentaire;


    public function __construct(posteTable $table,AuthenticationService $authService,UserManager $userManager, commentaireTable $commentaireTable){
        $this->tableBlog = $table;
        $this->authService = $authService;
        $this->_userManager = $userManager;
        $this->listeCommentaire = $commentaireTable;
    }

    public function indexAction()
    {
        $identity = $this->authService->getIdentity();
        $privilege = $this->_userManager->findPrivilege($identity);
        return new ViewModel([
            'posts' => $this->tableBlog->fetchAll(),
            'privilege' => $privilege,
        ]);
    }

    public function detailsAction(){

        $id = (int) $this->params()->fromRoute('id', 0);

        /*
        if (0 === $id) {
            return $this->redirect()->toRoute('Blog', ['action' => 'index']);
        }
*/

        try {
             $product = $this->tableBlog->getPoste($id);
            $listeCommantaires = $this->listeCommentaire->getListeCommentaireParPost($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('Blog', ['action' => 'index']);
        }



        $view = new ViewModel();
        $identity = $this->authService->getIdentity();
        $user = $this->_userManager->findByUsername($identity);
        $view->setVariable('details',$product);
        $view->setVariable('user',$user);
        $view->setVariable('listeCommentaires',$listeCommantaires);
        $view->setTemplate('blog/blog/details');
        return $view;

    }

    public function addAction()
    {
        $form = new BlogForm();
        $identity = $this->authService->getIdentity();
        $user = $this->_userManager->findByUsername($identity);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $blog = new poste();
        $userName = $user->username;
        $form->setInputFilter($blog->getInputFilter());
        $form->setData($request->getPost());


        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $blog->exchangeArray($form->getData());
        $this->tableBlog->savePoste($blog,$userName);
        return $this->redirect()->toRoute('Blog');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('Blog', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $poste = $this->tableBlog->getPoste($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('Blog', ['action' => 'index']);
        }

        $form = new BlogForm();
        $identity = $this->authService->getIdentity();
        $user = $this->_userManager->findByUsername($identity);
        $userName = $user->username;
        $form->bind($poste);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($poste->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->tableBlog->savePoste($poste,$userName);

        // Redirect to album list
        return $this->redirect()->toRoute('Blog', ['action' => 'index']);
    }



    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('Blog');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->tableBlog->deletePoste($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('Blog');
        }

        return [
            'id'    => $id,
            'poste' => $this->tableBlog->getPoste($id),
        ];
    }

    public function ajouterCommentaireAction(){

        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('Blog', ['action' => 'add']);
        }


        $i=$this->listeCommentaire->getLastidComm();
        $form = new CommentairesForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $idPost = $id;
        $commentaire = new Commentaire();
        $identity = $this->authService->getIdentity();
        $user = $this->_userManager->findByUsername($identity);
        $userId = $user->id;
        $form->setInputFilter($commentaire->getInputFilter());
        $form->setData($request->getPost());


        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $commentaire->exchangeArray($form->getData());
        $this->listeCommentaire->saveCommentaire($commentaire,$userId,$idPost,$i);
        return $this->redirect()->toRoute('Blog');


    }


    public function deleteCommentaireAction(){
        $id = (int) $this->params()->fromRoute('id', 1);


        /* if (!$id) {
             return $this->redirect()->toRoute('Blog');
         }

        */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id_commentaire');
                $this->listeCommentaire->deleteCommentaire($id);
            }

            return $this->redirect()->toRoute('Blog');
        }

        return [
            'id'    => $id,
            'comm' => $this->listeCommentaire->getCommentaire($id),
        ];
    }

}