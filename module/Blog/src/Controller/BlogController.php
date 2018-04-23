<?php
/**
 * Created by PhpStorm.
 * User: cdcde
 * Date: 18/04/2018
 * Time: 22:34
 */

namespace Blog\Controller;
use Blog\Form\BlogForm;
use Blog\Models\poste;
use Blog\Models\posteTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController {

    private $tableBlog;


    public function __construct(posteTable $table){
        $this->tableBlog = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'posts' => $this->tableBlog->fetchAll(),
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
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('Blog', ['action' => 'index']);
        }



        $view = new ViewModel();
        $view->setVariable('details',$product);
        $view->setTemplate('blog/blog/details');
        return $view;

    }

    public function addAction()
    {
        $form = new BlogForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $blog = new poste();
        $form->setInputFilter($blog->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $blog->exchangeArray($form->getData());
        $this->tableBlog->savePoste($blog);
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

        $this->tableBlog->savePoste($poste);

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
}