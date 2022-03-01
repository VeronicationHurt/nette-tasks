<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class AllUsersPresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}


    public function renderDisplayUsers(): void
{
	$this->template->users = $this->database
		->table('users')
		->limit(5);


$this->template->posts = $this->database
		->table('posts')
		->limit(5);

		$this->template->taskcategory = $this->database->table('taskcategory');

		

		$this->template->alltasks = $this->database->query('
		SELECT `tasks`.*,`users`.`name`,`taskstatus`.`status`, `taskcategory`.`nameOfTasksCategory` FROM `tasks`
		LEFT JOIN `taskstatus` ON `idtaskstatus` = `taskstatus`.`id_status`
		LEFT JOIN `users` ON `idusers` = `users`.`id_user`
		LEFT JOIN `taskcategory` ON `idtaskcategory` = `taskcategory`.`id_taskcategory`

		
			');
			
			

    }


	// KIND OF WORKING RIGHT 
	// SELECT * FROM `tasks`		LEFT JOIN `taskstatus` ON `idtaskstatus` = `taskstatus`.`id_status`		LEFT JOIN `users` ON `id_user` = `users`.`id_user`
//	ALTER TABLE tasks ADD CONSTRAINT FK3 FOREIGN KEY (idtaskstatus) REFERENCES taskstatus(id_status)

//ALTER TABLE tasks ADD CONSTRAINT FK4 FOREIGN KEY (idtaskcategory) REFERENCES taskcategory(id_taskcategory)

	
}