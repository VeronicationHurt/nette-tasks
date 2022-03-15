<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;


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

        $this->setLayout('createnewtask.createNewTask');
        

    }

    public function actionNewMember(){

        $this->setLayout('admin.signIn');
        
    }



    protected function createComponentNewMemberForm(): Form {
        $form = new Nette\Application\UI\Form();

        


        $form->addText('name', 'Username')->setRequired('Name is requried.');
        $form->addEmail('email', 'E-mail')->setRequired('E-mail is requried.')
		->addRule(function ($control) {
            return !$this->database->query('SELECT `email` FROM `login` WHERE `email` = ? LIMIT 1', $control->value)->fetch();
            }, ('E-mail already exist.'));
        $form->addPassword('password','Password')->setRequired('Password is requried.');
        $form->addSubmit('addNew','Add New Member');
        $form->onSuccess[]= [$this, 'newMemberFormSuccess'];
        
        
        return $form;


 }


 public function newMemberFormSuccess (Nette\Application\UI\Form $form){
    $values = $form->getValues();

    try {


       $values["password"] = @password_hash($values->password, PASSWORD_DEFAULT);
       $values["role"] = 'Admin';
    $values = $this->database
        ->table('login')
        ->insert($values);
        
        $this->flashMessage("New member was added succesfully.", 'success');
        } catch(AuthenticationException $e) {

            $this->flashMessage($e->getMessage(), "danger");
            $this->redirect("dashboard");

        }
        $this->redirect("dashboard");



     
   
   


    // try {
    // $this->getUser()->login($values->username, $values->password);
    // } catch(AuthenticationException $e) {

    //     $this->flashMessage($e->getMessage(), "danger");
    //     $this->redirect("signIn");

    // }
    // $this->redirect("dashboard");

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
	
    

}
