<?php 

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Form as FormsForm;

final class TaskDetailPresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function renderShow(int $taskId): void
	{    $this->setLayout('createnewtask.createNewTask');

		$this->template->currenttask = $this->database
			->table('tasks')
			->get($taskId);
        $current = $this->database
        ->table('tasks')
        ->get($taskId);

          $i = 0;
          $idtaskstatus = $this->database->table('tasks')->get($taskId);

          $currentstatusid = $this->database->table('tasks')->select('idtaskstatus')->where('id',$taskId);

          $currentcategoryid = $this->database->table('tasks')->select('idtaskcategory')->where('id',$taskId);

          $currentassignee = $this->database->table('tasks')->select('idusers')->where('id',$taskId);

          $currentclient = $this->database->table('tasks')->select('clientid')->where('id',$taskId);
          $currentAuthor = $this->database->table('comments')->select('name_id')->where('task_id',$taskId);

        $this->template->currentstatus = $this->database->table('taskstatus')->select('status')->where('id_status', $currentstatusid)->fetch();


        $this->template->currentcattegory = $this->database->table('taskcategory')->select('nameoftaskscategory')->where('id_taskcategory', $currentcategoryid)->fetch();
        
        $this->template->currentassignee = $this->database->table('login')->select('name')->where('id_users', $currentassignee)->fetch();

        $this->template->currentclient = $this->database->table('login')->select('name')->where('id_users', $currentclient)->fetch();

        $this->template->comments = $current->related('comments')->order('created_at');



        $this->template->author = $this->database->table('login')->select('name')->where('id_users', $currentAuthor)->fetch();
      //$this->database->table('taskstatus')->select('id_status, status')->fetchPairs('id_status', 'status');
	}
  public function actionEditTask($taskId){

    $this->setLayout('editTask');
    

}

public function renderEditTask($taskId) {

  


  $this->template->currentA = $this->database
  ->table('tasks')
  ->get($taskId);

  $currentTask = $this->database
  ->table('tasks')
  ->get($taskId);

if (!$currentTask) {
  $this->error('Not found');
}


$this->getComponent('editForm')
		->setDefaults($currentTask->toArray());

$currentcategoryid = $this->database->table('tasks')->select('idtaskcategory')->where('id',$taskId);
$this->template->test =  $this->database->table('tasks')->select('idusers')->where('id',$taskId)->fetch();
$currentassignee = $this->database->table('tasks')->select('idusers')->where('id',$taskId);
$this->template->contentTitle = $this->database->table('tasks')->select('title')->where('id',$taskId)->fetch()->title;
$this->template->contentContent = $this->database->table('tasks')->select('content')->where('id',$taskId)->fetch()->content;
$this->template->contentDeadline = $this->database->table('tasks')->select('deadline')->where('id',$taskId)->fetch()->deadline->format('Y-m-d');
$this->template->contentAsignee = $this->database->table('login')->select('name')->where('id_users', $currentassignee)->fetch()->name;
// $this->template->contentStatus = $this->database->table('tasks')->select('title')->where('id',$taskId)->fetch()->title;
 $this->template->contentCategory = $this->database->table('taskcategory')->select('nameoftaskscategory')->where('id_taskcategory', $currentcategoryid)->fetch()->nameoftaskscategory;


 $this->template->currentassignee2 = $this->database->table('tasks')->select('idusers')->where('id',$taskId);



 
}



 
protected function createComponentEditForm($taskId): Form {
  $form2 = new Nette\Application\UI\Form();

  $value = $this->database->table('tasks')->select('idusers')->where('id',$taskId)->fetch();
  
  $currentTask = $this->database->table('tasks')->get($taskId);
  $currentassignee2 = $this->database->table('tasks')->select('idusers')->where('id',$taskId);
  $currentTEST= $this->database->table('tasks')->select('idusers')->where('id',$taskId)->fetch();
  $currentTaskss = $this->database->table("tasks")->select("idtaskstatus")->where('id',$taskId)->fetch();
  
  $taskcategory = $this->database->table("taskcategory")
              ->select('id_taskcategory, nameOfTasksCategory')->fetchPairs('id_taskcategory', 'nameOfTasksCategory');

  $assignee = $this->database->table("login")
              ->select('id_users, name')->fetchPairs('id_users', 'name');

  $something = $this->database->table('taskstatus')->select('id_status, status')->fetchPairs('id_status', 'status');


  $form2->addText('title', 'Name of the Task')->setRequired('Cannot be empty');
  $form2->addTextArea('content', 'Content of the task')->setRequired('Cannot be empty')->setDefaultValue($this->database->query('SELECT `content` FROM `tasks` WHERE `id` = "$taskId" LIMIT 1')->fetch());
  $form2->addText('deadline','Deadline')->setHtmlType('date')->setDefaultValue((new \DateTime)->format('Y-m-d'))->setRequired('Cannot be empty');;
  $form2->addSelect('idtaskstatus', 'Status', $something)->setDefaultValue($currentTaskss);
  $form2->addSelect("idusers", "Assignee", $assignee)->setDefaultValue($value)->setHtmlId('Assignee');
  $form2->addSubmit('TaskForm','Edit Task');
  $form2->onSuccess[]= [$this, 'EditFormSuccess'];
  
  return $form2;


}

public function EditFormSuccess (Nette\Application\UI\Form $form2, array $data){
  $values = [$form2->getValues()];
  $currentId = $this->getParameter('taskId');

  
		$post = $this->database
			->table('tasks')
			->get($currentId);
		$post->update($data);

 
  $this->flashMessage("Task edited succesfully","success");   


}

protected function createComponentCommentForm(): Form
{
	$formCom = new Form; // means Nette\Application\UI\Form

	
	$formCom->addTextArea('content', 'Comment:')
		->setRequired();

	$formCom->addSubmit('sendCom', 'Add comment');
  $formCom->onSuccess[] = [$this, 'commentFormSucceeded'];
	return $formCom;
}

public function commentFormSucceeded(\stdClass $data): void
{

	$this->database->table('comments')->insert([
		'task_id' => $this->getParameter('taskId'),
		'name_id' => $this->getUser()->getIdentity()->id,
		'content' => $data->content,
	]);

	$this->flashMessage('Comment added successfully.', 'success');
	$this->redirect('this');
}


}
