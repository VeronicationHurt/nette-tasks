<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

final class CreateNewTaskPresenter extends Nette\Application\UI\Presenter
{
    private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

    public function startup(){
        parent::startup();

        if($this->getUser()->isLoggedIn()=== false && $this->getAction() !== 'signIn') {
            $this->flashMessage('Pro založení úkolu se musíte přihlásit.', 'danger');
            $this->redirect('signIn');


        }



    }

    public function actionDashboard(){
        $this->setLayout('admin.createNewTask');
        

    }
    public function renderDefault(){
        $this->template->taskCategory = $this->database->table('taskcategory');
    }


    // I DONT FUCKING UNDERSTAND WHY NOT HERE
//     protected function createComponentTaskForm(): Form {
//         $form2 = new Nette\Application\UI\Form();


//         $category = $this->database->table("taskcategory")
//                     ->select('id, nameOfTasksCategory')->fetchPairs('id', 'nameOfTasksCategory');

//         $assignee = $this->database->table("users")
//                     ->select('id, name')->fetchPairs('id', 'name');

//         $form2->addText('title', 'Name of the task');
//         $form2->addTextArea('content', 'Content');
//         $form2->addText('deadline','Deadline')->setHtmlType('date')->setDefaultValue((new \DateTime)->format('Y-m-d'));
//         $form2->addMultiSelect('safenm', 'Category', $category)->setHtmlId('Category');
//         $form2->addMultiSelect("idusers", "Assignee", $assignee)->setHtmlId('Assignee');
//         $form2->addSubmit('TaskForm','Create New Task');
//         $form2->onSuccess[]= [$this, 'TaskFormSuccess'];
        
//         return $form2;


//  }
    public function actionTaskForm(){
      //vložit do db
        

    }
}