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

    public function actionCreateNewTask(){

        $this->setLayout('createnewtask.createNewTask');
        

    }

  


 


    protected function createComponentTaskForm(): Form {
        $form2 = new Nette\Application\UI\Form();

        
        $taskcategory = $this->database->table("taskcategory")
                    ->select('id_taskcategory, nameOfTasksCategory')->fetchPairs('id_taskcategory', 'nameOfTasksCategory');

        $assignee = $this->database->table("login")
                    ->select('id_users, name')->fetchPairs('id_users', 'name');

        $form2->addText('title', 'Name of the task')->setRequired('Cannot be empty');
        $form2->addTextArea('content', 'Content of the task')->setRequired('Cannot be empty');
        $form2->addText('deadline','Deadline')->setHtmlType('date')->setDefaultValue((new \DateTime)->format('Y-m-d'))->setRequired('Cannot be empty');;
        $form2->addSelect('idtaskCategory', 'Category', $taskcategory)->setHtmlId('1');
        $form2->addSelect("idusers", "Assignee", $assignee)->setHtmlId('Assignee');
        $form2->addSubmit('TaskForm','Create New Task');
        $form2->onSuccess[]= [$this, 'TaskFormSuccess'];
        
        return $form2;


 }


 public function TaskFormSuccess (Nette\Application\UI\Form $form2, array $data){
    $values = [$form2->getValues()];
   
  
     $clientId = $this->getUser()->getIdentity()->id;

   

    
// https://doc.nette.org/cs/database/explorer *****************

    $row = $this->database->table('tasks')->insert([
 
        'content' => ($data['content']),
        'created_at' => new \DateTime(),  // nebo $explorer::literal('NOW()')
        'title' => ($data['title']), 
        'clientId' => $this->getUser()->getIdentity()->id,
        'deadline' => ($data['deadline']),
        'idtaskcategory' => ($data['idtaskCategory']),
        'idusers' => ($data['idusers']),
        'idtaskstatus' => 1,
    ]);
  



    $this->flashMessage("Task created succesfully","success");       
    $this->redirect("admin:dashboard");

}


}