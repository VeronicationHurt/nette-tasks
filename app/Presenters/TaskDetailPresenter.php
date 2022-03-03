<?php 

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

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


          $idtaskstatus = $this->database->table('tasks')->get($taskId);

          $currentstatusid = $this->database->table('tasks')->select('idtaskstatus')->where('id',$taskId);

          $currentcategoryid = $this->database->table('tasks')->select('idtaskcategory')->where('id',$taskId);

          $currentassignee = $this->database->table('tasks')->select('idusers')->where('id',$taskId);

          $currentclient = $this->database->table('tasks')->select('clientid')->where('id',$taskId);


        $this->template->currentstatus = $this->database->table('taskstatus')->select('status')->where('id_status', $currentstatusid)->fetch();


        $this->template->currentcattegory = $this->database->table('taskcategory')->select('nameoftaskscategory')->where('id_taskcategory', $currentcategoryid)->fetch();
        
        $this->template->currentassignee = $this->database->table('login')->select('name')->where('id_users', $currentassignee)->fetch();

        $this->template->currentclient = $this->database->table('users')->select('name')->where('id_user', $currentclient)->fetch();

		
	}

   
}