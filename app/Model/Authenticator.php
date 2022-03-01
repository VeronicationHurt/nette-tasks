<?php declare (strict_types=1);

use Nette\Security\IIdentity;
use \Nette\Database\Explorer;
use \Nette\Security\Passwords;
use \Nette\Security;

class Authenticator implements \Nette\Security\Authenticator {
    
    private \Nette\Database\Explorer $database;
    private \Nette\Security\Passwords $passwords;

    public function __construct(\Nette\Database\Explorer $database,\Nette\Security\Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
       
    }

    public function authenticate(string $username, string $password): IIdentity {
        
        $user = $this->database->table('login')->where('name', $username)->fetch();

        if ($user === null)

            throw new \Nette\Security\AuthenticationException("Uživatel nenalezen.");
            
        

        if ($this->passwords->verify($password, $user->password) === false)
            throw new \Nette\Security\AuthenticationException("Špatné heslo.");
    

    return new \Nette\Security\SimpleIdentity($user->id, ['name' => $user->name]);


}

}