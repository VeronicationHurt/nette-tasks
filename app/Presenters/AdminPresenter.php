<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

final class AdminPresenter extends Nette\Application\UI\Presenter
{
	
    
    private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
        
	}

    public function startup(){
        parent::startup();

        if($this->getUser()->isLoggedIn()=== false && $this->getAction() !== 'signIn') {
            $this->flashMessage('Pro zobrazení se musíte přihlásit.', 'danger');
            $this->redirect('signIn');


        }



    }
    public function renderDashboard(): void
    {   $currentId =  $this->getUser()->getIdentity()->id;
       
        $this->template->count = $this->database->table('tasks')->count("*"); 
        $this->template->myTasks = $this->database->table('tasks')->where('idusers', $currentId)->count("*");
        $this->template->membersCount = $this->database->table('login')->count("*");
        $this->template->newTasksNumber =  $this->database->table('tasks')->where('idtaskstatus', 1)->count("*");
        
       


        $this->template->users = $this->database
            ->table('users'); 

            $this->template->alltasks = $this->database->query('
		SELECT `tasks`.*,`login`.`name`,`taskstatus`.`status`, `taskcategory`.`nameOfTasksCategory` FROM `tasks`
		LEFT JOIN `taskstatus` ON `idtaskstatus` = `taskstatus`.`id_status`
		LEFT JOIN `login` ON `idusers` = `login`.`id_users`
		LEFT JOIN `taskcategory` ON `idtaskcategory` = `taskcategory`.`id_taskcategory`

		
			');
    

   
    
        }

    

    public function actionSignIn(){

        $this->setLayout('admin.signIn');
    }

    public function actionDashboard(){

        $this->setLayout('admin');

    }

    public function actionCreateNewTask(){

        $this->setLayout('admin.createNewTask');

    }

    public function actionSignOut(){
        $this->getUser()->logout();
        $this->flashMessage("Jste odhlášen.","success");
        $this->redirect("signIn");

    }

    protected function createComponentSignInForm(): Form {
           $form = new Nette\Application\UI\Form();
           $form->addText('username', 'Username');
           $form->addPassword('password','Password');
           $form->addSubmit('send','Sign In');
           $form->onSuccess[]= [$this, 'signInFormSuccess'];
           
           
           return $form;


    }


    public function signInFormSuccess (Nette\Application\UI\Form $form){
        $values = $form->getValues();

        try {
        $this->getUser()->login($values->username, $values->password);
        } catch(AuthenticationException $e) {

            $this->flashMessage($e->getMessage(), "danger");
            $this->redirect("signIn");

        }
        $this->redirect("dashboard");

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
       
      
       
        // $this->getUser()->login($values->username, $values->password);
       $clientId = $this->getUser()->getIdentity()->id;

       

        
// https://doc.nette.org/cs/database/explorer *****************

        $row = $this->database->table('tasks')->insert([
     
            'content' => ($data['content']),
            'created_at' => new \DateTime(),  // nebo $explorer::literal('NOW()')
            'title' => ($data['title']), // vloží soubor
            'clientId' => $this->getUser()->getIdentity()->id,
            'deadline' => ($data['deadline']),
            'idtaskcategory' => ($data['idtaskCategory']),
            'idusers' => ($data['idusers']),
            'idtaskstatus' => 1,
        ]);
      


        // $post = $this->database
		// ->table('tasks')
		// ->insert($data);

        $this->flashMessage("Task created succesfully","success");       
       $this->redirect("dashboard");

    }


    }
