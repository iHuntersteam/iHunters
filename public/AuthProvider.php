<?php

namespace Bakautov\Auth;

class AuthProvider
{
    public function login($credentials)
    {
        if (!$this->validateCredentials($credentials)) {
            throw new \Exception("Для авторизации нужны username и password");
        }

        $this->getUserFromBase($credentials);
    }

    protected function validateCredentials($credentials)
    {
        if (!is_array($credentials)) {
            throw new \Exception("Данные для аутентификации должны быть в виде массива");
        }

        return isset($credentials['username']) and isset($credentials['password']);
    }

    /**
     * @return \mysqli
     */
    private function makeDbConnection()
    {
        $host = 'localhost';
        $engine = 'mysql';
        $user = 'root';
        $password = 'Nsk!2015';
        $dbname = 'ihunters';
        $dsn = "$engine:dbname=$dbname;host=$host";

        $dbRes = new \mysqli($host, $user, $password, $dbname);

        if (mysqli_connect_errno()) {
            printf("Не удалось подключиться: %s\n", mysqli_connect_error());
            exit();
        }

        return $dbRes;
    }

    private function getUserFromBase($credentials)
    {
        $dbResource = $this->makeDbConnection();
        $stmt = $dbResource->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $credentials['username']);
        if (!$stmt->execute()) {
            print_r($dbResource->error_list);
            exit();
        }


        var_dump(($stmt));

    }
}